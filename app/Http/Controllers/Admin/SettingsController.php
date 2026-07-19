<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SettingsController extends Controller
{
    public function editProjectCategories()
    {
        $categories = [
            'ai-commercial-ads' => 'AI Commercial Ads',
            'ai-product-ads' => 'AI Product Ads',
            'ai-storytelling-drama' => 'AI Storytelling / Drama',
            'ai-short-films' => 'AI Short Films',
            'ai-movie-trailers' => 'AI Movie Trailers',
            'ai-brand-campaigns' => 'AI Brand Campaigns',
            'social-media-content' => 'Social Media Content',
            'ugc-style-ai-videos' => 'UGC-style AI Videos',
            'explainer-videos' => 'Explainer Videos',
            'motion-graphics' => 'Motion Graphics',
            'creative-concepts' => 'Creative Concepts',
            'marketing-ideas' => 'Marketing Ideas',
            'scriptwriting' => 'Scriptwriting',
            'storyboarding' => 'Storyboarding',
            'video-editing' => 'Video Editing',
            'content-strategy' => 'Content Strategy',
        ];

        $selected = SiteSetting::get('visible_project_categories', []);

        return view('admin.settings.project-categories', compact('categories', 'selected'));
    }

    public function updateProjectCategories(Request $request)
    {
        $data = $request->validate([
            'categories' => 'nullable|array',
            'categories.*' => 'string',
        ]);

        $categories = $data['categories'] ?? [];
        SiteSetting::set('visible_project_categories', $categories);

        return redirect()->back()->with('success', 'Visible project categories updated.');
    }
}
