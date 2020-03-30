<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin','namespace' => 'Auth'],function(){
    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')->namespace('Admin')->group(function () {
	/*  Operadores */
	// nota mental :: as rotas extras devem ser declaradas antes de se declarar as rotas resources
    Route::get('/users/password', 'ChangePasswordController@showPasswordUpdateForm')->name('users.password');
	Route::put('/users/password/update', 'ChangePasswordController@passwordUpdate')->name('users.passwordupdate');
    Route::get('/users/export/csv', 'UserController@exportcsv')->name('users.export.csv');
	Route::get('/users/export/pdf', 'UserController@exportpdf')->name('users.export.pdf');
    Route::resource('/users', 'UserController');

	/* Permissões */
    Route::get('/permissions/export/csv', 'PermissionController@exportcsv')->name('permissions.export.csv');
	Route::get('/permissions/export/pdf', 'PermissionController@exportpdf')->name('permissions.export.pdf');
    Route::resource('/permissions', 'PermissionController');

    /* Perfis */
    Route::get('/roles/export/csv', 'RoleController@exportcsv')->name('roles.export.csv');
    Route::get('/roles/export/pdf', 'RoleController@exportpdf')->name('roles.export.pdf');
    Route::resource('/roles', 'RoleController');
});

/* Cargos */
Route::get('/cargos/export/csv', 'CargoController@exportcsv')->name('cargos.export.csv');
Route::get('/cargos/export/pdf', 'CargoController@exportpdf')->name('cargos.export.pdf');
Route::resource('/cargos', 'CargoController');

/* Especilidades não usado*/
Route::get('/especialidades/export/csv', 'EspecialidadeController@exportcsv')->name('especialidades.export.csv');
Route::get('/especialidades/export/pdf', 'EspecialidadeController@exportpdf')->name('especialidades.export.pdf');
Route::resource('/especialidades', 'EspecialidadeController');


Route::resource('/curriculo', 'CurriculoController')->only(['create', 'store', 'show', 'index']);


Route::get('/lista/export/csv', 'ListaController@exportcsv')->name('lista.export.csv');
Route::get('/lista/export/pdf', 'ListaController@exportpdf')->name('lista.export.pdf');
Route::get('/lista/export/pdf/{id}/individual', 'ListaController@exportpdfindividual')->name('lista.export.pdf.individual');
Route::resource('/lista', 'ListaController')->only(['index', 'show', 'destroy']);
