<?php

namespace App\Classes;

use adriangibbons\phpFITFileAnalysis;
use Gpx2Png\Gpx2Png;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GpxTools
{
    public static function convertFitToGpx(phpFITFileAnalysis $fit, int $userId, string $filename): bool
    {
        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="no"?>
            <gpx xmlns="http://www.topografix.com/GPX/1/1" version="1.1"></gpx>');
        $trkNode = $rootNode->addChild('trk');
        $trksegNode = $trkNode->addChild('trkseg');

        try {
            foreach ($fit->data_mesgs['record']['timestamp'] as $timestamp) {
                if (array_key_exists($timestamp, $fit->data_mesgs['record']['position_lat'])) {
                    $trkptNode = $trksegNode->addChild('trkpt');
                    $trkptNode->addAttribute('lat', $fit->data_mesgs['record']['position_lat'][$timestamp]);
                    $trkptNode->addAttribute('lon', $fit->data_mesgs['record']['position_long'][$timestamp]);
                    if (isset($fit->data_mesgs['record']['altitude'][$timestamp])) {
                        $trkptNode->addChild('ele', $fit->data_mesgs['record']['altitude'][$timestamp]);
                    }
                    $trkptNode->addChild('time', date('Y-m-d\TH:i:s.000\Z', $timestamp));
                }
            }
        } catch (\Exception $e) {
            report($e);
        } finally {
            Storage::write('public/activities/' . $userId . '/' . $filename . '.gpx', $rootNode->asXML());
            return true;
        }
    }

    public static function generateImageFromGPX(string $file, int $userId, bool $tempPath = false): bool|string
    {
        if ($tempPath) {
            $fullFilePath = \Storage::path('temp/' . $file);
        } else {
            $fullFilePath = \Storage::path('public/activities/' . $userId . '/' . $file);
        }

        $gpx2png = new Gpx2Png();
        $gpx2png->imageParams->max_width = 1280;
        $gpx2png->imageParams->max_height = 1280;
        $gpx2png->imageParams->padding = 20;
        $gpx2png->drawParams->autoCropToBounds = 0;
        $gpx2png->drawParams->track->width = 12;
        $gpx2png->drawParams->track->color = '#ff6a00';
        $gpx2png->drawParams->track->distanceLabelsFrequency = 0;
        $gpx2png->loadFile($fullFilePath);

        $res = $gpx2png->generateImage();
        $image = $res->data();
        return Storage::put('public/activities/' . $userId . '/' . $file . '.png', $image);
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

    /**
     * Calculates the distance between two points, given their
     * latitude and longitude, and returns an array of values
     * of the most common distance units
     *
     * @param  {coord} $lat1 Latitude of the first point
     * @param  {coord} $lon1 Longitude of the first point
     * @param  {coord} $lat2 Latitude of the second point
     * @param  {coord} $lon2 Longitude of the second point
     * @return float {array}       Array of values in many distance units
     */
    public static function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2): float
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return $meters;
    }

    // Функция для преобразования координат в значение Garmin FIT
    public static function convertCoordinates($coordinate): int
    {
        $scalingFactor = (2 ** 31) / 180.0; // Коэффициент масштабирования
        return (int)($coordinate * $scalingFactor);
    }

    public static function stravaTimeToSeconds(string $time): float|int
    {
        $parts = explode(':', $time);
        $seconds = 0;

        if (count($parts) == 2) {
            // Если время в формате "минуты:секунды"
            $minutes = intval($parts[0]);
            $seconds = intval($parts[1]);
            $seconds += $minutes * 60;
        } elseif (count($parts) == 3) {
            // Если время в формате "часы:минуты:секунды"
            $hours = intval($parts[0]);
            $minutes = intval($parts[1]);
            $seconds = intval($parts[2]);
            $seconds += $hours * 3600 + $minutes * 60;
        }

        return $seconds;
    }
}
