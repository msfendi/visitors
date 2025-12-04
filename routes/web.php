<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitLogController;
use App\Http\Controllers\VisitorCardController;
use App\Http\Controllers\VisitorController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LoginController::class, 'login'])->name('/');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/register/guest', [RegisterController::class, 'store'])->name('register.guest');

    Route::get('/login', [LoginController::class, 'login'])->name('login.guest');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    //Register
    Route::get('/register/create', [RegisterController::class, 'create'])->name('register.create')->middleware(['auth', 'role:Admin']);
    Route::post('/register', [RegisterController::class, 'storeAuth'])->name('register')->middleware(['auth', 'role:Admin']);

    //Role
    Route::get('/role/index', [RoleController::class, 'index'])->name('role.index')->middleware(['auth', 'role:Admin']);
    Route::get('/role/delete/{id}', [RoleController::class, 'delete'])->name('role.delete')->middleware(['auth', 'role:Admin']);
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create')->middleware(['auth', 'role:Admin']);
    Route::post('/role/store', [RoleController::class, 'store'])->name('role.store')->middleware(['auth', 'role:Admin']);
    Route::get('/role/find/{id}', [RoleController::class, 'find'])->name('role.find')->middleware(['auth', 'role:Admin']);
    Route::post('/role/update', [RoleController::class, 'update'])->name('role.update')->middleware(['auth', 'role:Admin']);

    //User
    Route::get('/user/index', [UserController::class, 'index'])->name('user.index')->middleware(['auth', 'role:Admin']);
    Route::get('/user/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update')->middleware(['auth', 'role:Admin']);
    Route::get('/user/detail/{id}', [UserController::class, 'detail'])->name('user.detail')->middleware(['auth', 'role:Admin']);
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware(['auth', 'role:Admin']);
    Route::get('/user/assign/{id}', [UserController::class, 'assign'])->name('user.assign')->middleware(['auth', 'role:Admin']);
    Route::post('/user/assignrole', [UserController::class, 'assignrole'])->name('user.assignrole')->middleware(['auth', 'role:Admin']);

    // Visitor
    Route::get('/visitor/index', [VisitorController::class, 'index'])->name('visitor.index');
    Route::get('/visitor/create', [VisitorController::class, 'create'])->name('visitor.create');
    Route::post('/visitor/store', [VisitorController::class, 'store'])->name('visitor.store');
    Route::get('/visitor/revision/{id}', [VisitorController::class, 'revision'])->name('visitor.revisionVisitor');
    Route::get('/visitor/fetchVisitor/{id}', [VisitorController::class, 'fetchvisitor'])->name('visitor.fetchVisitor');
    Route::post('/visitor/update', [VisitorController::class, 'update'])->name('visitor.updateVisitor');
    Route::post('/visitor/void', [VisitorController::class, 'void'])->name('visitor.void');
    Route::post('/visitor/restore', [VisitorController::class, 'restore'])->name('visitor.restore');
    Route::get('/visitor/upload', function () {
    return view('visitor.upload');
})->name('visitor.upload');

    Route::get('/visitor/fetch-employee/{npk}', [VisitorController::class, 'fetchEmployee'])->name('visitor.fetch-employee');
    Route::get('/visitor/leave', [VisitorController::class, 'leave'])->name('visitor.leave');
    Route::post('/visitor/check-in', [VisitorController::class, 'checkin'])->name('visitor.check-in');
    Route::post('/visitor/check-out', [VisitorController::class, 'checkout'])->name('visitor.check-out');

    // Visit Logs
    Route::get('/visit-logs/index', [VisitLogController::class, 'index'])->name('visit-logs.index');
    Route::get('/visit-logs/indexAll', [VisitLogController::class, 'indexAll'])->name('visit-logs.indexAll');
    Route::get('/visit-logs/create/{visitor_number}', [VisitLogController::class, 'create'])->name('visit-logs.create');
    Route::post('/visit-logs/store', [VisitLogController::class, 'store'])->name('visit-logs.store');
    Route::get('/visit-logs/indexAll', [VisitLogController::class, 'indexAll'])->name('visit-logs.indexAll');
    Route::get('/visit-logs/showvisitor', [VisitLogController::class, 'showvisitor'])->name('visit-logs.showvisitor');
    Route::get('/visit-logs/revision/{id}', [VisitLogController::class, 'revision'])->name('visit-logs.revisionVisitor');
    Route::get('/visit-logs/fetchVisitor/{id}', [VisitLogController::class, 'fetchvisitor'])->name('visit-logs.fetchVisitor');
    Route::post('/visit-logs/update', [VisitLogController::class, 'update'])->name('visit-logs.updateVisitor');
    Route::post('/visit-logs/void', [VisitLogController::class, 'void'])->name('visit-logs.void');
    Route::post('/visit-logs/restore', [VisitLogController::class, 'restore'])->name('visit-logs.restore');
    Route::get('/visit-logs/fetchDept/{id_user}', [VisitLogController::class, 'fetchDept'])->name('visit-logs.fetchDept');
    Route::post('/visit-logs/visit', [VisitLogController::class, 'visit_time'])->name('visit-logs.visitTime');
    Route::post('/visit-logs/leave', [VisitLogController::class, 'leave_time'])->name('visit-logs.leaveTime');
    Route::post('visit-logs/search', [VisitLogController::class, 'search'])->name('visit-logs.search');

    // Visitor Card
    Route::get('/visitor-card/index', [VisitorCardController::class, 'index'])->name('visitor-card.index');
    Route::get('/visitor-card/indexTable', [VisitorCardController::class, 'indexTable'])->name('visitor-card.indexTable');
    Route::get('/visitor-card/create', [VisitorCardController::class, 'create'])->name('visitor-card.create');
    Route::post('/visitor-card/store', [VisitorCardController::class, 'store'])->name('visitor-card.store');
    Route::get('/visitor-card/revision/{id}', [VisitorCardController::class, 'revision'])->name('visitor-card.revisionVisitorCard');
    Route::get('/visitor-card/fetchVisitorCard/{id}', [VisitorCardController::class, 'fetchvisitorcard'])->name('visitor-card.fetchVisitorCard');
    Route::post('/visitor-card/update', [VisitorCardController::class, 'update'])->name('visitor-card.updateVisitorCard');
    Route::post('/visitor-card/void', [VisitorCardController::class, 'void'])->name('visitor-card.void');
    Route::post('/visitor-card/restore', [VisitorCardController::class, 'restore'])->name('visitor-card.restore');
    Route::get('/visitor-card/generateqr/{id}', [VisitorCardController::class, 'generateqr'])->name('visitor-card.generateqr');
    Route::get('/visitor-card/batchQR', [VisitorCardController::class, 'batchQR'])->name('visitor-card.batchQR');
});
