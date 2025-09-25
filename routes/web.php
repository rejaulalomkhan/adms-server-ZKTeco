<?php

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
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AbsensiSholatController;
use App\Http\Controllers\iclockController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftRotationController;
use App\Http\Controllers\ShiftAssignmentController;
use App\Http\Controllers\DashboardController as WebDashboardController;


Route::get('devices', [DeviceController::class, 'Index'])->name('devices.index');
Route::get('devices-log', [DeviceController::class, 'DeviceLog'])->name('devices.DeviceLog');
Route::get('finger-log', [DeviceController::class, 'FingerLog'])->name('devices.FingerLog');
Route::get('attendance', [DeviceController::class, 'Attendance'])->name('devices.Attendance');


// handshake
Route::get('/iclock/cdata', [iclockController::class, 'handshake']);
// request dari device
Route::post('/iclock/cdata', [iclockController::class, 'receiveRecords']);

Route::get('/iclock/test', [iclockController::class, 'test']);
Route::get('/iclock/getrequest', [iclockController::class, 'getrequest']);



Route::get('/', function () {
    return redirect('dashboard');
});

// Shifts CRUD
Route::prefix('shifts')->name('shifts.')->group(function () {
    Route::get('/', [ShiftController::class, 'index'])->name('index');
    Route::get('/data', [ShiftController::class, 'data'])->name('data');
    Route::get('/create', [ShiftController::class, 'create'])->name('create');
    Route::post('/', [ShiftController::class, 'store'])->name('store');
    Route::get('/{shift}/edit', [ShiftController::class, 'edit'])->name('edit');
    Route::put('/{shift}', [ShiftController::class, 'update'])->name('update');
    Route::delete('/{shift}', [ShiftController::class, 'destroy'])->name('destroy');
});

// Dashboard page
Route::get('/dashboard', [WebDashboardController::class, 'summary'])->name('dashboard.json');
Route::view('/dashboard/ui', 'dashboard.index')->name('dashboard.index');

// Shift Rotations CRUD
Route::prefix('shift-rotations')->name('shift-rotations.')->group(function () {
    Route::get('/', [ShiftRotationController::class, 'index'])->name('index');
    Route::get('/data', [ShiftRotationController::class, 'data'])->name('data');
    Route::get('/create', [ShiftRotationController::class, 'create'])->name('create');
    Route::post('/', [ShiftRotationController::class, 'store'])->name('store');
    Route::get('/{shift_rotation}/edit', [ShiftRotationController::class, 'edit'])->name('edit');
    Route::put('/{shift_rotation}', [ShiftRotationController::class, 'update'])->name('update');
    Route::delete('/{shift_rotation}', [ShiftRotationController::class, 'destroy'])->name('destroy');
});

// Manual Shift Assignments CRUD
Route::prefix('shift-assignments')->name('shift-assignments.')->group(function () {
    Route::get('/', [ShiftAssignmentController::class, 'index'])->name('index');
    Route::get('/data', [ShiftAssignmentController::class, 'data'])->name('data');
    Route::get('/create', [ShiftAssignmentController::class, 'create'])->name('create');
    Route::post('/', [ShiftAssignmentController::class, 'store'])->name('store');
    Route::get('/{shift_assignment}/edit', [ShiftAssignmentController::class, 'edit'])->name('edit');
    Route::put('/{shift_assignment}', [ShiftAssignmentController::class, 'update'])->name('update');
    Route::delete('/{shift_assignment}', [ShiftAssignmentController::class, 'destroy'])->name('destroy');
});
