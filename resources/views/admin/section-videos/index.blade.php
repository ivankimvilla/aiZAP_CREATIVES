@extends('admin.layout')

@section('title', 'Section Videos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contact-dropdown.css') }}" />
       <link rel="stylesheet" href="{{ asset('css/admin/section-video.css') }}" />
@endpush

@section('content')
    <main class="sv-page">
        <section class="sv-hero">
            <h1 class="hero-title">Manage <span class="gold">Section Videos</span></h1>
            <p class="hero-sub">Upload videos for the About page sections and homepage autoplay.</p>
        </section>

        @if(session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">&#10003;</span>
                {{ session('success') }}
            </div>
        @endif

        <section class="sv-grid">
            @foreach (['strategy-concept' => 'Strategy & Concept', 'why-clients-choose-us' => 'Why Clients Choose Us', 'services-what-we-do' => 'Services What We Do', 'services-process' => 'Services Process'] as $key => $label)
                @php $video = $sectionVideos[$key] ?? null; @endphp
                <div class="sv-card">
                    <div class="sv-card-header">
                        <h2>{{ $label }}</h2>
                        <span class="sv-status {{ ($video && $video->video_url) ? 'is-set' : 'is-empty' }}">
                            {{ ($video && $video->video_url) ? 'Video set' : 'No video' }}
                        </span>
                    </div>

                    <form action="{{ route('admin.section-videos.update', $key) }}" method="post" enctype="multipart/form-data" class="sv-form">
                        @csrf

                        <div class="sv-field">
                            <label>Video file</label>
                            <div class="sv-file-input">
                                <input type="file" name="video" accept="video/*" id="video-{{ $key }}" />
                                <label for="video-{{ $key }}" class="sv-file-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M23 7l-7 5 7 5V7z"></path>
                                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                    </svg>
                                    <span>Choose video</span>
                                </label>
                            </div>
                            @if($video && $video->video_url)
                                <a href="{{ $video->video_url }}" target="_blank" class="sv-current-link">
                                    Current video &rarr;
                                </a>
                            @endif
                        </div>

                        <div class="sv-field">
                            <label>Poster image</label>
                            <div class="sv-poster-row">
                                <div class="sv-file-input">
                                    <input type="file" name="poster" accept="image/*" id="poster-{{ $key }}" />
                                    <label for="poster-{{ $key }}" class="sv-file-label">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <path d="M21 15l-5-5L5 21"></path>
                                        </svg>
                                        <span>Choose poster</span>
                                    </label>
                                </div>
                                @if($video && $video->poster_url)
                                    <a href="{{ $video->poster_url }}" target="_blank" class="sv-poster-thumb">
                                        <img src="{{ $video->poster_url }}" alt="{{ $label }} poster" />
                                    </a>
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">Save {{ $label }}</button>
                    </form>
                </div>
            @endforeach
        </section>
    </main>

@endsection