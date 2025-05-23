<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ExamGradeController;
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
use App\Http\Controllers\PointsManagementController;
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

################################# AUTHENTICATION ROUTES ##########################

// Admin Authentication
Route::group(['middleware' => 'api','prefix' => 'auth/admin'], function () {
    Route::post('/login', [AuthAdminController::class, 'login']);
    Route::post('/logout', [AuthAdminController::class, 'logout'])->middleware('auth:admin');
    Route::post('/refresh', [AuthAdminController::class, 'refresh']);
    Route::get('/userProfile', [AuthAdminController::class, 'userProfile']);
});

// Student Authentication
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

// Secretary Authentication
Route::group(['middleware' => 'api','prefix' => 'auth/secretary'], function () {
    Route::post('/verificationEmail', [AuthSecretaryController::class, 'VerificationEmail']);
    Route::post('/login', [AuthSecretaryController::class, 'Login']);
    Route::post('/logout', [AuthSecretaryController::class, 'Logout'])->middleware('auth:secretary');
    Route::post('/forgotPassword',[ResetPasswordController::class,'ForgotPassword']);
    Route::post('/passwordReset',[ResetPasswordController::class,'PasswordReset']);
});

// Trainer Authentication
Route::group(['middleware' => 'api','prefix' => 'auth/trainer'], function () {
    Route::post('/verificationEmail', [AuthTrainerController::class, 'VerificationEmail']);
    Route::post('/login', [AuthTrainerController::class, 'Login']);
    Route::post('/logout', [AuthTrainerController::class, 'Logout'])->middleware('auth:trainer');
    Route::post('/forgotPassword',[ResetPasswordController::class,'ForgotPassword']);
    Route::post('/passwordReset',[ResetPasswordController::class,'PasswordReset']);
});

################################# ADMIN ROUTES ##########################

// Admin Secretary Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin/secretary'], function () {
    Route::post('/registrationSecretary', [FunctionAdminController::class, 'RegistrationSecretary']);
});

// Admin Department Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin'], function () {
    Route::apiResource('departments', DepartmentController::class)->except(['update']);
    Route::post('departments/{id}', [DepartmentController::class, 'update']);
});

// Admin Course Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin'], function () {
    Route::apiResource('courses', CourseController::class)->except(['update'])->names([
        'index' => 'admin.courses.index',
        'store' => 'admin.courses.store',
        'show' => 'admin.courses.show',
        'destroy' => 'admin.courses.destroy'
    ]);
    Route::post('courses/{id}', [CourseController::class, 'update']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
});

// Admin Complaint Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin'], function () {
    Route::apiResource('complaints', ComplaintController::class);
});

// Admin Report Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin'], function () {
    Route::apiResource('reports', ReportController::class)->except(['store', 'update'])->names([
        'index' => 'admin.reports.index',
        'show' => 'admin.reports.show',
        'destroy' => 'admin.reports.destroy'
    ]);
    Route::get('reports/secretary/{secretaryId}', [ReportController::class, 'getBySecretary'])->name('admin.reports.by-secretary');
});

// Admin Points Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin/points'], function () {
    Route::get('/top-students', [PointsManagementController::class, 'getTopStudents']);
    Route::get('/top-secretaries', [PointsManagementController::class, 'getTopSecretaries']);
    Route::post('/student/{studentId}', [PointsManagementController::class, 'updateStudentPoints']);
    Route::post('/secretary/{secretaryId}', [PointsManagementController::class, 'updateSecretaryPoints']);
});

// Admin Gift Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin'], function () {
    Route::get('gifts', [GiftController::class, 'index']);
    Route::post('gifts', [GiftController::class, 'store']);
    Route::get('gifts/{id}', [GiftController::class, 'show']);
    Route::post('gifts/{id}', [GiftController::class, 'update']);
    Route::delete('gifts/{id}', [GiftController::class, 'destroy']);
});

// Admin Advertisement Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin'], function () {
    Route::apiResource('ads', AdController::class)->except(['update']);
    Route::post('ads/{id}', [AdController::class, 'update']);
});

// Admin Employee Management
Route::group(['middleware' => ['api','auth:admin','transaction'],'prefix' => 'admin/employee'], function () {
    Route::post('/addEmployee', [CRUDEmployeeController::class, 'AddEmployee']);
    Route::get('/showAllEmployees', [CRUDEmployeeController::class, 'ShowAllEmployees']);
    Route::get('/showEmployeeById/{employeeId}', [CRUDEmployeeController::class, 'ShowEmployeeById']);
    Route::post('/updateEmployee/{employeeId}', [CRUDEmployeeController::class, 'UpdateEmployee']);
    Route::get('/searchEmployee/{querySearch}', [CRUDEmployeeController::class, 'SearchEmployee']);
    Route::post('/deleteEmployee/{employeeId}', [CRUDEmployeeController::class, 'DeleteEmployee']);
});

################################# STUDENT ROUTES ##########################

// Student Department & Course Access
Route::group(['middleware' => ['api','auth:student','transaction'],'prefix' => 'student'], function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
});

// Student Complaint Management
Route::group(['middleware' => ['api','auth:student','transaction'],'prefix' => 'student'], function () {
    Route::get('/complaints', [ComplaintController::class, 'studentComplaints']);
    Route::post('/complaints', [ComplaintController::class, 'store']);
});

// Student Gift Access
Route::group(['middleware' => ['api','auth:student','transaction'],'prefix' => 'student'], function () {
    Route::get('/gifts', [GiftController::class, 'studentGifts']);
});

// Student Reservation Management
Route::group(['middleware' => ['api','auth:student'],'prefix' => 'student/reservation'], function () {
    Route::post('/createReservation/{section_id}', [ReservationController::class, 'CreateReservation']);
    Route::post('/cancelReservation/{reservation_id}', [ReservationController::class, 'CancelReservation']);
});

################################# SECRETARY ROUTES ##########################

// Secretary Department & Course Management
Route::group(['middleware' => ['api','auth:secretary'],'prefix' => 'secretary'], function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::apiResource('courses', CourseController::class)->except(['update'])->names([
        'index' => 'secretary.courses.index',
        'store' => 'secretary.courses.store',
        'show' => 'secretary.courses.show',
        'destroy' => 'secretary.courses.destroy'
    ]);
    Route::post('courses/{id}', [CourseController::class, 'update']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
});

// Secretary Report Management
Route::group(['middleware' => ['api','auth:secretary'],'prefix' => 'secretary'], function () {
    Route::apiResource('reports', ReportController::class)->except(['index', 'destroy', 'update'])->names([
        'store' => 'secretary.reports.store',
        'show' => 'secretary.reports.show'
    ]);
    Route::post('reports/{id}', [ReportController::class, 'update'])->name('secretary.reports.update');
    Route::get('my-reports', [ReportController::class, 'getBySecretary'])->name('secretary.reports.my-reports');
});

// Secretary Gift Access
Route::group(['middleware' => ['api','auth:secretary'],'prefix' => 'secretary'], function () {
    Route::get('/gifts', [GiftController::class, 'secretaryGifts']);
});

// Secretary Student Management
Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary'], function () {
    Route::post('/student/registrationStudent', [FunctionSecretaryController::class, 'StudentRegistration']);
});

// Secretary Trainer Management
Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary'], function () {
    Route::post('/trainer/trainerRegistration', [FunctionSecretaryController::class, 'TrainerRegistration']);
});

// Secretary Student CRUD Operations
Route::group(['middleware' => ['api','Auth_admin_or_secretary','transaction'],'prefix' => 'secretary/student'], function () {
    Route::get('/showAllStudent', [CRUDStudentController::class, 'ShowAllStudent']);
    Route::get('/showStudentById/{studentId}', [CRUDStudentController::class, 'ShowStudentById']);
    Route::post('/updateStudent/{studentId}', [CRUDStudentController::class, 'UpdateStudent']);
    Route::get('/searchStudent/{querySearch}', [CRUDStudentController::class, 'SearchStudent']);
    Route::post('/deleteStudent/{studentId}', [CRUDStudentController::class, 'DeleteStudent']);
});

// Secretary Trainer CRUD Operations
Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary/trainer'], function () {
    Route::get('/showAllTrainer', [CRUDTrainerController::class, 'ShowAllTrainer']);
    Route::get('/showTrainerById/{trainerId}', [CRUDTrainerController::class, 'ShowTrainerById']);
    Route::post('/updateTrainer/{trainerId}', [CRUDTrainerController::class, 'UpdateTrainer']);
    Route::get('/searchTrainer/{querySearch}', [CRUDTrainerController::class, 'SearchTrainer']);
    Route::post('/deleteTrainer/{trainerId}', [CRUDTrainerController::class, 'DeleteTrainer']);
});

// Secretary Course Section Management
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

// Secretary Reservation Management
Route::group(['middleware' => ['api','auth:secretary','transaction'],'prefix' => 'secretary/reservation'], function () {
    Route::post('/confirmReservation/{reservation_id}', [ReservationController::class, 'ConfirmReservation']);
    Route::get('/showAllReservation/{section_id}', [ReservationController::class, 'ShowAllReservation']);
    Route::get('/showReservation/{reservation_id}', [ReservationController::class, 'ShowReservation']);
});

################################# TRAINER ROUTES ##########################

// Trainer Course Access
Route::group(['middleware' => ['api','auth:trainer'],'prefix' => 'trainer'], function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
});

################################# FILE ROUTES ##########################

//  
Route::group(['middleware' => ['api','auth:trainer'],'prefix' => 'trainer/file'], function () {
   Route::post('/uploadFile', [FileController::class, 'UploadFile']);
   Route::post('/updateFile', [FileController::class, 'UpdateFile']);
   Route::post('/deleteFile/{file_Id}', [FileController::class, 'DeleteFile']);
});

Route::group(['middleware' =>'api','prefix' => 'file'], function () {
   Route::get('/showAllFileInSection/{course_section_id}', [FileController::class, 'ShowAllFileInSection']);
   Route::get('/showFileById/{file_Id}', [FileController::class, 'ShowFileById']);
});

################################# FORUM ROUTES ##########################

// Forum Routes (for students and trainers)
Route::group(['middleware' => ['api', 'auth:student,trainer'], 'prefix' => 'forum'], function () {
    Route::get('sections/{section}/questions/stats', [ForumController::class, 'getQuestionsWithStats']);
    Route::get('questions/{question}/likes', [ForumController::class, 'getQuestionLikes']);
    Route::get('questions/{question}/answer-likes', [ForumController::class, 'getAnswerLikes']);
    Route::get('sections/{section}/questions', [ForumController::class, 'getSectionQuestions']);
    Route::post('sections/{section}/questions', [ForumController::class, 'createQuestion']);
    Route::post('questions/{question}/answers', [ForumController::class, 'createAnswer']);
    Route::delete('questions/{question}', [ForumController::class, 'deleteQuestion']);
    Route::post('questions/{question}/like', [ForumController::class, 'toggleLike']);
    Route::post('answers/{answer}/accept', [ForumController::class, 'markAnswerAsAccepted']);
});

################################# EXAM GRADES ROUTES ##########################

// Exam Grades Management
Route::group(['middleware' => 'api', 'prefix' => 'exam-grades'], function () {
    Route::get('/', [ExamGradeController::class, 'index']);
    Route::post('/', [ExamGradeController::class, 'store']);
    Route::get('/{id}', [ExamGradeController::class, 'show']);
    Route::put('/{id}', [ExamGradeController::class, 'update']);
    Route::delete('/{id}', [ExamGradeController::class, 'destroy']);
    
    // Helper routes
    Route::get('/student/{studentId}', [ExamGradeController::class, 'getStudentGrades']);
    Route::get('/section/{sectionId}', [ExamGradeController::class, 'getSectionGrades']);
    Route::get('/trainer/{trainerId}', [ExamGradeController::class, 'getTrainerGrades']);
    Route::get('/section/{sectionId}/statistics', [ExamGradeController::class, 'getSectionStatistics']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
