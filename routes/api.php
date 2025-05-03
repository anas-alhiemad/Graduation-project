<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthStudentController;
use App\Http\Controllers\AuthTrainerController;
use App\Http\Controllers\CRUDStudentController;
use App\Http\Controllers\CRUDTrainerController;
use App\Http\Controllers\CRUDEmployeeController;
use App\Http\Controllers\AuthSecretaryController;
use App\Http\Controllers\FunctionAdminController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\FunctionSecretaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

################################# ADMIN APIs ##########################

Route::group(['middleware' => 'api','prefix' => 'auth/admin'], function () {
    Route::post('/login', [AuthAdminController::class, 'login']);
    Route::post('/logout', [AuthAdminController::class, 'logout'])->middleware('auth:admin');
    Route::post('/refresh', [AuthAdminController::class, 'refresh']);
    Route::get('/userProfile', [AuthAdminController::class, 'userProfile']);
});


Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin/secretary'], function () {
    Route::post('/registrationSecretary', [FunctionAdminController::class, 'RegistrationSecretary']);

});

Route::group(['middleware' => ['api','auth:admin','transaction'],'prefix' => 'admin/employee'], function () {
    Route::post('/addEmployee', [CRUDEmployeeController::class, 'AddEmployee']);
    Route::get('/showAllEmployees', [CRUDEmployeeController::class, 'ShowAllEmployees']);
    Route::get('/showEmployeeById/{employeeId}', [CRUDEmployeeController::class, 'ShowEmployeeById']);
    Route::post('/updateEmployee/{employeeId}', [CRUDEmployeeController::class, 'UpdateEmployee']);
    Route::get('/searchEmployee/{querySearch}', [CRUDEmployeeController::class, 'SearchEmployee']);
    Route::post('/deleteEmployee/{employeeId}', [CRUDEmployeeController::class, 'DeleteEmployee']);

});

################################# STUDENT APIs ##########################


Route::group(['middleware' => 'api','prefix' => 'auth/student'], function () {
    Route::post('/login', [AuthStudentController::class, 'Login']);
    Route::post('/register', [AuthStudentController::class, 'Register']);
    Route::post('/logout', [AuthStudentController::class, 'Logout']);
    Route::post('/forgotPassword',[ResetPasswordController::class,'ForgotPassword']);
    Route::post('/passwordReset',[ResetPasswordController::class,'PasswordReset']);
    Route::post('/refreshToken', [AuthStudentController::class, 'RefreshToken']);
    Route::get('/studentProfile', [AuthStudentController::class, 'GetStudentProfile']);
    Route::post('/verificationEmail', [AuthStudentController::class, 'VerificationEmail']);
});



################################## SECRETARY  APIs ########################################



Route::group(['middleware' => 'api','prefix' => 'auth/secretary'], function () {
    Route::post('/verificationEmail', [AuthSecretaryController::class, 'VerificationEmail']);
    Route::post('/login', [AuthSecretaryController::class, 'Login']);
    Route::post('/logout', [AuthSecretaryController::class, 'Logout'])->middleware('auth:secretary');
    Route::post('/forgotPassword',[ResetPasswordController::class,'ForgotPassword']);
    Route::post('/passwordReset',[ResetPasswordController::class,'PasswordReset']);
    
});


Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary'], function () {
    Route::post('/student/registrationStudent', [FunctionSecretaryController::class, 'StudentRegistration']);
    Route::post('/trainer/trainerRegistration', [FunctionSecretaryController::class, 'TrainerRegistration']);

});


Route::group(['middleware' => ['api','auth:secretary,admin','transaction'],'prefix' => 'secretary/student'], function () {
    Route::get('/showAllStudent', [CRUDStudentController::class, 'ShowAllStudent']);
    Route::get('/showStudentById/{studentId}', [CRUDStudentController::class, 'ShowStudentById']);
    Route::post('/updateStudent/{studentId}', [CRUDStudentController::class, 'UpdateStudent']);
    Route::get('/searchStudent/{querySearch}', [CRUDStudentController::class, 'SearchStudent']);
    Route::post('/deleteStudent/{studentId}', [CRUDStudentController::class, 'DeleteStudent']);

});


Route::group(['middleware' => ['api','auth:secretary,admin','transaction'],'prefix' => 'secretary/trainer'], function () {
    Route::get('/showAllTrainer', [CRUDTrainerController::class, 'ShowAllTrainer']);
    Route::get('/showTrainerById/{trainerId}', [CRUDTrainerController::class, 'ShowTrainerById']);
    Route::post('/updateTrainer/{trainerId}', [CRUDTrainerController::class, 'UpdateTrainer']);
    Route::get('/searchTrainer/{querySearch}', [CRUDTrainerController::class, 'SearchTrainer']);
    Route::post('/deleteTrainer/{trainerId}', [CRUDTrainerController::class, 'DeleteTrainer']);

});


################################## TRAINER APIs ########################################

Route::group(['middleware' => 'api','prefix' => 'auth/trainer'], function () {
    Route::post('/verificationEmail', [AuthTrainerController::class, 'VerificationEmail']);
    Route::post('/login', [AuthTrainerController::class, 'Login']);
    Route::post('/logout', [AuthTrainerController::class, 'Logout'])->middleware('auth:trainer');
    Route::post('/forgotPassword',[ResetPasswordController::class,'ForgotPassword']);
    Route::post('/passwordReset',[ResetPasswordController::class,'PasswordReset']);
});





















































Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
