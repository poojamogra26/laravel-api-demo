<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\UserAuth\AuthController;
use App\Http\Controllers\Api\AdministratorAuth\AuthController;
use App\Http\Controllers\Api\AdministratorAuth\AccountController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AdministratorController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\EmailTemplateController;
use App\Http\Controllers\Api\UserCompanyController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::post('register',[AuthController::class,'register']);
//Route::post('users/login',[AuthController::class,'login']);
//Route::get('users/logout/{id}',[AuthController::class,'logout']);
Route::post('login',[AuthController::class,'login']);
Route::group([ 'middleware' => 'jwt.verify'], function () {
    Route::get('administrator/logout/{id}',[AuthController::class,'logout']);
    Route::get('account/user',[AuthController::class,'loggedInUser']);

    Route::post('account/change-password',[AccountController::class,'changePassword']);
    Route::post('account/change-avatar',[AccountController::class,'editAvatar']);
    Route::post('account/edit-profile',[AccountController::class,'editProfile']);

    Route::post('users/add',[UserController::class,'store'])->middleware('can:add-user');
    Route::get('users',[UserController::class,'index'])->middleware('can:user-listing');
    Route::get('users/{id}',[UserController::class,'show'])->middleware('can:view-user');
    Route::get('users/edit/{id}',[UserController::class,'edit'])->middleware('can:edit-user');
    Route::post('users/update/{id}',[UserController::class,'update'])->middleware('can:edit-user');
    Route::delete('users/delete/{id}',[UserController::class,'destroy'])->middleware('can:delete-user');
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('user/company/edit/{id}',[UserController::class,'companyedit']);
    Route::post('users/edit/personalinfo/{id}',[UserController::class,'personalInfoEdit']);

    // Clients Route
    Route::post('clients/add/{id}',[ClientController::class,'store'])->middleware('can:add-client');
    Route::get('clients/{user_id}',[ClientController::class,'index'])->middleware('can:client-listing');
    Route::get('clients/{user_id}/{id}',[ClientController::class,'show'])->middleware('can:view-client');
    Route::post('clients/update/{user_id}/{id}',[ClientController::class,'update'])->middleware('can:edit-client');
    Route::delete('clients/delete/{id}',[ClientController::class,'destroy'])->middleware('can:delete-client');

    // Plans Route
    Route::post('plans/add',[PlanController::class,'store'])->middleware('can:add-plan');
    Route::get('plans',[PlanController::class,'index'])->middleware('can:plan-listing');
    Route::get('plans/{id}',[PlanController::class,'show'])->middleware('can:view-plan');
    Route::post('plans/update/{id}',[PlanController::class,'update'])->middleware('can:edit-plan');
    Route::delete('plans/delete/{id}',[PlanController::class,'destroy'])->middleware('can:delete-plan');

    // email-templates Route
    Route::post('email-templates/add',[EmailTemplateController::class,'store'])->middleware('can:add-emailtemplate');
    Route::get('email-templates',[EmailTemplateController::class,'index'])->middleware('can:emailtemplate-listing');
    Route::get('email-templates/{id}',[EmailTemplateController::class,'show'])->middleware('can:view-emailtemplate');
    Route::post('email-templates/update/{id}',[EmailTemplateController::class,'update'])->middleware('can:edit-emailtemplate');
    Route::delete('email-templates/delete/{id}',[EmailTemplateController::class,'destroy'])->middleware('can:delete-emailtemplate');

    // Administrator routes
    Route::post('/administrator/add',[AdministratorController::class,'store'])->middleware('can:add-administrator');
    Route::get('/administrators',[AdministratorController::class,'index'])->middleware('can:listing-administrator');
    Route::get('administrator/{id}',[AdministratorController::class,'show'])->middleware('can:view-administrator');
    Route::get('administrator/edit/{id}',[AdministratorController::class,'edit'])->middleware('can:edit-administrator');
    Route::post('administrator/update/{id}',[AdministratorController::class,'update'])->middleware('can:edit-administrator');
    Route::delete('administrator/delete/{id}',[AdministratorController::class,'destroy'])->middleware('can:delete-administrator');

    Route::post('/edit/permission/{id}',[AdministratorController::class,'editpermission'])->middleware('can:edit-permission');

    // Role routes
    Route::get('roles',[RoleController::class,'index'])->middleware('can:role-listing');
    Route::post('role/add',[RoleController::class,'store'])->middleware('can:role-add');
    Route::get('role/edit/{id}',[RoleController::class,'edit'])->middleware('can:role-edit');
    Route::post('role/update/{id}',[RoleController::class,'update'])->middleware('can:role-edit');
    Route::delete('role/delete/{id}',[RoleController::class,'destroy'])->middleware('can:role-delete');
    //Route::get('role/manage-role-permission/{id}',[RoleController::class,'managerolepermission'])->middleware('can:manage-role-permission');
    Route::get('role/manage-role-permission/{id}',[RoleController::class,'managerolepermission']);
    Route::get('role/permisssion/{id}',[RoleController::class,'rolepermission'])->middleware('can:role-permission');

    //Dashboard routes
    Route::get('dashboard/counters',[DashboardController::class,'counters']);
});


