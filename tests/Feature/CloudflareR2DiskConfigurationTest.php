<?php

it('registers a Cloudflare R2-compatible filesystem disk alias', function () {
    expect(config('filesystems.disks.r2.driver'))->toBe('s3');
    expect(config('filesystems.disks.r2.visibility'))->toBe('public');
    expect(config('filesystems.disks.r2.use_path_style_endpoint'))->toBeTrue();
});
