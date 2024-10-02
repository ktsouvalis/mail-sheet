<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SingleController;

Route::get('/', function () {
    return view('index');
});

Route::post('/file/upload', [SingleController::class, 'upload'])->name('upload-file');

Route::view('/file/confirm', 'confirm')->name('confirm');

Route::post('/emails/send', [SingleController::class, 'send'])->name('send-emails');

Route::get('/emails/preview', [SingleController::class, 'preview'])->name('preview-emails');