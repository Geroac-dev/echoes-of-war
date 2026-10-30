<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

//Vistas-----------------------------------------------------------------------------------
Route::get('/',                     function () { return view('app'); })->name('login');
Route::get('/csrf-reload',          function () { return response()->json(['csrfToken' => csrf_token()]); });
Route::get('/iniciar-sesion',       function () { return view('app'); });
Route::get('/ver/{slug}',           function () { return view('app'); });
Route::get('/tris',                 function () { return view('layouts.tris'); });
Route::get('/administrar',          function () { return view('app'); })->middleware('firebase.auth');

//Login-----------------------------------------------------------------------------------
Route::post('/login',               [LoginController::class, 'authenticate']);
Route::post('/logout',              [LoginController::class, 'logout'])->middleware('firebase.auth');

//User-----------------------------------------------------------------------------------
Route::get("/get-user",             [UserController::class, 'getUser']);
Route::post("/get-users",           [UserController::class, 'getUsers'])->middleware('firebase.auth');
Route::post("/save-user",           [UserController::class, 'saveUser'])->middleware('firebase.auth');
Route::post("/edit-user",           [UserController::class, 'editUser'])->middleware('firebase.auth');
Route::post("/delete-user",         [UserController::class, 'deleteUser'])->middleware('firebase.auth');
Route::post("/change-theme",        [UserController::class, 'changeTheme'])->middleware('firebase.auth');

//Categorias-----------------------------------------------------------------------------------
Route::post("/get-categorias",      [CategoriaController::class, 'getCategorias']);
Route::post("/save-categoria",      [CategoriaController::class, 'saveCategoria'])->middleware('firebase.auth');
Route::post("/edit-categoria",      [CategoriaController::class, 'editCategoria'])->middleware('firebase.auth');
Route::post("/delete-categoria",    [CategoriaController::class, 'deleteCategoria'])->middleware('firebase.auth');
Route::post("/get-contenido",       [CategoriaController::class, 'getContenido']);
Route::post("/save-contenido",      [CategoriaController::class, 'saveContenido'])->middleware('firebase.auth');
Route::post("/edit-contenido",      [CategoriaController::class, 'editContenido'])->middleware('firebase.auth');
Route::post("/delete-contenido",    [CategoriaController::class, 'deleteContenido'])->middleware('firebase.auth');
Route::post("/get-secciones",       [CategoriaController::class, 'getSecciones']);
Route::post("/save-seccion",        [CategoriaController::class, 'saveSeccion'])->middleware('firebase.auth');
Route::post("/edit-seccion",        [CategoriaController::class, 'editSeccion'])->middleware('firebase.auth');

//Rutas Invalidas-----------------------------------------------------------------------------------
Route::fallback(            function (): RedirectResponse { return redirect('/'); });