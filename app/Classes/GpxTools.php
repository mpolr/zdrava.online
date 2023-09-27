<?php

namespace App\Classes;

use Gpx2Png\Gpx2Png;
use Illuminate\Support\Facades\Storage;

class GpxTools {
    public static function generateImageFromGPX(string $file, int $userId): bool|string
    {
        $fullFilePath = \Storage::path('public/activities/'. $userId .'/'. $file);
        $gpx2png = new Gpx2Png();
        $gpx2png->imageParams->max_width = 1280;
        $gpx2png->imageParams->max_height = 1280;
        $gpx2png->imageParams->padding = 20;
        $gpx2png->drawParams->autoCropToBounds = 0;
        $gpx2png->drawParams->track->distanceLabelsFrequency = 0;
        $gpx2png->loadFile($fullFilePath);

        $res = $gpx2png->generateImage();
        $image = $res->data();
        return Storage::put($file.'.png', $image, 'public');
    }
}
