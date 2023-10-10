<?php

namespace App\Http\Livewire\Segments;

use App\Classes\GpxTools;
use App\Classes\Polyline;
use App\Models\Segment;
use Fit\Data;
use Fit\Exception;
use Fit\FileType;
use Fit\LeaderboardType;
use Fit\Manufacturer;
use Fit\SelectionType;
use Fit\Sport;
use Fit\Writer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Zend_Io_Exception;

class Explore extends Component
{
    private $segments;

    public function mount(Request $request): void
    {
        $pagination = 11;
        if ($request->get('show') === 'max') {
            $pagination = 9999;
        }

        $this->segments = Segment::select(['id', 'name', 'distance', 'total_elevation_gain', 'polyline', 'start_latlng'])
            ->where('name', '!=', null)
            ->where('private', '!=', 1)
            ->paginate($pagination);
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.segments.explore', [
            'segments' => $this->segments,
        ]);
    }

    /**
     * @throws Exception
     * @throws Zend_Io_Exception
     */
    public function segmentDownloadFIT(int $id): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $segment = Segment::find($id);

        $points = Polyline::decode($segment->polyline);
        $points = Polyline::pair($points);
        $borderPoints = Polyline::findExtremeCoordinates($points);

        $date = !empty($segment->created_at) ? $segment->created_at->timestamp : time();
        $timeCreated = $date - mktime(0,0,0,12,31,1989);
        $data = new Data;
        $data->setFile(FileType::segment);
        $data
            ->add('file_id', [
                'type' => FileType::segment,
                'manufacturer' => Manufacturer::strava,
                'time_created' => $timeCreated,
                'product' => 65534,
                'serial_number' => 1,
                'number' => 1,
            ])
            ->add('file_creator', [
                'hardware_version' => 0,
                'software_version' => 0,
            ])
            ->add('segment_id', [
                'name' => $segment->name,
                'enabled' => 1,
                'sport' => Sport::cycling,
                'selection_type' => SelectionType::starred,
                'uuid' => 's' . $segment->id,
            ])
            ->add('segment_leaderboard_entry', [
                'message_index' => 0,
                'type' => LeaderboardType::kom,
                'segment_time' => 87,
            ])
            ->add('segment_leaderboard_entry', [
                'message_index' => 1,
                'type' => LeaderboardType::qom,
                'segment_time' => 165,
            ])
            ->add('segment_lap', [
                'uuid' => 'lap' . $segment->id,
                'total_distance' => $segment->distance / 1000,
                'total_ascent' => !empty($segment->total_elevation_gain) ? intval($segment->total_elevation_gain) : 0,
                'swc_lat' => GpxTools::convertCoordinates($borderPoints['SW'][0]),
                'swc_long' => GpxTools::convertCoordinates($borderPoints['SW'][1]),
                'nec_lat' => GpxTools::convertCoordinates($borderPoints['NE'][0]),
                'nec_long' => GpxTools::convertCoordinates($borderPoints['NE'][1]),
                'message_index' => 1,
                'start_position_lat' => GpxTools::convertCoordinates($segment->getStartLatLng()[0]),
                'start_position_long' => GpxTools::convertCoordinates($segment->getStartLatLng()[1]),
                'end_position_lat' => GpxTools::convertCoordinates($segment->getEndLatLng()[0]),
                'end_position_long' => GpxTools::convertCoordinates($segment->getEndLatLng()[1]),
            ]);

        $index = 0;
        $distance = 0;
        foreach ($points as $point) {
            if ($index !== 0) {
                $distance += GpxTools::getDistanceBetweenPoints(
                    $points[$index-1][0],
                    $points[$index-1][1],
                    $point[0],
                    $point[1]
                );
            }

            if ($index === count($points) - 1) {
                $distance = $segment->distance;
            }

            $data->add('segment_point', [
                'position_lat' => GpxTools::convertCoordinates($point[0]),
                'position_long' => GpxTools::convertCoordinates($point[1]),
                'altitude' => 1, // TODO: Получить высоту точки
                'distance' => $distance,
                'message_index' => $index,
                'leader_time' => $index * 6, // TODO: Получить время лидера
            ]);
            $index++;
        }

        $fitWriter = new Writer(false);
        $filepath = $fitWriter->writeData($data, Storage::path('temp/'. $segment->id .'.fit'));

        $this->segments = Segment::select(['id', 'name', 'distance', 'total_elevation_gain', 'polyline', 'start_latlng'])
            ->where('name', '!=', null)
            ->where('private', '!=', 1)
            ->paginate(11);

        $sanitized = preg_replace('/[^\w\-\._]/u','', $segment->name);
        return Storage::download(
            'temp/'. $segment->id .'.fit',
            html_entity_decode($sanitized).'.fit',
        );

        //unlink($filepath);
    }
}
