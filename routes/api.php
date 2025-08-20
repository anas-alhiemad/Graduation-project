<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ExamGradeController;
use App\Http\Controllers\SectionQAController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AuthStudentController;
use App\Http\Controllers\AuthTrainerController;
use App\Http\Controllers\CRUDStudentController;
use App\Http\Controllers\CRUDTrainerController;
use App\Http\Controllers\DisplayStudentService;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SavedCourseController;
use App\Http\Controllers\CRUDEmployeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthSecretaryController;
use App\Http\Controllers\CourseSectionController;
use App\Http\Controllers\DisplaySecretaryService;
use App\Http\Controllers\FunctionAdminController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SectionRatingController;
use App\Http\Controllers\StudentPointsController;
use App\Http\Controllers\TrainerRatingController;
use App\Http\Controllers\SecretaryPointsController;
use App\Http\Controllers\PointsManagementController;
use App\Http\Controllers\FunctionSecretaryController;
use App\Http\Controllers\SessionAttendanceController;
use App\Http\Controllers\CourseRecommendationController;
use App\Http\Controllers\SectionStudentSearchController;
use App\Http\Controllers\TrainerStatisticsController;
use App\Http\Controllers\SectionStatisticsController;
use App\Http\Controllers\StudentStatisticsController;
use App\Http\Controllers\CourseStatisticsController;
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
    //Route::get('/departments/{departmentId}', [CourseController::class, 'getByDepartment']);
    Route::get('/departments/{departmentId}/courses', [CourseController::class, 'getByDepartment']);

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

// Admin AdS Management
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin'], function () {
    Route::get('ads/active', [AdController::class, 'active']);
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

// Admin View Trainers Only &SectionStatistic&TrainerStatistics
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin/trainer'], function () {
    Route::get('/showAllTrainer', [CRUDTrainerController::class, 'ShowAllTrainer']);
    Route::get('/showTrainerById/{trainerId}', [CRUDTrainerController::class, 'ShowTrainerById']);
    Route::get('/searchTrainer/{querySearch}', [CRUDTrainerController::class, 'SearchTrainer']);
    Route::get('/indexTrainerWithCourse', [CourseSectionController::class, 'IndexTrainerWithCourse']);
    Route::get('/getTrainersInSection/{sectionId}', [CourseSectionController::class, 'GetTrainersInSection']);
    Route::get('/statistics', [TrainerStatisticsController::class, 'index']);
    Route::get('/section-statistics', [SectionStatisticsController::class, 'index']);
});

// Admin View Students Only
Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'admin/student'], function () {
    Route::get('/showAllStudent', [CRUDStudentController::class, 'ShowAllStudent']);
    Route::get('/showStudentById/{studentId}', [CRUDStudentController::class, 'ShowStudentById']);
    Route::get('/searchStudent/{querySearch}', [CRUDStudentController::class, 'SearchStudent']);
});

################################# STUDENT ROUTES ##########################

// Student Department & Course Access
Route::group(['middleware' => ['api','auth:student','transaction'],'prefix' => 'student'], function () {
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/searchCourses/{query}', [CourseController::class, 'search']);
    Route::get('/my-courses', [CourseSectionController::class, 'getStudentCourses']);
    Route::get('/getMyCourseIsFinished', [CourseSectionController::class, 'GetCourseIsFinished']);
    Route::get('/departments/{departmentId}', [CourseController::class, 'getByDepartment']);
    Route::get('/points', [StudentPointsController::class, 'getPoints']);
    Route::get('/showAllCourseSection/{courseId}', [CourseSectionController::class, 'ShowAllCourseSection']);
    Route::get('/departments/{departmentId}/courses', [CourseController::class, 'getByDepartment']);
     Route::get('/recommendations', [CourseRecommendationController::class, 'getRecommendations']);
      Route::get('/recommended-from-saved', [CourseRecommendationController::class, 'getRecommendationsFromSaved']);

    });

// Student Complaint Management
Route::group(['middleware' => ['api','auth:student','transaction'],'prefix' => 'student'], function () {
    Route::get('/complaints', [ComplaintController::class, 'studentComplaints']);
    Route::post('/complaints', [ComplaintController::class, 'store']);
});

// Student Gift Access
Route::group(['middleware' => ['api','auth:student','transaction'],'prefix' => 'student'], function () {
    Route::get('/gifts', [GiftController::class, 'studentGifts']);
    Route::get('/ads/active', [AdController::class, 'active']);
});

// Student Reservation Management
Route::group(['middleware' => ['api','auth:student'],'prefix' => 'student/reservation'], function () {
    Route::post('/createReservation/{section_id}', [ReservationController::class, 'CreateReservation']);
    Route::post('/cancelReservation/{reservation_id}', [ReservationController::class, 'CancelReservation']);
});


Route::group(['middleware' => ['api','auth:student'],'prefix' => 'student/events'], function () {
    Route::get('/getMyScheduleByDay/{name_day}', [CourseSectionController::class, 'GetMyScheduleByDayStudent']);
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
    Route::get('/departments/{departmentId}/courses', [CourseController::class, 'getByDepartment']);
    Route::get('/points', [SecretaryPointsController::class, 'getPoints']);
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
    Route::post('/changeStatusCourseSection/{sectionId}', [CourseSectionController::class, 'ChangeStatusCourseSection']);
   
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
   
    Route::get('/getStudentArchive/{studentId}', [CourseSectionController::class, 'getStudentArchiveBySecretary']);
    Route::get('/getTrainerArchive/{trainerId}', [CourseSectionController::class, 'getTrainerArchiveBySecretary']);
});



Route::group(['middleware' => ['api','Auth_student_or_secretary','transaction'],'prefix' => 'section'], function () {
    Route::get('/ShowByIdCourseSection/{sectionId}', [CourseSectionController::class, 'ShowByIdCourseSection']);
    Route::get('/showAllCourseSectionIsPending/{courseId}', [CourseSectionController::class, 'ShowAllCourseSectionPending']);
});

Route::group(['middleware' => ['api','Auth_student_or_secretary','transaction'],'prefix' => 'section'], function () {

    Route::get('/showAllCourseSectionInProgress/{courseId}', [CourseSectionController::class, 'ShowAllCourseSectionInProgress']);
    Route::get('/showAllCourseSectionFinished/{courseId}', [CourseSectionController::class, 'ShowAllCourseSectionFinished']);
});

Route::group(['middleware' => ['api','Auth_student_or_secretary','transaction'],'prefix' => 'trainer'], function () {
    Route::get('/indexTrainerWithCourse', [CourseSectionController::class, 'IndexTrainerWithCourse']);
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
    Route::get('/my-courses', [CourseSectionController::class, 'getTrainerCourses']);
    Route::get('/departments/{departmentId}', [CourseController::class, 'getByDepartment']);
    Route::get('/getStudentsInSection/{sectionId}', [CourseSectionController::class, 'GetStudentsInSection']);

});

################################# FILE ROUTES ##########################

//  
Route::group(['middleware' => ['api','auth:trainer'],'prefix' => 'trainer/file'], function () {
   Route::post('/uploadFile', [FileController::class, 'UploadFile']);
   Route::post('/updateFile', [FileController::class, 'UpdateFile']);
   Route::post('/deleteFile/{file_Id}', [FileController::class, 'DeleteFile']);
});

Route::group(['middleware' => ['api','Auth_student_or_secretary'],'prefix' => 'file'], function () {
   Route::get('/showAllFileInSection/{course_section_id}', [FileController::class, 'ShowAllFileInSection']);
   Route::get('/showFileById/{file_Id}', [FileController::class, 'ShowFileById']);

});

################################# Quiz ROUTES ##########################
Route::group(['middleware' => ['api','auth:trainer'],'prefix' => 'trainer/quiz'], function () {
   Route::post('/createQuiz', [QuizController::class, 'CreateQuiz']);
   Route::post('/updateTitle/{quiz_id}', [QuizController::class, 'UpdateTitle']);
   Route::post('/updateQuestion/{question_id}', [QuizController::class, 'UpdateQuestion']);
   Route::post('/deleteQuiz/{quiz_id}', [QuizController::class, 'DeleteQuiz']);
   Route::post('/deleteQuizQuestion/{question_id}', [QuizController::class, 'DeleteQuizQuestion']);
});

Route::group(['middleware' => ['api','Auth_student_or_secretary'],'prefix' => 'quiz'], function () {
    Route::get('/listQuizzesBySectionId/{course_section_id}', [QuizController::class, 'ListQuizzesBySectionId']);
    Route::get('/showQuizById/{quiz_id}', [QuizController::class, 'ShowQuizById']);
    Route::post('/answerQuestion/{option_id}', [QuizController::class, 'answerQuestion']);

});

################################# Quiz ROUTES ##########################

Route::group(['middleware' => ['api','auth:trainer'],'prefix' => 'trainer/events'], function () {
    Route::get('/getMyScheduleByDay/{name_day}', [CourseSectionController::class, 'GetMyScheduleByDayTrainer']);
});


################################# TASK ROUTES ##########################

Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'secretary/task'], function () {
    Route::post('/createTask', [TaskController::class, 'CreateTask']);
    Route::get('/showTasksForAdmin', [TaskController::class, 'ShowTasksForAdmin']);
    Route::get('/showTasksByIdSecretary/{secretary_id}', [TaskController::class, 'ShowTasksByIdSecretary']);
    Route::post('/updateTask/{task_id}', [TaskController::class, 'UpdateTask']);
    Route::post('/deleteTask/{task_id}', [TaskController::class, 'DeleteTask']);
});

Route::group(['middleware' => ['api','auth:secretary'],'prefix' => 'secretary/task'], function () {
    Route::post('/changeStatusTask/{task_id}', [TaskController::class, 'ChangeStatus']);
    Route::get('/showMyTask', [TaskController::class, 'ShowMyTask']);
});


################################# Notification ROUTES ##########################

Route::group(['middleware' => ['api','auth:student'],'prefix' => 'notifications'], function () {
    Route::get('/indexNotifications', [NotificationController::class, 'IndexNotifications']);
});

Route::group(['middleware' => ['api','auth:secretary'],'prefix' => 'notifications'], function () {
    Route::get('/indexNotificationsSecretary', [NotificationController::class, 'IndexNotifications']);
});

Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'notifications'], function () {
    Route::get('/indexNotificationsAdmin', [NotificationController::class, 'IndexNotifications']);
});

Route::group(['middleware' => ['api','auth:trainer'],'prefix' => 'notifications'], function () {
    Route::get('/indexNotificationsTrainer', [NotificationController::class, 'IndexNotifications']);
});


// Route::group(['middleware' => ['api','auth:admin'],'prefix' => 'notifications'], function () {
//     Route::get('/indexNotificationsAdmin', [NotificationController::class, 'IndexNotifications']);
// });

################################# FORUM ROUTES ##########################

// Forum QA Routes
Route::group(['middleware' => ['api', 'auth:trainer,student'], 'prefix' => 'forum'], function () {
    // Question routes
    Route::get('sections/{sectionId}/questions', [SectionQAController::class, 'getSectionQuestions']);
    Route::post('questions', [SectionQAController::class, 'createQuestion']);
    Route::put('questions/{questionId}', [SectionQAController::class, 'updateQuestion']);
    Route::delete('questions/{questionId}', [SectionQAController::class, 'deleteQuestion']);
    Route::post('questions/{questionId}/like', [SectionQAController::class, 'likeQuestion']);
    Route::delete('questions/{questionId}/like', [SectionQAController::class, 'unlikeQuestion']);

    // Answer routes accessible by trainer and student
    Route::get('questions/{questionId}/answers', [SectionQAController::class, 'getQuestionAnswers']);
    Route::post('answers', [SectionQAController::class, 'createAnswer']);
    Route::put('answers/{answerId}', [SectionQAController::class, 'updateAnswer']);
    Route::delete('answers/{answerId}', [SectionQAController::class, 'deleteAnswer']);
    Route::post('answers/{answerId}/like', [SectionQAController::class, 'likeAnswer']);
    Route::delete('answers/{answerId}/like', [SectionQAController::class, 'unlikeAnswer']);

    // Group for trainer-only routes
    Route::group(['middleware' => ['auth:trainer']], function () {
        Route::post('answers/{answerId}/accept', [SectionQAController::class, 'acceptAnswer']);
        Route::delete('answers/{answerId}/accept', [SectionQAController::class, 'unacceptAnswer']);
    });
});



// Trainer Rating - Student (Create rating)
Route::group(['middleware' => ['api', 'auth:student'], 'prefix' => 'trainer-rating'], function () {
    Route::post('/rate', [TrainerRatingController::class, 'rateTrainer']);
});

// Trainer Rating - All Roles (View ratings)
Route::group(['middleware' => ['api', 'auth:admin,trainer,secretary,student'], 'prefix' => 'trainer-rating'], function () {
    Route::get('/{trainerId}/section/{sectionId}/ratings', [TrainerRatingController::class, 'getTrainerRatings']);
});


// Section Rating - Student (Create rating)
Route::group(['middleware' => ['api', 'auth:student'], 'prefix' => 'section-rating'], function () {
    Route::post('/rate', [SectionRatingController::class, 'rateSection']);
});


// Section Rating - All Roles (View ratings)
Route::group(['middleware' => ['api', 'auth:admin,trainer,secretary,student'], 'prefix' => 'section-rating'], function () {
    Route::get('/{sectionId}/ratings', [SectionRatingController::class, 'getSectionRatings']);
    
    
});

// showProgressForCourseSection- All Roles 
Route::group(['middleware' => ['api', 'auth:admin,trainer,secretary,student'], 'prefix' => 'course-sections'], function () {
    Route::get('/{sectionId}/progress', [CourseSectionController::class, 'showProgress']);
});




################################# STUDENT SEARCH ROUTES ##########################

// Student Search Routes (for trainers only)
Route::group(['middleware' => ['api', 'auth:trainer'], 'prefix' => 'trainer'], function () {
    Route::get('students/search', [SectionStudentSearchController::class, 'searchInAllSections']);
    Route::get('sections/{section}/students/search', [SectionStudentSearchController::class, 'searchInSpecificSection']);
    Route::get('sections/{section}/students/{studentId}', [SectionStudentSearchController::class, 'getStudentDetails']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Student Profile Routes
Route::middleware(['auth:student'])->group(function () {
    Route::get('/student/profile', [StudentController::class, 'getMyProfile']);
});

// Trainer Profile Routes
Route::middleware(['auth:trainer'])->group(function () {

    Route::get('/trainer/profile', [TrainerController::class, 'getMyProfile']);
    Route::get('/showStudentById/{studentId}', [CRUDStudentController::class, 'ShowStudentById']);
});

// Exam Routes
Route::group(['middleware' =>  ['api', 'auth:trainer'], 'prefix' => 'exams'], function () {
    Route::post('/', [ExamController::class, 'create']);
    Route::get('/section/{sectionId}', [ExamController::class, 'getBySection']);
    Route::put('/{examId}', [ExamController::class, 'update']);
    Route::delete('/{examId}', [ExamController::class, 'delete']);
});

// Grade Routes
Route::group(['middleware' => ['api', 'auth:trainer'], 'prefix' => 'grades'], function () {
    Route::post('/', [GradeController::class, 'create']);
    Route::get('/exam/{examId}', [GradeController::class, 'getByExam']);
    Route::get('/student/{studentId}', [GradeController::class, 'getByStudent']);
    Route::put('/{gradeId}', [GradeController::class, 'update']);
    Route::delete('/{gradeId}', [GradeController::class, 'delete']);
});
//Grades Management - Student Routes
Route::group(['middleware' => ['api', 'auth:student'], 'prefix' => 'grades'], function () {
    Route::get('/my-grades', [GradeController::class, 'getMyGrades']);
});

// Session routes
Route::group(['middleware' => ['api', 'auth:trainer'], 'prefix' => 'session'], function () {
    Route::post('/sessions', [SessionController::class, 'create']);
    Route::get('/sections/{sectionId}/sessions', [SessionController::class, 'getBySection']);
    Route::put('/sessions/{sessionId}', [SessionController::class, 'update']);
    Route::delete('/sessions/{sessionId}', [SessionController::class, 'delete']);
});

// Session Attendance Routes
Route::group(['middleware' => ['api', 'auth:trainer'], 'prefix' => 'session-attendance'], function () {
    // Session Attendance
    Route::post('/sessions/{sessionId}/attendance', [SessionAttendanceController::class, 'markAttendance']);
    Route::get('/sessions/{sessionId}/attendance', [SessionAttendanceController::class, 'getSessionAttendance']);
    
    // Student Attendance
    Route::get('/students/{studentId}/attendance', [SessionAttendanceController::class, 'getStudentAttendance']);
    
    // Section Attendance
    Route::get('/sections/{sectionId}/attendance', [SessionAttendanceController::class, 'getSectionAttendance']);
    
    // Edit and Delete Attendance
    Route::put('/attendance/{attendanceId}', [SessionAttendanceController::class, 'editAttendance']);
    Route::delete('/attendance/{attendanceId}', [SessionAttendanceController::class, 'deleteAttendance']);
    
    // Attendance Statistics
    Route::get('/students/{studentId}/sections/{sectionId}/attendance-stats', [SessionAttendanceController::class, 'getStudentAttendanceStats']);
    Route::get('/sections/{sectionId}/attendance-stats', [SessionAttendanceController::class, 'getSectionAttendanceStats']);
});
/*
Route::group(['middleware' => ['api', 'auth:student']], function () {
    Route::get('/student/recommendations', [CourseRecommendationController::class, 'getRecommendations']);
});

*/
// Saved Courses Routes for student 
Route::group(['middleware' => ['api', 'auth:student']], function () {
    Route::post('/courses/{courseId}/save', [SavedCourseController::class, 'saveCourse']);
    Route::delete('/courses/{courseId}/unsave', [SavedCourseController::class, 'unsaveCourse']);
    Route::get('/saved-courses', [SavedCourseController::class, 'getMySavedCourses']);
    Route::get('/saved-courses/count', [SavedCourseController::class, 'getSavedCoursesCount']);
});
// StudentStatisticsMonthly&Yearly
Route::group(['middleware' => ['api','auth:admin'], 'prefix' => 'admin/students'], function () {
    Route::get('/statistics/monthly', [StudentStatisticsController::class, 'monthly']);
    Route::get('/statistics/yearly', [StudentStatisticsController::class, 'yearly']);
});
//`CourseStatistics
Route::group(['middleware' => ['api','auth:admin'], 'prefix' => 'admin/courses'], function () {
    Route::get('/statistics/top-courses', [CourseStatisticsController::class, 'topCourses']);
});


    Route::post('/sendNot', [AuthAdminController::class, 'sendNotification']);