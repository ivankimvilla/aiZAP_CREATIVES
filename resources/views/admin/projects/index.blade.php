@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/admin-dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/bulk-actions.css') }}" />
@endpush

@section('content')
<div class="admin-dashboard">

    <div class="admin-topbar">
        <div class="admin-stats">
            <div class="admin-stat">
                <div class="admin-stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M15 10l5-3v10l-5-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="2" y="6" width="13" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <div class="admin-stat-body">
                    <div class="admin-stat-label">Videos</div>
                    <div class="admin-stat-value">{{ $projectCount ?? $projects->count() }}</div>
                </div>
            </div>

            <div class="admin-stat">
                <div class="admin-stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="17" rx="2" stroke="currentColor" stroke-width="2"/>
                        <path d="M3 9h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="admin-stat-body">
                    <div class="admin-stat-label">Bookings</div>
                    <div class="admin-stat-value">{{ $bookingCount ?? '—' }}</div>
                </div>
            </div>

            <div class="admin-stat">
                <div class="admin-stat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="admin-stat-body">
                    <div class="admin-stat-label">Messages</div>
                    <div class="admin-stat-value">{{ $contactCount ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">&#10003;</span>
            {{ session('success') }}
        </div>
    @endif

    <section class="admin-projects-section">
        <div class="admin-section-header">
            <h2>All Videos</h2>
        </div>

        <section class="bulk-toolbar" data-bulk-item-selector=".admin-project-card" data-empty-state-selector=".admin-empty-state">
            <label class="bulk-select">
                <input type="checkbox" class="bulk-select-all" aria-label="Select all videos" />
                Select all
            </label>
            <div class="bulk-actions">
                <span class="bulk-selected-count">0 selected</span>
                <button type="button" class="btn-delete-selected" data-delete-url="{{ route('admin.projects.bulk_destroy', [], false) }}" disabled>Delete selected</button>
            </div>
        </section>

        <div class="admin-projects-grid">
            @forelse($projects as $project)
                <article class="admin-project-card">
                    <div class="admin-card-checkbox">
                        <input type="checkbox" class="bulk-item-checkbox" data-item-id="{{ $project->id }}" aria-label="Select video project #{{ $project->id }}" />
                    </div>
                    <div class="project-thumb admin-project-thumb">

                        {{-- Category pill + featured star share one row so they can never overlap --}}
                        @if((!empty($project->categories) && is_array($project->categories) && count($project->categories)) || $project->featured)
                            <div class="admin-badge-row">
                                @if(!empty($project->categories) && is_array($project->categories) && count($project->categories))
                                    <span class="admin-category-pill">{{ ucwords(str_replace('-', ' ', $project->categories[0])) }}</span>
                                @endif
                                @if($project->featured)
                                    <span class="admin-featured-star" title="Featured" aria-label="Featured">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M12 2l2.9 6.26L21.8 9l-5 4.87L18.2 21 12 17.27 5.8 21l1.4-7.13-5-4.87 6.9-.74L12 2z"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if($project->video_url)
                            <video class="project-video admin-project-video" autoplay muted playsinline loop preload="metadata" poster="{{ $project->image_url }}">
                                <source src="{{ $project->video_url }}" type="video/mp4" />
                            </video>

                            <button type="button" class="mute-toggle admin-mute-toggle admin-mute-fixed" aria-label="Unmute">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M11 5L6 9H2v6h4l5 4V5z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <line x1="23" y1="9" x2="17" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="17" y1="9" x2="23" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        @else
                            <img
                                class="admin-project-thumb-fallback"
                                src="{{ $project->image_url ?: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=700&q=80' }}"
                                alt="{{ $project->title }} thumbnail"
                            />
                        @endif
                    </div>

                    <div class="admin-project-body {{ $project->video_url ? 'admin-video-only-body' : '' }}">
                        @unless($project->video_url)
                            <h3>{{ $project->title }}</h3>
                            <p>{{ $project->subtitle }}</p>
                        @endunless

                        <div class="admin-project-actions">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-outline btn-sm admin-btn-edit">Edit</a>
                            <form action="{{ route('admin.projects.destroy', $project) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm admin-btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="admin-empty-state">
                    <p>No videos yet.</p>
                    <span>Uploaded project videos will appear here.</span>
                </div>
            @endforelse
        </div>
    </section>
</div>

@push('scripts')
    <script type="module">
        import adminBulkDelete from '/js/admin/bulk-delete.js';
        try { adminBulkDelete(); } catch (e) { console.error('adminBulkDelete init failed', e); }
    </script>
@endpush

@endsection