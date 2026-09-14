<?php

use App\Jobs\SendMailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/with-queue', function () {
    $start = microtime(true);

    SendMailJob::dispatch();

    $duration = microtime(true) - $start;

    return 'Email sent in: ' . number_format($duration, 4) . ' seconds';
});

Route::get('/without-queue', function () {
    $start = microtime(true);

    sleep(5);

    
    Mail::raw('Hello from Laravel + Redis!', function ($message) {
        $message
            ->to('demo@chinchu.com')
            ->subject('Redis Queue Test');
    });

    $duration = microtime(true) - $start;

    return 'Email sent in: ' . number_format($duration, 4) . ' seconds';
});

Route::get('/test-redis', function () {

    $start = microtime(true);

    SendMailJob::dispatch();

    $duration = microtime(true) - $start;

    return [
        'message' => 'Job dispatched',
        'dispatch_time' => number_format($duration, 4) . ' seconds',
    ];
});