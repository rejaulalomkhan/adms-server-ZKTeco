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
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\UserOfficeController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;


Route::get('devices', [DeviceController::class, 'Index'])->name('devices.index');
Route::get('devices-log', [DeviceController::class, 'DeviceLog'])->name('devices.DeviceLog');
Route::get('finger-log', [DeviceController::class, 'FingerLog'])->name('devices.FingerLog');
Route::get('attendance', [DeviceController::class, 'Attendance'])->name('devices.Attendance');
Route::get('devices/guide', [DeviceController::class, 'Guide'])->name('devices.Guide');
Route::get('devices/create', [DeviceController::class, 'create'])->name('devices.create');
Route::post('devices', [DeviceController::class, 'store'])->name('devices.store');
Route::get('devices/{device}/edit', [DeviceController::class, 'edit'])->name('devices.edit');
Route::put('devices/{device}', [DeviceController::class, 'update'])->name('devices.update');
Route::delete('devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');


// handshake
Route::get('/iclock/cdata', [iclockController::class, 'handshake']);
// request dari device
Route::post('/iclock/cdata', [iclockController::class, 'receiveRecords']);

Route::get('/iclock/test', [iclockController::class, 'test']);
Route::get('/iclock/getrequest', [iclockController::class, 'getrequest']);



Route::get('/', function () { return redirect('dashboard'); });

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Profile
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

// Shifts CRUD
Route::prefix('shifts')->name('shifts.')->middleware('auth')->group(function () {
    Route::get('/', [ShiftController::class, 'index'])->name('index');
    Route::get('/data', [ShiftController::class, 'data'])->name('data');
    Route::get('/create', [ShiftController::class, 'create'])->name('create');
    Route::post('/', [ShiftController::class, 'store'])->name('store');
    Route::get('/{shift}/edit', [ShiftController::class, 'edit'])->name('edit');
    Route::put('/{shift}', [ShiftController::class, 'update'])->name('update');
    Route::delete('/{shift}', [ShiftController::class, 'destroy'])->name('destroy');
});

// Dashboard page (protected)
Route::view('/dashboard', 'dashboard.index')->name('dashboard.index')->middleware('auth');
Route::redirect('/dashboard/ui', '/dashboard')->middleware('auth');

// Holidays CRUD
Route::prefix('holidays')->name('holidays.')->middleware('auth')->group(function () {
    Route::get('/', [HolidayController::class, 'index'])->name('index');
    Route::get('/data', [HolidayController::class, 'data'])->name('data');
    Route::get('/create', [HolidayController::class, 'create'])->name('create');
    Route::post('/', [HolidayController::class, 'store'])->name('store');
    Route::get('/{holiday}/edit', [HolidayController::class, 'edit'])->name('edit');
    Route::put('/{holiday}', [HolidayController::class, 'update'])->name('update');
    Route::delete('/{holiday}', [HolidayController::class, 'destroy'])->name('destroy');
});

// Overtime
Route::prefix('overtime')->name('overtime.')->middleware('auth')->group(function(){
    Route::get('/', [OvertimeController::class, 'index'])->name('index');
    Route::get('/data', [OvertimeController::class, 'data'])->name('data');
    Route::post('/calculate', [OvertimeController::class, 'calculate'])->name('calculate');
    Route::post('/{overtime}/approve', [OvertimeController::class, 'approve'])->name('approve');
});

// Reports
Route::prefix('reports')->name('reports.')->middleware('auth')->group(function(){
    Route::get('/', [ReportsController::class, 'index'])->name('index');
    Route::get('/attendance', [ReportsController::class, 'attendanceData'])->name('attendance');
    Route::get('/lateness', [ReportsController::class, 'latenessData'])->name('lateness');
    Route::get('/absence', [ReportsController::class, 'absenceData'])->name('absence');
});

// Offices CRUD
Route::prefix('offices')->name('offices.')->middleware('auth')->group(function(){
    Route::get('/', [OfficeController::class, 'index'])->name('index');
    Route::get('/data', [OfficeController::class, 'data'])->name('data');
    Route::get('/create', [OfficeController::class, 'create'])->name('create');
    Route::post('/', [OfficeController::class, 'store'])->name('store');
    Route::get('/{office}/edit', [OfficeController::class, 'edit'])->name('edit');
    Route::put('/{office}', [OfficeController::class, 'update'])->name('update');
    Route::delete('/{office}', [OfficeController::class, 'destroy'])->name('destroy');
});

// Users -> Office assignment
Route::prefix('user-offices')->name('user-offices.')->middleware('auth')->group(function(){
    Route::get('/', [UserOfficeController::class, 'index'])->name('index');
    Route::get('/data', [UserOfficeController::class, 'data'])->name('data');
    Route::get('/{user}/edit', [UserOfficeController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserOfficeController::class, 'update'])->name('update');
});

// Areas CRUD
Route::prefix('areas')->name('areas.')->middleware('auth')->group(function(){
    Route::get('/', [AreaController::class, 'index'])->name('index');
    Route::get('/data', [AreaController::class, 'data'])->name('data');
    Route::get('/create', [AreaController::class, 'create'])->name('create');
    Route::post('/', [AreaController::class, 'store'])->name('store');
    Route::get('/{area}/edit', [AreaController::class, 'edit'])->name('edit');
    Route::put('/{area}', [AreaController::class, 'update'])->name('update');
    Route::delete('/{area}', [AreaController::class, 'destroy'])->name('destroy');
});

// Employees CRUD - Super Admin only
Route::prefix('users')->name('users.')->middleware(['auth','role:Super Admin'])->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});

// Roles & Permissions - Super Admin only
Route::middleware(['auth','role:Super Admin'])->group(function(){
    Route::prefix('roles')->name('roles.')->group(function(){
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::get('/create', [RolesController::class, 'create'])->name('create');
        Route::post('/', [RolesController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RolesController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RolesController::class, 'update'])->name('update');
        Route::delete('/{role}', [RolesController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('permissions')->name('permissions.')->group(function(){
        Route::get('/', [PermissionsController::class, 'index'])->name('index');
        Route::get('/create', [PermissionsController::class, 'create'])->name('create');
        Route::post('/', [PermissionsController::class, 'store'])->name('store');
    });
});

// Shift Rotations CRUD
Route::prefix('shift-rotations')->name('shift-rotations.')->middleware('auth')->group(function () {
    Route::get('/', [ShiftRotationController::class, 'index'])->name('index');
    Route::get('/data', [ShiftRotationController::class, 'data'])->name('data');
    Route::get('/create', [ShiftRotationController::class, 'create'])->name('create');
    Route::post('/', [ShiftRotationController::class, 'store'])->name('store');
    Route::get('/{shift_rotation}/edit', [ShiftRotationController::class, 'edit'])->name('edit');
    Route::put('/{shift_rotation}', [ShiftRotationController::class, 'update'])->name('update');
    Route::delete('/{shift_rotation}', [ShiftRotationController::class, 'destroy'])->name('destroy');
});

// Manual Shift Assignments CRUD
Route::prefix('shift-assignments')->name('shift-assignments.')->middleware('auth')->group(function () {
    Route::get('/', [ShiftAssignmentController::class, 'index'])->name('index');
    Route::get('/data', [ShiftAssignmentController::class, 'data'])->name('data');
    Route::get('/create', [ShiftAssignmentController::class, 'create'])->name('create');
    Route::post('/', [ShiftAssignmentController::class, 'store'])->name('store');
    Route::get('/{shift_assignment}/edit', [ShiftAssignmentController::class, 'edit'])->name('edit');
    Route::put('/{shift_assignment}', [ShiftAssignmentController::class, 'update'])->name('update');
    Route::delete('/{shift_assignment}', [ShiftAssignmentController::class, 'destroy'])->name('destroy');
});
