@extends('admin.layout')

@section('title', 'Create Project')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/create-video.css') }}" />
    <main class="cp-page">
        <section class="cp-hero">
            <h1 class="hero-title">Upload a <span class="gold">Featured</span> Project Video</h1>
            <p class="hero-sub">Add a project with video and mark it featured to show it in the featured projects section.</p>
        </section>

        @if(session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">&#10003;</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <span class="alert-icon">!</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <section class="cp-panel">
            <form action="{{ route('admin.projects.store') }}" method="post" enctype="multipart/form-data" class="cp-form">
                @csrf

                <div class="cp-row">
                    <div class="cp-field">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required />
                    </div>

                    <div class="cp-field">
                        <label for="subtitle">Subtitle</label>
                        <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle') }}" />
                    </div>

                    <div class="cp-field cp-field-video">
                        <label>Video</label>
                        <div class="cp-file-input">
                            <input type="file" name="video" accept="video/*" id="video" />
                            <label for="video" class="cp-file-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M23 7l-7 5 7 5V7z"></path>
                                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                </svg>
                                <span>Choose video</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="cp-field">
                    <fieldset class="cp-categories">
                        <legend>Categories</legend>
                        <div class="cp-cat-grid">
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
                            @endphp
                            @foreach($categories as $key => $label)
                                <label class="cp-checkbox">
                                    <input type="checkbox" name="categories[]" value="{{ $key }}" {{ in_array($key, old('categories', [])) ? 'checked' : '' }} />
                                    <span class="cp-checkbox-box">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </span>
                                    <span class="cp-checkbox-text">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                </div>

                <div class="cp-bottom-row">
                    <label class="cp-checkbox cp-featured">
                        <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} />
                        <span class="cp-checkbox-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        <span class="cp-checkbox-text">
                            <strong>Featured Project</strong>
                            <small>Shows in the featured projects section.</small>
                        </span>
                    </label>

                    <button type="submit" class="btn-primary">Save Project</button>
                </div>
            </form>
        </section>
    </main>

@endsection