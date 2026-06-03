<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\GuruBk\GuruBkDashboardController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Siswa\QuestionnaireController;
use App\Http\Controllers\Siswa\RiwayatController;
use App\Http\Controllers\Siswa\ChatbotController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('siswa.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('guru-bk', \App\Http\Controllers\Admin\AdminGuruBkController::class)->except(['show']);
    Route::resource('siswa', \App\Http\Controllers\Admin\AdminSiswaController::class)->except(['show']);
    Route::resource('soal', \App\Http\Controllers\Admin\AdminSoalController::class)->only(['index', 'edit', 'update']);
});

// Guru BK Routes
Route::middleware(['auth', 'role:guru_bk'])->prefix('guru-bk')->name('guruBk.')->group(function () {
    Route::get('/dashboard', [GuruBkDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analisis-bi', [\App\Http\Controllers\GuruBk\GuruBkBiController::class, 'index'])->name('bi');
    Route::get('/siswa', [\App\Http\Controllers\GuruBk\GuruBkSiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/{id}', [\App\Http\Controllers\GuruBk\GuruBkSiswaController::class, 'show'])->name('siswa.show');
    Route::post('/siswa/{id}/rekomendasi', [\App\Http\Controllers\GuruBk\GuruBkSiswaController::class, 'storeRecommendation'])->name('siswa.recommendation');
});

// Siswa Routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

    // Assessment Wizard
    Route::get('/assessment', [QuestionnaireController::class, 'index'])->name('questionnaire.index');

    Route::get('/assessment/sdq',   [QuestionnaireController::class, 'sdq'])->name('questionnaire.sdq');
    Route::post('/assessment/sdq',  [QuestionnaireController::class, 'storeSdqStep'])->name('questionnaire.sdq.store');

    Route::get('/assessment/psc17', [QuestionnaireController::class, 'psc17'])->name('questionnaire.psc17');
    Route::post('/assessment/psc17',[QuestionnaireController::class, 'storePsc17Step'])->name('questionnaire.psc17.store');

    Route::get('/assessment/sassv', [QuestionnaireController::class, 'sassv'])->name('questionnaire.sassv');
    Route::post('/assessment/sassv',[QuestionnaireController::class, 'storeSassvStep'])->name('questionnaire.sassv.store');

    Route::get('/assessment/hasil/{id}', [QuestionnaireController::class, 'result'])->name('questionnaire.result');

    // Riwayat Assessment
    Route::get('/riwayat',      [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');

    // Chatbot Edukatif
    Route::get('/chatbot',       [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
});

require __DIR__.'/auth.php';