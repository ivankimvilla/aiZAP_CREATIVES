<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::where('featured', true)->get();
        $projects = Project::orderBy('created_at', 'desc')->get();
        $sectionVideos = \App\Models\SectionVideo::whereIn('key', ['portfolio-collage'])
            ->get()
            ->keyBy('key');

        // Load enabled categories from settings (if any). If set, we'll pass to the view
        // so only projects whose categories intersect the enabled set will be used for
        // project video display in the collage.
        $visibleCategories = \App\Models\SiteSetting::get('visible_project_categories', null);

        return view('pages.portfolio', compact('featuredProjects', 'projects', 'sectionVideos', 'visibleCategories'));
    }

    public function adminIndex()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();

        // Additional counts for admin dashboard overview
        $projectCount = Project::count();
        $bookingCount = \App\Models\Booking::count();
        $contactCount = \App\Models\ContactMessage::count();

        return view('admin.projects.index', compact('projects', 'projectCount', 'bookingCount', 'contactCount'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:20480',
            'categories' => 'nullable|array',
            'categories.*' => 'string',
            'featured' => 'nullable|boolean',
        ]);

        $project = new Project();
        $project->title = $request->title;
        $project->subtitle = $request->subtitle;
        $project->featured = $request->boolean('featured');

        if ($request->hasFile('image')) {
            $project->image = $request->file('image')->store('projects/images', 'public');
        }

        if ($request->hasFile('video')) {
            $project->video_path = $request->file('video')->store('projects/videos', 'public');
        }

        // categories as array (saved to json column via $casts)
        $project->categories = $request->input('categories', []);

        $project->save();

        return redirect()->route('admin.dashboard')->with('success', 'Project saved successfully.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska|max:20480',
            'categories' => 'nullable|array',
            'categories.*' => 'string',
            'featured' => 'nullable|boolean',
        ]);

        $project->title = $request->title;
        $project->subtitle = $request->subtitle;
        $project->featured = $request->boolean('featured');

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $project->image = $request->file('image')->store('projects/images', 'public');
        }

        if ($request->hasFile('video')) {
            if ($project->video_path) {
                Storage::disk('public')->delete($project->video_path);
            }
            $project->video_path = $request->file('video')->store('projects/videos', 'public');
        }

        $project->categories = $request->input('categories', []);

        $project->save();

        return redirect()->route('admin.dashboard')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        if ($project->video_path) {
            Storage::disk('public')->delete($project->video_path);
        }

        $project->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Project removed successfully.');
    }
}
