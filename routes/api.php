<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Payroll\Employee;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeTransactionFactory;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\Transaction\Change\Composition as CompositionFactory;

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

// Employee

Route::get('/employees', function () {
    $emp = EmployeeFactory::createEmployee();
    $employees = $emp->getAll();

    return $employees;
});

Route::get('/employee/{employee}', function (Employee $employee) {
    return $employee;
});

Route::post('/employee', function (Request $request) {
    $transaction = AddEmployeeTransactionFactory::create($request->all());
    $employee = $transaction->execute();

    return $employee;
});

Route::delete('/employee/{employee}', function (Employee $employee) {
    $original = $employee;
    $employee->delete();

    return $original;
});

Route::put('employee/{employee}', function (Request $request, Employee $employee) {
    $transaction = CompositionFactory::create($employee, $request->all());
    $transaction->execute();

    $employee->update($request->all());

    return $employee;
});