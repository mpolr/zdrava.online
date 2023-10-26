<?php

namespace App\Http\Livewire\Admin\Import;

use App\Models\Segment;
use Database\Seeders\PermissionSeeder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Redirector;
use Livewire\WithFileUploads;
use App\Jobs\ImportStravaSegments;

class Strava extends Component
{
    use WithFileUploads;

    public $stravaCSV;

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function render(): View|\Illuminate\View\View
    {
        return view('livewire.admin.import.strava');
    }

    public function upload(): RedirectResponse|Redirector
    {
        $stravaIds = [];

        $this->validate([
            'stravaCSV' => 'required|file|mimes:csv',
        ]);

        $file = $this->stravaCSV->store('temp');
        if ($handle = fopen(Storage::path($file), "r")) {
            $i = 0;
            while ($item = fgetcsv($handle, null, ",")) {
                if ($i >= 1) {
                    $stravaIds[] = $item[0];
                }
                $i++;
            }
            fclose($handle);
            Storage::delete($file);

            if (empty($stravaIds)) {
                session()->flash('error', 'Нет записей в файле');
            }

            $existIds = [];
            $segments = Segment::whereIn('strava_segment_id', $stravaIds)->get('strava_segment_id');
            if (count($segments) > 0) {
                foreach ($segments as $segment) {
                    $existIds[] = $segment->strava_segment_id;
                }
                $stravaIds = array_diff($stravaIds, $existIds);
            }

            $insertIds = [];

            foreach ($stravaIds as $item) {
                $insertIds[] = ['strava_segment_id' => $item];
            }

            if (!empty($insertIds)) {
                Segment::insert($insertIds);
            }

            session()->flash('success', "Файл успешно обработан. Записей добавлено: " . count($stravaIds));
        } else {
            session()->flash('error', 'Ошибка загрузки файла');
        }

        return redirect()->route('admin.import.strava.csv');
    }

    public function processStrava(): RedirectResponse|Redirector
    {
        $segments = Segment::where('strava_segment_id', 'IS NOT', null)
            ->where('country', null)
            ->where('elevation_high', null)
            ->limit(1000)
            ->get();

        if ($segments->count() == 0) {
            session()->flash('error', 'Нет сегментов для импорта из Strava');
            return redirect()->route('admin.import.strava.csv');
        }

        $delay = 0;
        $delayDay = 0;

        $i = 1;
        foreach ($segments as $segment) {
            if ($i % 100 === 0) {
                $delay += 960;
            }
            $delay += $delayDay;
            ImportStravaSegments::dispatch(auth()->id(), $segment)
                ->onQueue('import-strava-segments')
                ->delay(now()->addSeconds($delay));
            $i++;
        }

        session()->flash('success', count($segments) . ' сегментов будет импортировано из Strava');
        return redirect()->route('admin.import.strava.csv');
    }
}
