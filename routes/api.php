<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AuthStudentController;
use App\Http\Controllers\AuthTrainerController;
use App\Http\Controllers\CRUDStudentController;
use App\Http\Controllers\CRUDTrainerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CRUDEmployeeController;
use App\Http\Controllers\AuthSecretaryController;
use App\Http\Controllers\CourseSectionController;
use App\Http\Controllers\FunctionAdminController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\FunctionSecretaryController;
use App\Http\Controllers\ReportController;

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

// Department routes for admin
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin'], function () {
    Route::apiResource('departments', DepartmentController::class)->except(['update']);
    Route::post('departments/{id}', [DepartmentController::class, 'update']);
    Route::apiResource('courses', CourseController::class)->except(['update']);
    Route::post('courses/{id}', [CourseController::class, 'update']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
    Route::apiResource('complaints', ComplaintController::class);
    Route::apiResource('reports', ReportController::class)->except(['store', 'update']);
    Route::get('reports/secretary/{secretaryId}', [ReportController::class, 'getBySecretary']);
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

// Department routes for students (read-only)
Route::group(['middleware' => ['api','auth:student','transaction'],'prefix' => 'student'], function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
    Route::get('/complaints', [ComplaintController::class, 'studentComplaints']);
    Route::post('/complaints', [ComplaintController::class, 'store']);
});




Route::group(['middleware' => ['api','auth:student'],'prefix' => 'student/reservation'], function () {
    Route::post('/createReservation/{section_id}', [ReservationController::class, 'CreateReservation']);
    Route::post('/cancelReservation/{reservation_id}', [ReservationController::class, 'CancelReservation']);
});


################################## SECRETARY  APIs ########################################



Route::group(['middleware' => 'api','prefix' => 'auth/secretary'], function () {
    Route::post('/verificationEmail', [AuthSecretaryController::class, 'VerificationEmail']);
    Route::post('/login', [AuthSecretaryController::class, 'Login']);
    Route::post('/logout', [AuthSecretaryController::class, 'Logout'])->middleware('auth:secretary');
    Route::post('/forgotPassword',[ResetPasswordController::class,'ForgotPassword']);
    Route::post('/passwordReset',[ResetPasswordController::class,'PasswordReset']);
    
});


Route::group(['middleware' => ['api','auth:secretary'],'prefix' => 'secretary'], function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::apiResource('courses', CourseController::class)->except(['update']);
    Route::post('courses/{id}', [CourseController::class, 'update']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
    Route::apiResource('reports', ReportController::class)->except(['index', 'destroy', 'update']);
    Route::post('reports/{id}', [ReportController::class, 'update']);
    Route::get('my-reports', [ReportController::class, 'getBySecretary']);
});

Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary'], function () {
    Route::post('/student/registrationStudent', [FunctionSecretaryController::class, 'StudentRegistration']);
    Route::post('/trainer/trainerRegistration', [FunctionSecretaryController::class, 'TrainerRegistration']);

});


Route::group(['middleware' => ['api','Auth_admin_or_secretary','transaction'],'prefix' => 'secretary/student'], function () {
    Route::get('/showAllStudent', [CRUDStudentController::class, 'ShowAllStudent']);
    Route::get('/showStudentById/{studentId}', [CRUDStudentController::class, 'ShowStudentById']);
    Route::post('/updateStudent/{studentId}', [CRUDStudentController::class, 'UpdateStudent']);
    Route::get('/searchStudent/{querySearch}', [CRUDStudentController::class, 'SearchStudent']);
    Route::post('/deleteStudent/{studentId}', [CRUDStudentController::class, 'DeleteStudent']);

});


Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary/trainer'], function () {
    Route::get('/showAllTrainer', [CRUDTrainerController::class, 'ShowAllTrainer']);
    Route::get('/showTrainerById/{trainerId}', [CRUDTrainerController::class, 'ShowTrainerById']);
    Route::post('/updateTrainer/{trainerId}', [CRUDTrainerController::class, 'UpdateTrainer']);
    Route::get('/searchTrainer/{querySearch}', [CRUDTrainerController::class, 'SearchTrainer']);
    Route::post('/deleteTrainer/{trainerId}', [CRUDTrainerController::class, 'DeleteTrainer']);

});


Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary/section'], function () {
    Route::post('/createCourseSection', [CourseSectionController::class, 'CreateCourseSection']);
    Route::post('/updateCourseSection/{sectionId}', [CourseSectionController::class, 'UpdateCourseSection']);
    Route::post('/deleteCourseSection/{sectionId}', [CourseSectionController::class, 'DeleteCourseSection']);
    Route::get('/showAllCourseSection/{courseId}', [CourseSectionController::class, 'ShowAllCourseSection']);
    Route::get('/ShowByIdCourseSection/{sectionId}', [CourseSectionController::class, 'ShowByIdCourseSection']);
    Route::post('/registerStudentToSection', [CourseSectionController::class, 'RegisterStudentToSection']);
    Route::get('/getStudentsInSection/{sectionId}', [CourseSectionController::class, 'GetStudentsInSection']);
    Route::get('/getStudentsInSectionConfirmed/{sectionId}', [CourseSectionController::class, 'GetStudentsInSectionConfirmed']);
    Route::post('/deleteStudentFromSection', [CourseSectionController::class, 'DeleteStudentFromSection']);
    Route::post('/registerTrainerToSection', [CourseSectionController::class, 'RegisterTrainerToSection']);
    Route::get('/getTrainersInSection/{sectionId}', [CourseSectionController::class, 'GetTrainersInSection']);
    Route::post('/deleteTrainerFromSection', [CourseSectionController::class, 'DeleteTrainerFromSection']);
});




Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary/reservation'], function () {
    Route::post('/confirmReservation/{reservation_id}', [ReservationController::class, 'ConfirmReservation']);
    Route::get('/showAllReservation/{section_id}', [ReservationController::class, 'ShowAllReservation']);
    Route::get('/showReservation/{reservation_id}', [ReservationController::class, 'ShowReservation']);
});



################################## TRAINER APIs ########################################

Route::group(['middleware' => 'api','prefix' => 'auth/trainer'], function () {
    Route::post('/verificationEmail', [AuthTrainerController::class, 'VerificationEmail']);
    Route::post('/login', [AuthTrainerController::class, 'Login']);
    Route::post('/logout', [AuthTrainerController::class, 'Logout'])->middleware('auth:trainer');
    Route::post('/forgotPassword',[ResetPasswordController::class,'ForgotPassword']);
    Route::post('/passwordReset',[ResetPasswordController::class,'PasswordReset']);
});

// Course routes for trainers (read-only)
Route::group(['middleware' => ['api','auth:trainer'],'prefix' => 'trainer'], function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
