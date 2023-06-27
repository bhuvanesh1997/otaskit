<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::group(['prefix'=>'dashboard'], function(){
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/users', [HomeController::class, 'users'])->name('users');
    Route::post('/users', [HomeController::class, 'users']);
    Route::get('/useredit', [HomeController::class, 'user_edit'])->name('userEdit');
    Route::get('/manage_task', [HomeController::class, 'manage_task'])->name('manage_task');
    Route::post('/manage_task', [HomeController::class, 'manage_task']);
    Route::get('/manage_taskedit', [HomeController::class, 'manage_task_edit'])->name('manage_taskEdit');
    Route::get('/assign_task', [HomeController::class, 'assign_task'])->name('assign_task');
    Route::post('/assign_task', [HomeController::class, 'assign_task']);
    Route::get('/assign_taskedit', [HomeController::class, 'assign_task_edit'])->name('assign_taskEdit');
});