<?php

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

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/fetch-active-users', [App\Http\Controllers\UserController::class, 'activeUsers'])->name('fetch-active-users');
    Route::get('/fetch-cancelled-users', [App\Http\Controllers\UserController::class, 'cancelledUsers'])->name('fetch-cancelled-users');

    Route::get('/dashboard', [App\Http\Controllers\IncidentController::class, 'dashboard'])->name('dashboard');
    Route::resource('cases', App\Http\Controllers\IncidentController::class);

    Route::get('/case-search/{x?}', function () {
        return Redirect::route('dashboard');
    })->name('case-sin-search-dashboard-redirect');

    Route::resource('case-funding', App\Http\Controllers\CaseFundingController::class);

    Route::post('/cases/{case}/delete-sanction', [App\Http\Controllers\CaseSanctionTypeController::class, 'deleteSanction'])->name('case-funding-delete-sanction');
    Route::post('/cases/{case}/delete-offence', [App\Http\Controllers\CaseNatureOffenceController::class, 'deleteOffence'])->name('case-funding-delete-offence');
    Route::post('/cases/{case}/delete-area-of-audit', [App\Http\Controllers\CaseAuditTypeController::class, 'deleteAuditType'])->name('case-funding-delete-audit-type');

    Route::resource('case-comment', App\Http\Controllers\CaseCommentController::class);

    //authenticated admin routes
    Route::group(['middleware' => ['admin']], function () {
        Route::get('/reports', [App\Http\Controllers\UserController::class, 'reports'])->name('reports');
        Route::get('/reports/download/{case}', [App\Http\Controllers\ReportController::class, 'downloadSingleStudentReport'])->name('download-single-student-report');

        Route::post('/reports', [App\Http\Controllers\ReportController::class, 'searchReports'])->name('reports-search');

//        Route::get('/maintenance', [App\Http\Controllers\MaintenanceController::class, 'goToPage'])->name('maintenance');
        Route::get('/maintenance/areas-of-audit', [App\Http\Controllers\MaintenanceController::class, 'areasList'])->name('maintenance.areas-of-audit.list');
        Route::put('/maintenance/areas-of-audit/{area}', [App\Http\Controllers\MaintenanceController::class, 'areasEdit'])->name('maintenance.areas-of-audit.edit');
        Route::post('/maintenance/areas-of-audit', [App\Http\Controllers\MaintenanceController::class, 'areasStore'])->name('maintenance.areas-of-audit.store');

        Route::get('/maintenance/schools', [App\Http\Controllers\MaintenanceController::class, 'staffList'])->name('maintenance.schools.list');
        Route::get('/maintenance/staff', [App\Http\Controllers\MaintenanceController::class, 'staffList'])->name('maintenance.staff.list');
        Route::get('/maintenance/staff/{user}', [App\Http\Controllers\MaintenanceController::class, 'staffShow'])->name('maintenance.staff.show');
        Route::post('/maintenance/staff/{user}', [App\Http\Controllers\MaintenanceController::class, 'staffEdit'])->name('maintenance.staff.edit');

        Route::get('/archive', [App\Http\Controllers\UserController::class, 'reports'])->name('archive');
        Route::get('/archive/cases', [App\Http\Controllers\IncidentController::class, 'archived'])->name('archive.cases.list');
    });
});
require __DIR__.'/auth.php';

