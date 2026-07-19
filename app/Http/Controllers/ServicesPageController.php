<?php

namespace App\Http\Controllers;

use App\Models\SectionVideo;

class ServicesPageController extends Controller
{
    public function index()
    {
        $sectionVideos = SectionVideo::whereIn('key', ['services-what-we-do', 'services-process'])
            ->get()
            ->keyBy('key');

        return view('pages.services', compact('sectionVideos'));
    }
}
