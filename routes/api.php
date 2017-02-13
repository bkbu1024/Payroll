<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeTransactionFactory;
use Payroll\Factory\Model\Employee as EmployeeFactory;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/employee', function (Request $request) {
    $transaction = AddEmployeeTransactionFactory::create($request->all());
    $employee = $transaction->execute();

    return $employee;
});

Route::get('/employees', function () {
    $emp = EmployeeFactory::createEmployee();
    $employees = $emp->getAll();

    return $employees;
});