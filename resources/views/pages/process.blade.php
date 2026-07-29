<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Process | Aizap Creatives Studios</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if ((file_exists(public_path('build/manifest.json')) && filesize(public_path('build/manifest.json')) > 0) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/css/process.css', 'resources/js/pages/process.js'])
            @else
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/process.css') }}" />
        @endif
    </head>
    <body class="home-page-page process-page antialiased">
        <div class="background-glow">
            <div class="container">
                @include('partials.header')

                <main class="page-main">
                    <section class="hero-section">
                        <div class="hero-copy">
                            <p class="eyebrow">Our Process</p>
                            <h1 class="hero-title">Our Process.<br /><span class="gold">Simple. Clear.</span><br />Powerful.</h1>
                            <p class="hero-sub">Our proven process ensures every project is strategic, creative, and delivered with precision from concept to final cut.</p>
                        </div>

                        <div class="hero-panels">
                            <div class="hero-panel-media" style="background-image:url('{{ asset('home-bg.png') }}')"></div>
                        </div>
                    </section>

                    <section class="timeline-section">
                        <p class="section-eyebrow">How We Work</p>
                        <h2 class="section-title">A Clear Process. Exceptional Results.</h2>
                        <p class="section-copy">From your idea to a powerful final video — we handle everything.</p>

                        @php
                            $processSteps = [
                                [
                                    'icon' => 'phone',
                                    'number' => '01',
                                    'title' => 'Discovery Call',
                                    'image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=500&q=80',
                                    'text' => 'We get to know your goals, audience, and vision.',
                                    'checklist' => ['Project brief', 'Goal alignment', 'Scope & timeline'],
                                ],
                                [
                                    'icon' => 'idea',
                                    'number' => '02',
                                    'title' => 'Strategy & Concept',
                                    'image' => 'https://images.unsplash.com/photo-1558655146-9f40138edfeb?auto=format&fit=crop&w=500&q=80',
                                    'text' => 'We craft the right strategy and creative concept.',
                                    'checklist' => ['Market & audience insight', 'Creative direction', 'Concept approval'],
                                ],
                                [
                                    'icon' => 'doc',
                                    'number' => '03',
                                    'title' => 'Script & Storyboard',
                                    'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=500&q=80',
                                    'text' => 'We write the script and visualize the entire story.',
                                    'checklist' => ['Scriptwriting', 'Storyboard & shot list', 'Client approval'],
                                ],
                                [
                                    'icon' => 'ai',
                                    'number' => '04',
                                    'title' => 'AI Production',
                                    'image' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=500&q=80',
                                    'text' => 'Our AI tools bring your story to life.',
                                    'checklist' => ['AI image & video generation', 'Voiceover & SFX', 'Scene composition'],
                                ],
                                [
                                    'icon' => 'edit',
                                    'number' => '05',
                                    'title' => 'Editing & Revisions',
                                    'image' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?auto=format&fit=crop&w=500&q=80',
                                    'text' => 'We polish every frame until it\'s perfect.',
                                    'checklist' => ['Editing & color grading', 'Sound design & music', 'Revisions included'],
                                ],
                                [
                                    'icon' => 'send',
                                    'number' => '06',
                                    'title' => 'Final Delivery',
                                    'image' => 'https://images.unsplash.com/photo-1533750349088-cd871a92f312?auto=format&fit=crop&w=500&q=80',
                                    'text' => 'We deliver a high-impact final video, ready to perform.',
                                    'checklist' => ['Final quality check', 'Formats for all platforms', 'On-time delivery'],
                                ],
                            ];
                        @endphp

                        <div class="timeline-row">
                            @foreach ($processSteps as $step)
                                <div class="timeline-icon">
                                    @switch($step['icon'])
                                        @case('phone')
                                            <svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.5.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.4c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.5.1.4 0 .8-.2 1.1L6.6 10.8z" /></svg>
                                            @break
                                        @case('idea')
                                            <svg viewBox="0 0 24 24"><path d="M9 18h6M10 22h4M12 2a6 6 0 0 0-3.5 10.9c.6.4 1 1.1 1 1.9V16h5v-1.2c0-.8.4-1.5 1-1.9A6 6 0 0 0 12 2z" /></svg>
                                            @break
                                        @case('doc')
                                            <svg viewBox="0 0 24 24"><path d="M7 2h7l4 4v16H7V2z" /><path d="M14 2v4h4M9 12h6M9 16h6M9 8h2" /></svg>
                                            @break
                                        @case('ai')
                                            <svg viewBox="0 0 24 24"><rect x="7" y="7" width="10" height="10" rx="1.5" /><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.5 4.5l2 2M17.5 17.5l2 2M4.5 19.5l2-2M17.5 6.5l2-2" /></svg>
                                            @break
                                        @case('edit')
                                            <svg viewBox="0 0 24 24"><circle cx="6" cy="6" r="2.5" /><circle cx="6" cy="18" r="2.5" /><path d="M20 4L8.5 12 20 20M8.5 12l3.5 0" /></svg>
                                            @break
                                        @case('send')
                                            <svg viewBox="0 0 24 24"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" /></svg>
                                            @break
                                    @endswitch
                                </div>
                            @endforeach
                        </div>

                        <div class="timeline-cards">
                            @foreach ($processSteps as $step)
                                <div class="step-card">
                                    <p class="step-card-number">{{ $step['number'] }}</p>
                                    <h3 class="step-card-title">{{ $step['title'] }}</h3>
                                    <div class="step-card-image" style="background-image:url('{{ $step['image'] }}')"></div>
                                    <p class="step-card-text">{{ $step['text'] }}</p>
                                    <ul class="step-checklist">
                                        @foreach ($step['checklist'] as $item)
                                            <li>
                                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" /><path d="M8 12.5l2.5 2.5L16 9.5" /></svg>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="cta-bar">
                        <div class="cta-bar-left">
                            <svg viewBox="0 0 24 24" class="cta-bar-icon"><path d="M12 2c2.5 2 4 5 4 9 0 2-.6 3.6-1.2 4.8L12 19l-2.8-2.7C8.6 15.1 8 13.5 8 11.5c0-4 1.5-7 4-9z" /><circle cx="12" cy="10" r="1.6" /><path d="M9 17l-2 3M15 17l2 3" /></svg>
                            <div>
                                <h3>Ready to bring your story to life?</h3>
                                <p>Let's create something extraordinary together.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary contact-toggle">
                            Let's Work Together
                            <svg viewBox="0 0 24 24" class="btn-arrow"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </button>
                    </section>
                </main>

                @include('partials.footer')
            </div>
        </div>
    </body>
</html>