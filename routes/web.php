<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeedController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\VaultRegistrationOfficeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'doRegister'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('states', StateController::class)->except(['show']);
Route::resource('districts', DistrictController::class)->except(['show']);
Route::resource('offices', VaultRegistrationOfficeController::class)->except(['show']);

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', UserController::class)->except(['show']);
});

Route::resource('indexes', IndexController::class)->except(['show']);
Route::get('indexes/{index}', [IndexController::class, 'show'])->name('indexes.show');
Route::post('indexes/{index}/status', [IndexController::class, 'updateStatus'])->name('indexes.status.update');

Route::get('indexes/{index}/deeds', [DeedController::class, 'index'])->name('indexes.deeds.index');
Route::get('indexes/{index}/deeds/create', [DeedController::class, 'create'])->name('indexes.deeds.create');
Route::post('indexes/{index}/deeds', [DeedController::class, 'store'])->name('indexes.deeds.store');
Route::get('indexes/{index}/deeds/{deed}', [DeedController::class, 'show'])->name('indexes.deeds.show');
Route::get('indexes/{index}/deeds/{deed}/edit', [DeedController::class, 'edit'])->name('indexes.deeds.edit');
Route::put('indexes/{index}/deeds/{deed}', [DeedController::class, 'update'])->name('indexes.deeds.update');
Route::delete('indexes/{index}/deeds/{deed}', [DeedController::class, 'destroy'])->name('indexes.deeds.destroy');
Route::get('deeds', [DeedController::class, 'all'])->name('deeds.index');
Route::get('deeds/{deed}', [DeedController::class, 'showGlobal'])->name('deeds.show');
Route::get('deeds/{deed}/download', [DeedController::class, 'download'])->name('deeds.download');

Route::get('metadata', [MetadataController::class, 'index'])->name('metadata.index');
Route::get('deeds/{deed}/metadata/create', [MetadataController::class, 'create'])->name('deeds.metadata.create');
Route::post('deeds/{deed}/metadata', [MetadataController::class, 'store'])->name('deeds.metadata.store');
Route::get('deeds/{deed}/metadata/{metadata}', [MetadataController::class, 'edit'])->name('deeds.metadata.edit');
Route::get('deeds/{deed}/metadata/{metadata}/view', [MetadataController::class, 'show'])->name('deeds.metadata.show');
Route::put('deeds/{deed}/metadata/{metadata}', [MetadataController::class, 'update'])->name('deeds.metadata.update');
Route::delete('deeds/{deed}/metadata/{metadata}', [MetadataController::class, 'destroy'])->name('deeds.metadata.destroy');

Route::get('verifications', [VerificationController::class, 'indexVerifications'])->name('verifications.index');
Route::post('verifications/index/{index}', [VerificationController::class, 'verifyIndex'])->name('verifications.index.verify');
Route::get('verifications/metadata', [VerificationController::class, 'metadataVerifications'])->name('verifications.metadata');
Route::post('verifications/metadata/{metadata}', [VerificationController::class, 'verifyMetadata'])->name('verifications.metadata.verify');
Route::get('verifications/qc', [VerificationController::class, 'qcVerifications'])->name('verifications.qc');
Route::post('verifications/qc/{index}', [VerificationController::class, 'verifyQc'])->name('verifications.qc.verify');
