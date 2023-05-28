<?php

namespace App\Http\Controllers;

use adriangibbons\phpFITFileAnalysis;
use App\Http\Requests\StoreWorkoutRequest;
use App\Models\Activities;
use phpGPX\phpGPX;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function avatar(Request $request): string
    {
        $this->validate($request, [
            'avatar' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('avatar');
        $fileName = $file->hashName();
        $file->move(public_path('pictures/athletes/' . $request->user()->id), $fileName);

        $user = $request->user();
        if ($user->photo) {
            unlink(public_path('pictures/athletes/' . $request->user()->id . '/' . $user->photo));
        }
        $user->photo = $fileName;
        $user->save();

        session()->flash('success', __('File Upload successfully'));
        return redirect()->back();
    }

    public function workout(StoreWorkoutRequest $request): string
    {
        $result = false;

        foreach ($request->allFiles()['workout'] as $uploadedFile) {
            $name = $uploadedFile->hashName();
            $name = str_replace(['.xml', '.bin'], '', $name);
            $extension = $uploadedFile->getClientOriginalExtension();

            $uploadedFileName = "{$name}.{$extension}";

            $file = Storage::putFileAs(
                'public/activities/' . $request->user()->id,
                $uploadedFile,
                $uploadedFileName
            );

            if (!empty($file)) {
                switch ($extension) {
                    case 'gpx':
                        $result = $this->processGPX($file, $request, $uploadedFileName);
                        break;
                    case 'fit':
                        $result = $this->processFIT($file, $request, $uploadedFileName);
                        break;
                    case 'tcx':
                        $result = $this->processTCX($file, $request, $uploadedFileName);
                        break;
                }
            }
        }

        if ($result) {
            session()->flash('success', __('File Upload successfully'));
        } else {
            session()->flash('error', __('Error occurred'));
        }

        return redirect()->back();
    }

    /**
     * @throws \Exception
     */
    private function processFIT(string $file, StoreWorkoutRequest $request, string $filename): bool
    {
        // TODO: Рассчитать avg_pace, min_altitude, max_altitude
        $fit = new phpFITFileAnalysis(Storage::path($file), [
            'pace' => true,
        ]);

        // TODO: Переименовать type в sport, добавить subsport, завести таблички для этого дела
        // подумать как лучше заюзать $fit->manufacturer()

        $activity = new Activities();
        $activity->users_id = $request->user()->id;
        $activity->type = strtolower($fit->enumData('sport', $fit->data_mesgs['sport']['sport']));
        $activity->name = !empty($fit->data_mesgs['sport']['name']) ? $fit->data_mesgs['sport']['name'] : __('Workout');
        $activity->creator = !empty($fit->data_mesgs['file_id']['manufacturer']) ? $fit->data_mesgs['file_id']['manufacturer'] : 'Zdrava';
        $activity->distance = $fit->data_mesgs['session']['total_distance'];
        $activity->avg_speed = $fit->data_mesgs['session']['avg_speed'];
        $activity->elevation_gain = $fit->data_mesgs['session']['total_ascent'];
        $activity->elevation_loss = $fit->data_mesgs['session']['total_descent'];
        $activity->started_at = $fit->data_mesgs['session']['start_time'];
        $activity->finished_at = $fit->data_mesgs['session']['start_time'] + $fit->data_mesgs['session']['total_elapsed_time'];
        $activity->duration = $fit->data_mesgs['session']['total_timer_time'];
        $activity->file = $filename;
        $activity->save();

        return true;
    }

    private function processGPX(string $file, StoreWorkoutRequest $request, string $filename): bool
    {
        $gpx = new phpGPX();
        $gpx = $gpx->parse(Storage::get($file));

        foreach ($gpx->tracks as $track) {
            // Statistics for whole track
            $statsTrack[] = $track->stats->toArray();
        }

        $stat = array_merge_recursive($statsTrack)[0];

        $activity = new Activities();
        $activity->users_id = $request->user()->id;
        $activity->type = 'bicycle';
        $activity->name = !empty($gpx->tracks[0]->name) ? $gpx->tracks[0]->name : __('Workout');
        $activity->creator = !empty($gpx->creator) ? $gpx->creator : 'Zdrava';
        $activity->distance = $stat['distance'];
        $activity->avg_speed = $stat['avgSpeed'];
        $activity->avg_pace = $stat['avgPace'];
        $activity->min_altitude = $stat['minAltitude'];
        $activity->max_altitude = $stat['maxAltitude'];
        $activity->elevation_gain = $stat['cumulativeElevationGain'];
        $activity->elevation_loss = $stat['cumulativeElevationLoss'];
        $activity->started_at = $stat['startedAt'];
        $activity->finished_at = $stat['finishedAt'];
        $activity->duration = $stat['duration'];
        $activity->file = $filename;
        $activity->save();

        return true;
    }

    private function processTCX(string $file, StoreWorkoutRequest $request, string $filename): bool
    {
        // TODO: Найти парсер TCX файлов
        return true;
    }
}
