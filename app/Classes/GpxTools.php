<?php

namespace App\Classes;

use adriangibbons\phpFITFileAnalysis;
use Gpx2Png\Gpx2Png;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GpxTools {
    public static function convertFitToGpx(phpFITFileAnalysis $fit, int $userId, string $filename): void
    {
        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="no"?>
            <gpx xmlns="http://www.topografix.com/GPX/1/1" version="1.1"></gpx>');
        $trkNode = $rootNode->addChild('trk');
        $trksegNode = $trkNode->addChild('trkseg');

        try {
            foreach ($fit->data_mesgs['record']['timestamp'] as $timestamp) {
                $trkptNode = $trksegNode->addChild('trkpt');
                $trkptNode->addAttribute('lat', $fit->data_mesgs['record']['position_lat'][$timestamp]);
                $trkptNode->addAttribute('lon', $fit->data_mesgs['record']['position_long'][$timestamp]);
                if (!empty($fit->data_mesgs['record']['altitude'][$timestamp])) {
                    $trkptNode->addChild('ele', $fit->data_mesgs['record']['altitude'][$timestamp]);
                }
                $trkptNode->addChild('time', date('Y-m-d\TH:i:s.000\Z', $timestamp));
            }

            Storage::write('public/activities/'. $userId .'/'. $filename .'.gpx', $rootNode->asXML());
        } catch (\Exception $e) {
            report($e);
        }
    }

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
        return Storage::put('public/activities/'. $userId .'/'. $file . '.png', $image);
    }

    public static function geocode($latitude, $longitude): ?array
    {
        try {
            $geo = app('geocoder')
                ->using('nominatim')
                ->reverse($latitude, $longitude)
                ->get()
                ->first();
        } catch (Throwable $e) {
            return null;
        }

        return [
            'country' => $geo->getCountry()->getCode(),
            'locality' => $geo->getLocality()
        ];
    }
}
