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

        $disk = config('filesystems.default', 'public');

        if ($request->hasFile('image')) {
            $project->image = $request->file('image')->store('projects/images', $disk);
        }

        if ($request->hasFile('video')) {
            $project->video_path = $request->file('video')->store('projects/videos', $disk);
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
        $project->categories = $request->input('categories', []);

        $updated = $project->isDirty();

        $disk = config('filesystems.default', 'public');

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk($disk)->delete($project->image);
            }
            $project->image = $request->file('image')->store('projects/images', $disk);
            $updated = true;
        }

        if ($request->hasFile('video')) {
            if ($project->video_path) {
                Storage::disk($disk)->delete($project->video_path);
            }
            $project->video_path = $request->file('video')->store('projects/videos', $disk);
            $updated = true;
        }

        if (!$updated) {
            return redirect()->back()->with('info', 'No changes were made.');
        }

        $project->save();

        return redirect()->route('admin.dashboard')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $disk = config('filesystems.default', 'public');

        if ($project->image) {
            Storage::disk($disk)->delete($project->image);
        }

        if ($project->video_path) {
            Storage::disk($disk)->delete($project->video_path);
        }

        $project->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Project removed successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:projects,id'],
        ]);

        $disk = config('filesystems.default', 'public');
        Project::whereIn('id', $data['ids'])->get()->each(function (Project $project) use ($disk) {
            if ($project->image) {
                Storage::disk($disk)->delete($project->image);
            }
            if ($project->video_path) {
                Storage::disk($disk)->delete($project->video_path);
            }
            $project->delete();
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Selected videos deleted.');
    }
}
