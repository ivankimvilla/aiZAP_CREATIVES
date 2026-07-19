<?php

namespace App\Http\Controllers;

use App\Models\SectionVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SectionVideoController extends Controller
{
    public function index()
    {
        $sectionVideos = SectionVideo::whereIn('key', ['strategy-concept', 'why-clients-choose-us', 'services-what-we-do', 'services-process', 'portfolio-collage'])
            ->get()
            ->keyBy('key');

        return view('admin.section-videos.index', compact('sectionVideos'));
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:20480',
            'poster' => 'nullable|image|max:2048',
        ]);

        $sectionVideo = SectionVideo::firstOrCreate(['key' => $key]);

        if ($request->hasFile('video')) {
            if ($sectionVideo->video_path) {
                Storage::disk('public')->delete($sectionVideo->video_path);
            }
            $sectionVideo->video_path = $request->file('video')->store('section-videos', 'public');
        }

        if ($request->hasFile('poster')) {
            if ($sectionVideo->poster_path) {
                Storage::disk('public')->delete($sectionVideo->poster_path);
            }
            $sectionVideo->poster_path = $request->file('poster')->store('section-videos/posters', 'public');
        }

        $sectionVideo->save();

        return back()->with('success', ucfirst(str_replace('-', ' ', $key)) . ' video saved successfully.');
    }
}
