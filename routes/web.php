<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\VehicleController;
use App\Models\Station;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dispatcher\LiveMapController;
use App\Http\Controllers\Driver\DashboardController;
use App\Http\Controllers\Driver\ShiftController;
use App\Http\Controllers\Driver\ReportFaultController;
use App\Http\Controllers\Controller\TicketController;
use App\Http\Controllers\Passenger\TicketsController;
use App\Http\Controllers\Passenger\ScheduleController;
use App\Http\Controllers\Passenger\MapController;
use App\Http\Controllers\Mechanic\WorkOrderController;
use App\Http\Controllers\Mechanic\InspectionController;


Route::get('/register',[AuthController::class,'showRegister'])->name('register');
Route::post('/register',[AuthController::class,'register']);
Route::middleware('auth')->group(function () {
    Route::get('/driver/dashboard', [DashboardController::class, 'index'])->name('driver.dashboard');
});
Route::get('/login',[AuthController::class,'showLogIn'])->name('login');
Route::post('/login',[AuthController::class,'LogIn']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dispatcher/requests',[RequestsController::class,'index'])->name('requests.index');
Route::post('/dispatcher/requests/{id}/approve',[RequestsController::class,'approve'])->name('requests.approve');
Route::post('/dispatcher/requests/{id}/reject',[RequestsController::class,'reject']);
Route::get('/', function () {
    return view('passenger.mainview');
});
Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketsController::class, 'store'])->name('tickets.store');
});
Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index')->middleware('auth');
Route::get('/controller/dashboard', function () {
    return view('controller.controllerdashboard');
})->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('/controller/tickets', [TicketController::class, 'index'])->name('controller.tickets');
    Route::post('/controller/tickets/validate', [TicketController::class, 'validate'])->name('controller.tickets.validate');
});
Route::get('/liveMap', [MapController::class, 'index'])->name('passenger.livemap')->middleware('auth');


Route::get('/driver/triporder', function () {
    return view('driver.drivertriporder');
});
Route::get('/driver/predeparture', function () {
    return view('driver.predeparture');
});
Route::get('/driver/myshift', [ShiftController::class, 'index'])->name('driver.shift');

Route::middleware('auth')->group(function () {
    Route::get('/driver/reportfault', [ReportFaultController::class, 'index'])->name('driver.reportfault');
    Route::post('/driver/reportfault', [ReportFaultController::class, 'store'])->name('driver.reportfault.store');
});
Route::middleware('auth')->group(function () {
    Route::get('/mechanic/workorders', [WorkOrderController::class, 'index'])->name('mechanic.workorders');
    Route::post('/mechanic/faults/{id}/accept', [WorkOrderController::class, 'accept'])->name('mechanic.faults.accept');
    Route::post('/mechanic/faults/{id}/complete', [WorkOrderController::class, 'complete'])->name('mechanic.faults.complete');
});
Route::get('/mechanic/inspections', [InspectionController::class, 'index'])->name('mechanic.inspections')->middleware('auth');

Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/register/role', function () {
    return view('auth.registerrole');
});
Route::get('/register', function () {
    return view('auth.register');
});
Route::get('/dispatcher/fleet',[VehicleController::class, 'index']);
Route::post('/dispatcher/fleet/add-vehicle', [VehicleController::class, 'addVehicle'])
    ->name('fleet.store');

Route::get('/api/stations', function () {
    return Station::all();
});

Route::middleware('auth')->group(function () {
    Route::get('/dispatcher/livemap', [LiveMapController::class, 'index'])->name('dispatcher.livemap');
    Route::get('/dispatcher/livemap/locations', [LiveMapController::class, 'locations'])->name('dispatcher.livemap.locations');
});