<?php

namespace App\Http\Controllers;

use App\Models\AndroidApp;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadAppController extends Controller
{
    public function index(): \Illuminate\View\View|View
    {
        $appVersions = AndroidApp::all()->sortDesc();
        return view('app.index', ['versions' => $appVersions]);
    }

    public function mobile(): \Illuminate\View\View|View
    {
        return view('app.mobile');
    }

    public function download(?string $version = null): StreamedResponse
    {
        $app = null;
        if (strpos($version, '.') == 0) {
            abort(404);
        }

        if (!empty($version)) {
            $app = AndroidApp::where([
                'version' => $version
            ])->first();
        }

        if (empty($app)) {
            $app = AndroidApp::latest()->first();
        }

        $app->downloads += 1;
        $app->save();

        return Storage::download(
            'public/android/zdrava-' . $app->version . '.apk',
            null,
            [
                'Content-Type' => 'application/vnd.android.package-archive',
            ]
        );
    }
}
