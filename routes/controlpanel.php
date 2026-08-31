<?php

use App\Http\Controllers\Sop\AboutPageController;
use App\Http\Controllers\Sop\AdvisoryBoardController;
use App\Http\Controllers\Sop\ChangePasswordController;
use App\Http\Controllers\Sop\ContactPageController;
use App\Http\Controllers\Sop\DashboardController;
use App\Http\Controllers\Sop\FacultyPageController;
use App\Http\Controllers\Sop\ForgotPasswordController;
use App\Http\Controllers\Sop\HomeImageController;
use App\Http\Controllers\Sop\HomeReelController;
use App\Http\Controllers\Sop\EventGalleryController;
use App\Http\Controllers\Sop\AttendanceModuleController;
use App\Http\Controllers\Sop\IntroSessionEmailController;
use App\Http\Controllers\Sop\LoginController;
use App\Http\Controllers\Sop\LogoutController;
use App\Http\Controllers\Sop\MembershipUploadController;
use App\Http\Controllers\Sop\PaymentReportController;
use App\Http\Controllers\Sop\RegisterController;
use App\Http\Controllers\Sop\ResetPasswordController;
use App\Http\Controllers\Sop\AdmissionFormsController;
use App\Http\Controllers\Sop\AdmissionHubController;
use App\Http\Controllers\Sop\AdmissionPageController;
use App\Http\Controllers\Sop\SiteBrandingController;
use App\Http\Controllers\Sop\SocialPageController;
use App\Http\Controllers\Sop\SponsorMemberController;
use App\Http\Controllers\Sop\TeamMemberController;
use App\Http\Controllers\Sop\TeamPageController;
use App\Http\Controllers\Sop\TestimonialController;
use App\Http\Controllers\Sop\UserController;
use App\Http\Controllers\Sop\VisitingExpertFacultyController;
use App\Http\Controllers\Sop\WhatsappPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('controlpanel')->name('controlpanel.')->group(function () {
    Route::get('/', fn () => redirect()->route('controlpanel.login'));

    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'create'])->name('login');
        Route::post('login', [LoginController::class, 'store']);

        Route::get('register', [RegisterController::class, 'create'])->name('register');
        Route::post('register', [RegisterController::class, 'store']);

        Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

        Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('change-password', [ChangePasswordController::class, 'create'])->name('password.change');
        Route::post('change-password', [ChangePasswordController::class, 'store'])->name('password.change.update');

        Route::post('logout', [LogoutController::class, 'destroy'])->name('logout');

        Route::middleware('sop.admin')->group(function () {
            Route::get('about-page', [AboutPageController::class, 'edit'])->name('about-page.edit');
            Route::put('about-page', [AboutPageController::class, 'update'])->name('about-page.update');

            Route::get('home-images', [HomeImageController::class, 'index'])->name('home-images.index');
            Route::post('home-images/{homeImage}', [HomeImageController::class, 'update'])->name('home-images.update');
            Route::delete('home-images/{homeImage}', [HomeImageController::class, 'destroy'])->name('home-images.destroy');

            Route::get('home-reels', [HomeReelController::class, 'index'])->name('home-reels.index');
            Route::put('home-reels/settings', [HomeReelController::class, 'updateSection'])->name('home-reels.settings');
            Route::post('home-reels', [HomeReelController::class, 'store'])->name('home-reels.store');
            Route::put('home-reels/{homeReel}', [HomeReelController::class, 'update'])->name('home-reels.update');
            Route::delete('home-reels/{homeReel}', [HomeReelController::class, 'destroy'])->name('home-reels.destroy');

            Route::get('event-galleries', [EventGalleryController::class, 'index'])->name('event-galleries.index');
            Route::post('event-galleries', [EventGalleryController::class, 'store'])->name('event-galleries.store');
            Route::put('event-galleries/{eventGallery}', [EventGalleryController::class, 'update'])->name('event-galleries.update');
            Route::delete('event-galleries/{eventGallery}', [EventGalleryController::class, 'destroy'])->name('event-galleries.destroy');
            Route::get('event-galleries/{eventGallery}/manage', [EventGalleryController::class, 'manage'])->name('event-galleries.manage');
            Route::post('event-galleries/{eventGallery}/photos', [EventGalleryController::class, 'storePhotos'])->name('event-galleries.photos.store');
            Route::put('event-galleries/{eventGallery}/photos/{photo}', [EventGalleryController::class, 'updatePhoto'])->name('event-galleries.photos.update');
            Route::delete('event-galleries/{eventGallery}/photos/{photo}', [EventGalleryController::class, 'destroyPhoto'])->name('event-galleries.photos.destroy');
            Route::post('event-galleries/{eventGallery}/reels', [EventGalleryController::class, 'storeReel'])->name('event-galleries.reels.store');
            Route::put('event-galleries/{eventGallery}/reels/{reel}', [EventGalleryController::class, 'updateReel'])->name('event-galleries.reels.update');
            Route::delete('event-galleries/{eventGallery}/reels/{reel}', [EventGalleryController::class, 'destroyReel'])->name('event-galleries.reels.destroy');

            Route::get('sponsor-members', [SponsorMemberController::class, 'index'])->name('sponsor-members.index');
            Route::put('sponsor-members/settings', [SponsorMemberController::class, 'updateSection'])->name('sponsor-members.settings');
            Route::post('sponsor-members', [SponsorMemberController::class, 'store'])->name('sponsor-members.store');
            Route::put('sponsor-members/{sponsorMember}', [SponsorMemberController::class, 'update'])->name('sponsor-members.update');
            Route::delete('sponsor-members/{sponsorMember}', [SponsorMemberController::class, 'destroy'])->name('sponsor-members.destroy');

            Route::get('site-branding', [SiteBrandingController::class, 'edit'])->name('site-branding.edit');
            Route::put('site-branding', [SiteBrandingController::class, 'update'])->name('site-branding.update');
            Route::delete('site-branding/logo', [SiteBrandingController::class, 'destroyLogo'])->name('site-branding.destroy-logo');
            Route::delete('site-branding/favicon', [SiteBrandingController::class, 'destroyFavicon'])->name('site-branding.destroy-favicon');
            Route::delete('site-branding/brochure', [SiteBrandingController::class, 'destroyBrochure'])->name('site-branding.destroy-brochure');

            Route::get('payments', [PaymentReportController::class, 'index'])->name('payments.index');
            Route::get('payments/email-preview', [PaymentReportController::class, 'emailPreview'])->name('payments.email-preview');
            Route::post('payments/send-mail', [PaymentReportController::class, 'sendMail'])->name('payments.send-mail');
            Route::get('payments/{payment}', [PaymentReportController::class, 'show'])->name('payments.show');
            Route::post('payments/{payment}/refresh', [PaymentReportController::class, 'refreshStatus'])->name('payments.refresh');

            Route::get('membership-uploads', [MembershipUploadController::class, 'index'])->name('membership-uploads.index');
            Route::get('membership-uploads/{membershipUpload}/edit', [MembershipUploadController::class, 'edit'])->name('membership-uploads.edit');
            Route::put('membership-uploads/{membershipUpload}', [MembershipUploadController::class, 'update'])->name('membership-uploads.update');
            Route::delete('membership-uploads/{membershipUpload}', [MembershipUploadController::class, 'destroy'])->name('membership-uploads.destroy');

            Route::get('intro-session-emails', [IntroSessionEmailController::class, 'index'])->name('intro-session-emails.index');
            Route::get('intro-session-emails/preview', [IntroSessionEmailController::class, 'preview'])->name('intro-session-emails.preview');
            Route::get('intro-session-emails/export', [IntroSessionEmailController::class, 'export'])->name('intro-session-emails.export');
            Route::post('intro-session-emails/send', [IntroSessionEmailController::class, 'send'])->name('intro-session-emails.send');

            Route::get('attendance', [AttendanceModuleController::class, 'index'])->name('attendance.index');
            Route::post('attendance/mark', [AttendanceModuleController::class, 'mark'])->name('attendance.mark');
            Route::post('attendance/bulk-mark', [AttendanceModuleController::class, 'bulkMark'])->name('attendance.bulk-mark');
            Route::post('attendance/send-mail', [AttendanceModuleController::class, 'sendMail'])->name('attendance.send-mail');

            Route::get('team-page', [TeamPageController::class, 'edit'])->name('team-page.edit');
            Route::put('team-page', [TeamPageController::class, 'update'])->name('team-page.update');
            Route::resource('team-members', TeamMemberController::class)->except(['show']);

            Route::resource('users', UserController::class)->except(['show']);
            Route::resource('advisory-board', AdvisoryBoardController::class)->except(['show']);

            Route::get('contact-page', [ContactPageController::class, 'edit'])->name('contact-page.edit');
            Route::put('contact-page', [ContactPageController::class, 'update'])->name('contact-page.update');
            Route::get('contact-inquiries', [ContactPageController::class, 'inquiries'])->name('contact-inquiries.index');
            Route::get('contact-inquiries/{inquiry}', [ContactPageController::class, 'showInquiry'])->name('contact-inquiries.show');
            Route::delete('contact-inquiries/{inquiry}', [ContactPageController::class, 'destroyInquiry'])->name('contact-inquiries.destroy');

            Route::get('whatsapp-page', [WhatsappPageController::class, 'edit'])->name('whatsapp-page.edit');
            Route::put('whatsapp-page', [WhatsappPageController::class, 'update'])->name('whatsapp-page.update');

            Route::get('social-page', [SocialPageController::class, 'edit'])->name('social-page.edit');
            Route::put('social-page', [SocialPageController::class, 'update'])->name('social-page.update');

            Route::get('admission-hub', [AdmissionHubController::class, 'edit'])->name('admission-hub.edit');
            Route::put('admission-hub', [AdmissionHubController::class, 'update'])->name('admission-hub.update');

            Route::get('admission-pages', [AdmissionPageController::class, 'index'])->name('admission-pages.index');
            Route::get('admission-pages/{admission_page}/edit', [AdmissionPageController::class, 'edit'])->name('admission-pages.edit');
            Route::put('admission-pages/{admission_page}', [AdmissionPageController::class, 'update'])->name('admission-pages.update');
            Route::get('admission-forms', [AdmissionFormsController::class, 'index'])->name('admission-forms.index');
            Route::get('admission-forms/{type}/{id}', [AdmissionFormsController::class, 'show'])
                ->name('admission-forms.show')
                ->whereNumber('id')
                ->where('type', 'online|youth|student|women|working-women|job-professional|business-growth');
            Route::put('admission-forms/{type}/{id}/status', [AdmissionFormsController::class, 'updateStatus'])
                ->name('admission-forms.status')
                ->whereNumber('id')
                ->where('type', 'online|youth|student|women|working-women|job-professional|business-growth');
            Route::delete('admission-forms/{type}/{id}', [AdmissionFormsController::class, 'destroy'])
                ->name('admission-forms.destroy')
                ->whereNumber('id')
                ->where('type', 'online|youth|student|women|working-women|job-professional|business-growth');

            Route::redirect('admission-applications', '/controlpanel/admission-forms?type=online')->name('admission-applications.index');

            Route::get('faculty-page', [FacultyPageController::class, 'edit'])->name('faculty-page.edit');
            Route::put('faculty-page', [FacultyPageController::class, 'update'])->name('faculty-page.update');

            Route::resource(
                'visiting-faculty',
                VisitingExpertFacultyController::class
            )->except(['show']);

            Route::resource('testimonials', TestimonialController::class)->except(['show']);
        });
    });
});
