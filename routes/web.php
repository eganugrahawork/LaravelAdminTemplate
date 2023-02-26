<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuAccessController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->get('/settings', function(){
    return view('admin.settings.index');
});




Route::middleware('auth')->controller(DashboardController::class)->group(function () {
    Route::get('/admin/dashboard', 'index');
    Route::get('/admin/dashboard/templates', 'templates');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware('auth')->controller(MenuController::class)->group(function(){
    Route::get('/admin/menu/index', 'index');
    Route::get('/admin/menu/list', 'list');
    Route::get('/admin/menu/create', 'create');
    Route::get('/admin/menu/edit/{id}', 'edit');
    Route::post('/admin/menu/store', 'store');
    Route::post('/admin/menu/update', 'update');
    Route::get('/admin/menu/destroy/{id}', 'destroy');
    Route::get('/admin/menu/loadmenu/{parent}/{role_id}', 'loadmenu');
});

Route::middleware('auth')->controller(RoleController::class)->group(function(){
    Route::get('/admin/role/index', 'index');
    Route::get('/admin/role/list', 'list');
    Route::get('/admin/role/create', 'create');
    Route::get('/admin/role/edit/{id}', 'edit');
    Route::post('/admin/role/store', 'store');
    Route::post('/admin/role/update', 'update');
    Route::get('/admin/role/delete/{id}', 'destroy');
});

Route::middleware('auth')->controller(MenuAccessController::class)->group(function(){
    Route::get('/admin/menuaccess/index', 'index');
    Route::get('/admin/menuaccess/list', 'list');
    Route::get('/admin/menuaccess/access/{id}', 'access');
    Route::get('/admin/menuaccess/listaccess/{id}', 'listaccess');
    Route::post('/admin/menuaccess/changeaccess', 'changeaccess');
    Route::get('/admin/menuaccess/permission/{id}', 'permission');
    Route::get('/admin/menuaccess/listpermission/{id}', 'listpermission');
    Route::post('/admin/menuaccess/changepermission', 'changepermission');
});
