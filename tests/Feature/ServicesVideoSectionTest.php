<?php

use App\Models\SectionVideo;

it('renders the services intro video section from uploaded content', function () {
    SectionVideo::create([
        'key' => 'services-what-we-do',
        'video_path' => 'section-videos/demo.mp4',
        'poster_path' => 'section-videos/posters/demo.jpg',
    ]);

    $response = $this->get('/services');

    $response->assertStatus(200);
    $response->assertSee('services-feature-video', false);
    $response->assertSee('services-what-we-do-video', false);
    $response->assertSee(asset('storage/section-videos/demo.mp4'), false);
});
