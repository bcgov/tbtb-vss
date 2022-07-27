<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('manual-logout');
//Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'authorised' => Auth::check(),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth'])->group(function () {

    Route::get('/fetch-active-users', [App\Http\Controllers\UserController::class, 'activeUsers'])->name('fetch-active-users');
    Route::get('/fetch-cancelled-users', [App\Http\Controllers\UserController::class, 'cancelledUsers'])->name('fetch-cancelled-users');

    Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [App\Http\Controllers\UserController::class, 'reports'])->name('reports');
    Route::get('/reports/download/{case}', [App\Http\Controllers\ReportController::class, 'downloadSingleStudentReport'])->name('download-single-student-report');
    Route::get('/reports/over_award', [App\Http\Controllers\ReportController::class, 'showOverAward'])->name('show-overaward-report');

    Route::get('/maintenance/{page?}', [App\Http\Controllers\MaintenanceController::class, 'goToPage'])->name('maintenance');

    Route::get('/archive', [App\Http\Controllers\UserController::class, 'reports'])->name('archive');
    Route::get('/archive/cases', [App\Http\Controllers\IncidentController::class, 'archived'])->name('archive.cases.list');

    Route::resource('cases', App\Http\Controllers\IncidentController::class);

    Route::get('/cases-search', function () {
        return Inertia::render('@/Pages/CasesSearch');
    })->name('cases-search');
    Route::post('/case-search/sin', [App\Http\Controllers\IncidentController::class, 'sinSearch'])->name('case-sin-search');
    Route::post('/case-search/name', [App\Http\Controllers\IncidentController::class, 'nameSearch'])->name('case-name-search');
    Route::post('/case-search/active_user', [App\Http\Controllers\IncidentController::class, 'activeUserSearch'])->name('case-active-user-search');
    Route::post('/case-search/cancelled_user', [App\Http\Controllers\IncidentController::class, 'cancelledUserSearch'])->name('case-cancelled-user-search');

    Route::get('/case-search/{x?}', function () {
        return Redirect::route('dashboard');
    })->name('case-sin-search-dashboard-redirect');
//    Route::inertia('/cases/case-funding/{incident}', 'CaseFunding')->name('pages.case-funding');

//    Route::get('/cases/case-funding/{incident}', [App\Http\Controllers\IncidentController::class, 'show'])->name('incidents.case-funding');
//    Route::post('/cases/case-funding/new', [App\Http\Controllers\IncidentController::class, 'store'])->name('incidents.case-funding.store');
    Route::resource('case-funding', App\Http\Controllers\CaseFundingController::class);

    Route::post('/cases/{case}/delete-sanction', [App\Http\Controllers\CaseSanctionTypeController::class, 'deleteSanction'])->name('case-funding-delete-sanction');
    Route::post('/cases/{case}/delete-offence', [App\Http\Controllers\CaseNatureOffenceController::class, 'deleteOffence'])->name('case-funding-delete-offence');
    Route::post('/cases/{case}/delete-area-of-audit', [App\Http\Controllers\CaseAuditTypeController::class, 'deleteAuditType'])->name('case-funding-delete-audit-type');

    Route::resource('case-comment', App\Http\Controllers\CaseCommentController::class);

    //    Route::get('/dashboard', function () {
//        return Inertia::render('Dashboard');
//    })->name('dashboard');

    //authenticated admin routes
    Route::group(['middleware' => ['admin']], function () {

    });
});
require __DIR__.'/auth.php';
