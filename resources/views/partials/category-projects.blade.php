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
        /* Four-video row layout and centered cards */
        .projects-by-category .projects-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));justify-content:center;justify-items:stretch;gap:18px}
        .projects-by-category .project-card{background:transparent;padding:0;border-radius:12px;color:#fff;width:100%;max-width:none}
        .projects-by-category .project-card:only-child{max-width:340px}
        .projects-by-category .project-thumb{position:relative;overflow:hidden;border-radius:14px}
        .projects-by-category .project-thumb,.projects-by-category .project-thumb-fallback{aspect-ratio:16/9;width:100%;display:block;background-size:cover;background-position:center}
        .projects-by-category .project-thumb video,
        .projects-by-category .project-video,
        .projects-by-category .category-video{width:100% !important;height:100% !important;max-width:none !important;object-fit:cover !important;display:block !important;position:absolute;inset:0;z-index:1}
        .projects-by-category .expand-btn,
        .projects-by-category .expand-toggle,
        .projects-by-category .mute-toggle {
            position:absolute;
            z-index:3;
            width:30px;
            height:30px;
            padding:0;
            border-radius:50%;
            border:1px solid rgba(255,255,255,0.4);
            background:rgba(0,0,0,0.55);
            color:#fff;
            font-size:14px;
            line-height:28px;
            text-align:center;
            cursor:pointer;
            opacity:0;
            pointer-events:none;
            transition:background .2s ease,transform .2s ease,opacity .3s ease;
        }
        .projects-by-category .expand-btn,
        .projects-by-category .expand-toggle {
            right:8px;
            top:8px;
        }
        .projects-by-category .mute-toggle {
            right:8px;
            bottom:8px;
        }
        .projects-by-category .project-thumb:hover .expand-btn,
        .projects-by-category .project-thumb:hover .expand-toggle,
        .projects-by-category .project-thumb:hover .mute-toggle {
            opacity:1;
            pointer-events:auto;
        }
        .projects-by-category .expand-btn:hover,
        .projects-by-category .expand-toggle:hover,
        .projects-by-category .mute-toggle:hover {
            background:rgba(0,0,0,0.75);
            transform:scale(1.08);
        }
        .projects-by-category .audio-indicator{position:absolute;left:8px;top:8px;background:rgba(0,0,0,0.6);color:#fff;padding:5px 10px;border-radius:999px;font-size:11px;z-index:2}
        .projects-by-category h3{margin:10px 6px 4px;font-size:1rem}
        .projects-by-category p.muted{margin:0 6px 8px;color:#cfcfcf;font-size:0.90rem}
        @media (max-width:1200px){.projects-by-category .projects-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media (max-width:900px){.projects-by-category .projects-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media (max-width:600px){.projects-by-category .projects-grid{grid-template-columns:repeat(1,minmax(0,1fr))}}
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
                            <video class="category-video" poster="{{ $project->poster_url }}" autoplay muted loop playsinline preload="metadata" src="{{ preg_match('/^\/\//', $project->video_url) ? 'https:'.$project->video_url : $project->video_url }}" data-src="{{ preg_match('/^\/\//', $project->video_url) ? 'https:'.$project->video_url : $project->video_url }}"></video>
                            <div class="category-overlay"></div>
                            <button type="button" class="expand-btn expand-toggle" aria-label="Expand video">⛶</button>
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
        @if ((file_exists(public_path('build/manifest.json')) && filesize(public_path('build/manifest.json')) > 0) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js'])
        @else
            <script type="module" src="{{ asset('js/app.js') }}"></script>
        @endif
    @endonce
@endif