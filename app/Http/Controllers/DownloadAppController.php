<?php

namespace App\Http\Controllers;

use App\Models\AndroidApp;

class DownloadAppController extends Controller
{
    public function index()
    {
        $appVersions = AndroidApp::all()->sortDesc();
        return view('app.index', ['versions' => $appVersions]);
    }
}
