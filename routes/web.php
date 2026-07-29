<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\SectionVideo;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    $featuredProjects = Project::where('featured', true)->get();
    $sectionVideos = SectionVideo::where('key', 'why-clients-choose-us')->get()->keyBy('key');
    return view('home-page', compact('featuredProjects', 'sectionVideos'));
});

// Accept GET requests to the Boost browser-logs endpoint to avoid
// MethodNotAllowed exceptions when the browser probes the route.
Route::get('/_boost/browser-logs', function () {
    return response('', 200);
});

Route::get('/about-us', function () {
    $sectionVideos = SectionVideo::whereIn('key', ['strategy-concept', 'why-clients-choose-us'])->get()->keyBy('key');
    return view('pages.about-us', compact('sectionVideos'));
});
Route::get('/services', [App\Http\Controllers\ServicesPageController::class, 'index']);
// "What We Do" service pages (individual)
Route::view('/what-we-do/ai-commercial-ads', 'what we do.ai-commercial-ads');
Route::view('/what-we-do/ai-product-ads', 'what we do.ai-product-ads');
Route::view('/what-we-do/ai-storytelling-drama', 'what we do.ai-storytelling-drama');
Route::view('/what-we-do/ai-short-films', 'what we do.ai-short-films');
Route::view('/what-we-do/ai-movie-trailers', 'what we do.ai-movie-trailers');
Route::view('/what-we-do/ai-brand-campaigns', 'what we do.ai-brand-campaigns');
Route::view('/what-we-do/social-media-content', 'what we do.social-media-content');
Route::view('/what-we-do/ugc-style-ai-videos', 'what we do.ugc-style-ai-videos');
Route::view('/what-we-do/explainer-videos', 'what we do.explainer-videos');
Route::view('/what-we-do/motion-graphics', 'what we do.motion-graphics');
Route::view('/what-we-do/creative-concepts', 'what we do.creative-concepts');
Route::view('/what-we-do/marketing-ideas', 'what we do.marketing-ideas');
Route::view('/what-we-do/scriptwriting', 'what we do.scriptwriting');
Route::view('/what-we-do/storyboarding', 'what we do.storyboarding');
Route::view('/what-we-do/video-editing', 'what we do.video-editing');
Route::view('/what-we-do/content-strategy', 'what we do.content-strategy');

Route::get('/storage/{path}', function ($path) {
    $disk = Storage::disk('public');
    if (! $disk->exists($path)) {
        abort(404);
    }
    return response()->file($disk->path($path));
})->where('path', '.*');

Route::get('/portfolio', [ProjectController::class, 'index']);
Route::view('/process', 'pages.process');
Route::view('/pricing', 'pages.pricing');
Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');
Route::post('/packages', [App\Http\Controllers\PackageRequestController::class, 'store'])->name('packages.store');

Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/availability', [BookingController::class, 'availability'])->name('bookings.availability');
Route::get('/api/booked-times', [BookingController::class, 'getBookedTimes']);

Route::get('/admin/login', function () {
    return redirect()->route('login');
});
Route::post('/admin/login', function () {
    return redirect()->route('login.submit');
});
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
Route::get('/admin/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [AdminAuthController::class, 'sendResetLinkEmail'])->name('admin.password.email');
Route::get('/admin/reset-password/{token}', [AdminAuthController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('/admin/reset-password', [AdminAuthController::class, 'reset'])->name('admin.password.update');

// Admin registration (create admin account)
Route::get('/admin/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AdminAuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AdminAuthController::class, 'changePassword'])->name('password.change.update');
    Route::post('/account', [AdminAuthController::class, 'updateAccount'])->name('account.update');
    Route::post('/admins/{user}/forgot-password', [AdminAuthController::class, 'sendPasswordResetToAdmin'])->name('admins.forgot_password');
    Route::post('/admins/{user}/reset-password', [AdminAuthController::class, 'resetAdminPassword'])->name('admins.reset_password');
    Route::post('/admins/{user}/change-password', [AdminAuthController::class, 'changeAdminPassword'])->name('admins.change_password');
    Route::delete('/admins/{user}', [AdminAuthController::class, 'destroyAdmin'])->name('admins.destroy');
    Route::post('/admins/check', [AdminAuthController::class, 'checkAdminUnique'])->name('admins.check');

    Route::get('/', [ProjectController::class, 'adminIndex'])->name('dashboard');

    Route::get('/settings/project-categories', [\App\Http\Controllers\Admin\SettingsController::class, 'editProjectCategories'])->name('settings.project_categories');
    Route::post('/settings/project-categories', [\App\Http\Controllers\Admin\SettingsController::class, 'updateProjectCategories'])->name('settings.project_categories.update');

    Route::get('/bookings', [BookingController::class, 'adminIndex'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status.update');
    Route::delete('/bookings/bulk-delete', [BookingController::class, 'bulkDestroy'])->name('bookings.bulk_destroy');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/contacts', [ContactMessageController::class, 'adminIndex'])->name('contacts.index');
    Route::post('/contacts/mark-read', [ContactMessageController::class, 'markAllRead'])->name('contacts.mark_read');
    Route::post('/contacts/{contactMessage}/seen', [ContactMessageController::class, 'markRead'])->name('contacts.mark_read_single');
    Route::post('/contacts/{contactMessage}/reply', [ContactMessageController::class, 'reply'])->name('contacts.reply');
    Route::delete('/contacts/bulk-delete', [ContactMessageController::class, 'bulkDestroy'])->name('contacts.bulk_destroy');
    Route::delete('/contacts/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contacts.destroy');
    Route::get('/projects', [ProjectController::class, 'adminIndex'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::delete('/projects/bulk-delete', [ProjectController::class, 'bulkDestroy'])->name('projects.bulk_destroy');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/packages', [App\Http\Controllers\Admin\PackageController::class, 'index'])->name('packages.index');
    Route::post('/packages/mark-read', [App\Http\Controllers\Admin\PackageController::class, 'markAllRead'])->name('packages.mark_read');
    Route::post('/packages/{packageRequest}/seen', [App\Http\Controllers\Admin\PackageController::class, 'markRead'])->name('packages.mark_read_single');
    Route::post('/packages/{packageRequest}/reply', [App\Http\Controllers\Admin\PackageController::class, 'reply'])->name('packages.reply');
    Route::delete('/packages/bulk-delete', [App\Http\Controllers\Admin\PackageController::class, 'bulkDestroy'])->name('packages.bulk_destroy');
    Route::delete('/packages/{packageRequest}', [App\Http\Controllers\Admin\PackageController::class, 'destroy'])->name('packages.destroy');
});
