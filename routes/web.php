<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\WebinarBookingController;
use App\Http\Controllers\WebinarController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/make-call', [SMSController::class, 'makeCall'])->name('make-call');

Route::get('/check-twilio', function () {
    return response()->json([
        'sid' => config('services.twilio.sid'),
        'token' => config('services.twilio.token') ? 'Loaded' : 'Missing',
        'from' => config('services.twilio.from'),
    ]);
});

Route::get('/debug-env', function () {
    return response()->json([
        'TWILIO_SID' => env('TWILIO_SID'),
        'TWILIO_TOKEN' => env('TWILIO_TOKEN'),
        'TWILIO_NUMBER' => env('TWILIO_NUMBER'),
    ]);
});

// Route::get('/webinars', [WebinarController::class, 'index']);
// Route::resource('webinars', WebinarController::class);

// // Route::get('webinars', WebinarController::class);

// Route::get('/webinars/{webinar}', [WebinarController::class, 'show'])->name('webinars.show');

// Route::post('/webinars/{webinar}/book', [WebinarBookingController::class, 'book'])
//     ->middleware('auth')
//     ->name('webinars.book');

//     Route::get('/my-webinars', [WebinarBookingController::class, 'myWebinars'])
//     ->middleware('auth')
//     ->name('my.webinars');

// Route::get('/webinars', [WebinarController::class, 'index']);

//public routes for webinars

//Admin (Only Admin Can Manage Webinars)
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('webinars', WebinarController::class)->except(['index', 'show']);
});
Route::resource('webinars', WebinarController::class)->only(['index', 'show']); 

// Booking Routes (Only Logged-in Users)
Route::post('/webinars/{webinar}/book', [WebinarBookingController::class, 'book'])
    ->middleware('auth')
    ->name('webinars.book');

Route::get('/my-webinars', [WebinarBookingController::class, 'myWebinars'])
    ->middleware('auth')
    ->name('my.webinars');


Route::get('/test-mail', function() {
    Mail::raw('Test email works!', function($message){
        $message->to('yourtestemail@example.com')->subject('Test Mail');
    });
    return 'Mail sent!';
});
Route::get('/content', [App\Http\Controllers\ContentController::class, 'index'])->name('content.page');
Route::get('/webinar/{id}', [WebinarController::class, 'show'])->name('webinars.show');
// Route::post('/webinar/{id}/book', [WebinarBookingController::class, 'store'])->name('webinars.book');


