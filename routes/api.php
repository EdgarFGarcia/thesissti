<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers;
use App\Http\Controllers\adminControllers;
use App\Http\Controllers\PerCompanyControllers;
use App\Http\Controllers\AgentControllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ApiControllers::class, 'login'])->name('login');
Route::post('registerUser', [ApiControllers::class, 'registerUser']);
Route::post('companyregistration', [ApiControllers::class, 'companyregistration']);

Route::get('getUser', [ApiControllers::class, 'getUser'])->middleware('auth:api');

Route::get('agents', [ApiControllers::class, 'iscompanyadmin'])->middleware('auth:api');
Route::post('agents/{id}', [ApiControllers::class, 'addagents'])->middleware('auth:api');

Route::get('products', [adminControllers::class, 'getproducts'])->middleware('auth:api');
Route::post('products/{productid}/{companyid}', [adminControllers::class, 'addproducts'])->middleware('auth:api');
Route::get('products/{companyId}', [ApiControllers::class, 'getcompanyproducts'])->middleware('auth:api');

Route::post('products/{companyId}', [PerCompanyControllers::class, 'addproducts'])->middleware('auth:api');


Route::get('selectcompany', [AgentControllers::class, 'selectcompany'])->middleware('auth:api');
Route::post('selectcompany/{companyId}', [AgentControllers::class, 'selectthiscompany'])->middleware('auth:api');

Route::post('addtosales/{id}/{companyid}', [AgentControllers::class, 'addsales'])->middleware('auth:api');

Route::get('getmysales/{companyid}', [AgentControllers::class, 'getmysales'])->middleware('auth:api');