<?php

namespace App\Http\Livewire\Admin\Import;

use App\Models\Segment;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Strava extends Component
{
    use WithFileUploads;

    public $stravaCSV;

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.admin.import.strava');
    }

    public function upload()
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
}
