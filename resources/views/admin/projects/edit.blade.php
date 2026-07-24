@extends('admin.layout')

@section('title', 'Edit Project')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/create-video.css') }}" />

<div class="cp-page">

    <div class="cp-back-row">
        <a href="{{ route('admin.dashboard') }}" class="cp-back-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back
        </a>
    </div>

    <section class="cp-hero">
        <h1 class="hero-title">Edit <span class="gold">video</span></h1>
        <p class="hero-sub">Update video details, upload a new video, or toggle featured status.</p>
    </section>

    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">&#10003;</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <span class="alert-icon">&#8505;</span>
            {{ session('info') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <span class="alert-icon">!</span>
            Please fix the errors below and try again.
        </div>
    @endif

    <div class="cp-panel">
        <form action="{{ route('admin.projects.update', $project) }}" method="post" enctype="multipart/form-data" class="cp-form">
            @csrf
            @method('PUT')

            <div class="cp-row">
                <div class="cp-field">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}" required />
                </div>

                <div class="cp-field">
                    <label for="subtitle">Subtitle</label>
                    <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle', $project->subtitle) }}" />
                </div>

                <div class="cp-field">
                    <label for="video">Video</label>
                    <div class="cp-file-input">
                        <input type="file" id="video" name="video" accept="video/*" />
                        <label for="video" class="cp-file-label" id="cp-file-label-text">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 16V4M12 4l-4 4M12 4l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4 16v3a2 2 0 002 2h12a2 2 0 002-2v-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            @if($project->video_url)
                                Replace current video
                            @else
                                Upload video
                            @endif
                        </label>
                    </div>
                    @if($project->video_url)
                        <p class="cp-current-file">
                            Current: <a href="{{ $project->video_url }}" target="_blank" rel="noopener">View video</a>
                        </p>
                    @endif
                </div>
            </div>

            <fieldset class="cp-categories">
                <legend>Categories</legend>
                @php
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
                    $selected = old('categories', $project->categories ?? []);
                @endphp
                <div class="cp-cat-grid">
                    @foreach($categories as $key => $label)
                        <label class="cp-checkbox">
                            <input type="checkbox" name="categories[]" value="{{ $key }}" {{ in_array($key, $selected) ? 'checked' : '' }} />
                            <span class="cp-checkbox-box">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="cp-checkbox-text">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <div class="cp-bottom-row">
                <label class="cp-checkbox cp-featured">
                    <input type="checkbox" name="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }} />
                    <span class="cp-checkbox-box">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="cp-checkbox-text">
                        <strong>Featured Project</strong>
                        <small>Shown at the top of the public portfolio</small>
                    </span>
                </label>

                <button type="submit" class="btn-primary">Update Video</button>
            </div>
        </form>
    </div>
</div>

@endsection