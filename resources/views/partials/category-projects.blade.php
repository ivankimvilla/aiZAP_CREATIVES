@php
    use App\Models\Project;

    $categoryKey = $category ?? null;
    $categoryLabel = $label ?? null;

    $projectsForCategory = [];
    if ($categoryKey) {
        $all = Project::orderBy('created_at', 'desc')->get();

        $normalize = function ($val) {
            $s = is_string($val) ? $val : '';
            $s = trim(strtolower($s));
            $s = preg_replace('/[^a-z0-9\s-]+/', '', $s);
            $s = preg_replace('/\s+/', '-', $s);
            return $s;
        };

        $target = $normalize($categoryKey);

        foreach ($all as $p) {
            $cats = (array) ($p->categories ?? []);
            foreach ($cats as $c) {
                if ($normalize($c) === $target) {
                    if ($p->video_url) {
                        $projectsForCategory[] = $p;
                    }
                    break;
                }
            }
        }
    }

    $projectsForCategory = array_slice($projectsForCategory, 0, 12);
@endphp

@if(!empty($projectsForCategory))
    <style>
        .projects-by-category{margin:28px 0;padding:0 12px}
        .projects-by-category .section-title{color:#fff;background:#052a3a;padding:6px 12px;border-radius:6px;display:inline-block;margin-bottom:12px}
        /* Fixed 6-up grid for video rows */
        .projects-by-category .projects-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:18px}
        .projects-by-category .project-card{background:transparent;padding:0;border-radius:12px;color:#fff}
        .projects-by-category .project-thumb{position:relative;overflow:hidden;border-radius:14px;border:6px solid rgba(255,255,255,0.06);box-shadow:0 6px 24px rgba(0,0,0,0.6)}
        .projects-by-category .project-thumb,.projects-by-category .project-thumb-fallback{aspect-ratio:16/9;width:100%;display:block;background-size:cover;background-position:center}
        .projects-by-category .project-video{width:100%;height:100%;object-fit:cover;display:block}
        .projects-by-category .mute-toggle{position:absolute;right:8px;bottom:8px;background:rgba(0,0,0,0.6);color:#fff;border:none;padding:6px 8px;border-radius:8px;cursor:pointer;z-index:2}
        .projects-by-category .audio-indicator{position:absolute;left:8px;top:8px;background:rgba(0,0,0,0.6);color:#fff;padding:5px 10px;border-radius:999px;font-size:11px;z-index:2}
        .projects-by-category h3{margin:10px 6px 4px;font-size:1rem}
        .projects-by-category p.muted{margin:0 6px 8px;color:#cfcfcf;font-size:0.90rem}
        @media (max-width:1200px){.projects-by-category .projects-grid{grid-template-columns:repeat(4,1fr)}}
        @media (max-width:900px){.projects-by-category .projects-grid{grid-template-columns:repeat(3,1fr)}}
        @media (max-width:600px){.projects-by-category .projects-grid{grid-template-columns:repeat(1,1fr)}}
        .category-cta{text-align:center;margin-top:18px}
        .category-cta .btn{display:inline-flex;width:auto;min-width:0;padding:10px 22px}
    </style>

    <section class="projects-section projects-by-category">
        <div class="projects-grid">
            @foreach($projectsForCategory as $project)
                @php
                    $categoryLabel = collect($project->categories ?? [])
                        ->filter()
                        ->map(fn($cat) => ucwords(str_replace('-', ' ', $cat)))
                        ->first() ?: 'Video';
                @endphp
                <article class="project-card{{ $project->video_url ? ' has-video' : '' }}">
                    <div class="project-thumb">
                        @if ($project->video_url)
                            <video class="category-video" poster="{{ $project->poster_url }}" autoplay muted loop playsinline preload="metadata" src="{{ $project->video_url }}"></video>
                            <div class="category-overlay"></div>
                            <button type="button" class="expand-btn" aria-label="Expand video">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path d="M3 9V3h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 15v6h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 3l-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M3 21l7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <div class="category-label">{{ $categoryLabel }}</div>
                            <button type="button" class="mute-toggle" aria-label="Unmute">🔇</button>
                        @else
                            <div class="project-thumb-fallback" style="background-image:url('{{ $project->image_url }}')"></div>
                        @endif
                    </div>
                    @unless($project->video_url)
                        <h3>{{ $project->title }}</h3>
                        <p class="muted">{{ $project->subtitle }}</p>
                    @endunless
                </article>
            @endforeach
        </div>
    </section>
    <div class="category-cta">
        <a href="{{ url('/portfolio') }}" class="btn btn-primary">View Our Work</a>
    </div>

    @once
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js'])
        @else
            <script type="module" src="{{ asset('js/app.js') }}"></script>
        @endif
    @endonce
@endif
