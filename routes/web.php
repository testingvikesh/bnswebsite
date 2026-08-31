<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AudienceProgramController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ExpertController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayNowController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PitchController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SponsorsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\VenueInspectionController;
use App\Http\Controllers\VisitingFacultyController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/introduction-session-admission', function () {
    return redirect()->route('home', ['open' => 'introduction-session']);
})->name('introduction-session.admission');
Route::get('/book-your-spot', function () {
    return redirect()->route('home', ['open' => 'introduction-session']);
})->name('book-your-spot');

Route::get('/pitch', [PitchController::class, 'index'])->name('pitch');
Route::get('/pitch/business-coach', [PitchController::class, 'businessCoach'])->name('pitch.business-coach');
Route::get('/pitch/bns-member', [PitchController::class, 'bnsMember'])->name('pitch.bns-member');
Route::get('/expert/mehul', [ExpertController::class, 'mehul'])->name('expert.mehul');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/about/mission', [AboutController::class, 'mission'])->name('about.mission');
Route::get('/about/vision', [AboutController::class, 'vision'])->name('about.vision');
Route::get('/about/core-values', [AboutController::class, 'values'])->name('about.values');
Route::get('/about/founders-message', [AboutController::class, 'founder'])->name('about.founder');
Route::get('/about/why-bns', [AboutController::class, 'why'])->name('about.why');
Route::get('/about/why-business-education', [AboutController::class, 'whyBusinessEducation'])->name('about.why-business-education');
Route::get('/about/prosperity-mission', [AboutController::class, 'prosperityMission'])->name('about.prosperity-mission');
Route::get('/about/vision-2047', [AboutController::class, 'vision2047'])->name('about.vision-2047');
Route::get('/about/team', [TeamController::class, 'index'])->name('about.team');
Route::get('/about/sponsors', [SponsorsController::class, 'index'])->name('about.sponsors');
Route::get('/about/visiting-faculty', [VisitingFacultyController::class, 'index'])->name('about.faculty');
Route::get('/programs/featured', [ProgramsController::class, 'featured'])->name('programs.featured');
Route::get('/syllabus', [ProgramsController::class, 'syllabus'])->name('syllabus');
Route::redirect('/programs/job-business-batch', '/programs/women-batch', 301);
Route::get('/programs/{slug}', [AudienceProgramController::class, 'show'])
    ->name('programs.show')
    ->where('slug', implode('|', array_keys(config('audience_programs', []))));
Route::get('/success-statistics', [StatisticsController::class, 'index'])->name('statistics.index');
Route::get('/courses', fn () => redirect()->route('programs.featured'));
Route::get('/course-details', function () { return view('home'); });
Route::get('/course-list', function () { return view('home'); });
Route::get('/instructor', function () { return view('home'); });
Route::get('/events', [EventsController::class, 'index'])->name('events.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/message', [MessageController::class, 'index'])->name('message.index');
Route::post('/message/send-mail', [MessageController::class, 'sendMail'])
    ->middleware('throttle:20,1')
    ->name('message.send-mail');

Route::prefix('mail')->name('mail.')->group(function () {
    Route::get('/', [MailController::class, 'loginForm'])->name('login');
    Route::post('/login', [MailController::class, 'login'])
        ->middleware('throttle:12,1')
        ->name('login.store');

    Route::middleware('mail.auth')->group(function () {
        Route::post('/logout', [MailController::class, 'logout'])->name('logout');
        Route::get('/hub', [MailController::class, 'hub'])->name('hub');
        Route::get('/student', [MailController::class, 'student'])->name('student');
        Route::get('/business-coach', [MailController::class, 'businessCoach'])->name('business-coach');
        Route::post('/send-mail', [MailController::class, 'sendMail'])
            ->middleware('throttle:20,1')
            ->name('send-mail');
    });
});
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials');
Route::get('/pricing', function () { return view('home'); });
Route::get('/faq', function () { return view('home'); });
Route::get('/blog', function () { return view('home'); });
Route::get('/blog-details', function () { return view('home'); });
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/csrf-token', function () {
    return response()
        ->json(['token' => csrf_token()])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
})->name('csrf-token');
Route::post('/contact/check-mobile', [ContactController::class, 'checkMobile'])->name('contact.check-mobile');
Route::post('/contact/check-email', [ContactController::class, 'checkEmail'])->name('contact.check-email');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/thank-you', [ContactController::class, 'thankYou'])->name('contact.thank-you');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::redirect('/brochure', '/', 301)->name('brochure');
Route::redirect('/brochure/view', '/', 301)->name('brochure.view');
Route::redirect('/brochure/download', '/', 301)->name('brochure.download');
Route::redirect('/admissions/download-brochure', '/', 301);
Route::get('/legal', [LegalController::class, 'index'])->name('legal.index');
Route::redirect('/legal/legal-compliance', '/legal/data-protection-policy', 301);
Route::redirect('/legal/intellectual-property-policy', '/legal/intellectual-property-policies', 301);
Route::get('/legal/{slug}', [LegalController::class, 'show'])->name('legal.show');
Route::get('/whatsapp-support', [WhatsappController::class, 'index'])->name('whatsapp.support');
Route::get('/follow-us', [SocialController::class, 'index'])->name('social.follow');

Route::prefix('admissions')->name('admissions.')->group(function () {
    Route::get('/', [AdmissionController::class, 'index'])->name('index');
    Route::get('/apply-now', [AdmissionController::class, 'apply'])->name('apply');
    Route::get('/online-application', [AdmissionController::class, 'onlineApply'])->name('online-apply');
    Route::post('/apply-now', [AdmissionController::class, 'store'])->name('apply.store');
    Route::get('/confirmation/{application}', [AdmissionController::class, 'confirmation'])->name('confirmation');
    Route::get('/{slug}', [AdmissionController::class, 'page'])->name('page');
});

Route::get('/register', [RegistrationController::class, 'index'])->name('register');
Route::post('/register/youth-school', [RegistrationController::class, 'storeYouth'])->name('register.youth.store');
Route::post('/register/student-school', [RegistrationController::class, 'storeStudent'])->name('register.student.store');
Route::post('/register/women-school', [RegistrationController::class, 'storeWomen'])->name('register.women.store');
Route::post('/register/working-women-school', [RegistrationController::class, 'storeWorkingWomen'])->name('register.working-women.store');
Route::post('/register/job-professional-school', [RegistrationController::class, 'storeJobProfessional'])->name('register.job-professional.store');
Route::post('/register/business-growth-school', [RegistrationController::class, 'storeBusinessGrowth'])->name('register.business-growth.store');

Route::get('/pay-now', [PayNowController::class, 'index'])->name('pay-now');
Route::post('/pay-now/membership-upload', [PayNowController::class, 'uploadMembership'])->name('pay-now.membership-upload');
Route::post('/pay-now/submit', [PayNowController::class, 'submit'])->name('pay-now.submit');
Route::post('/pay-now/pay', [PayNowController::class, 'pay'])->name('pay-now.pay');

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::post('/attendance/lookup', [AttendanceController::class, 'lookup'])->name('attendance.lookup');
Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
Route::post('/attendance/register-and-mark', [AttendanceController::class, 'registerAndMark'])->name('attendance.register-and-mark');
Route::get('/attendance/qr/{token}', [\App\Http\Controllers\AttendanceQrController::class, 'show'])->name('attendance.qr.show');
Route::post('/attendance/qr/{token}/approve', [\App\Http\Controllers\AttendanceQrController::class, 'approve'])->name('attendance.qr.approve');

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/checkout/{merchantTxnNo}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/initiate/{merchantTxnNo}', [PaymentController::class, 'initiate'])->name('initiate');
    Route::match(['get', 'post'], '/callback', [PaymentController::class, 'callback'])->name('callback');
    Route::get('/success/{merchantTxnNo}', [PaymentController::class, 'success'])->name('success');
    Route::get('/receipt/{merchantTxnNo}', [PaymentController::class, 'receipt'])->name('receipt');
    Route::get('/failure/{merchantTxnNo}', [PaymentController::class, 'failure'])->name('failure');
});

Route::get('/venue-inspection', [VenueInspectionController::class, 'index'])->name('venue-inspection');
Route::post('/venue-inspection', [VenueInspectionController::class, 'store'])->name('venue-inspection.store');
Route::get('/cart', function () { return view('home'); });

Route::any('/sop/{path?}', function (?string $path = null) {
    $target = '/controlpanel'.($path ? '/'.$path : '');
    $query = request()->getQueryString();

    return redirect($query ? $target.'?'.$query : $target, 301);
})->where('path', '.*');

require __DIR__.'/controlpanel.php';
require __DIR__.'/reporting.php';
