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
    return redirect()->route('login');
});

Auth::routes();

Route::get('home', 'HomeController@index')->name('home')->middleware('auth');

Route::get('student', 'StudentController@display')->name('student')->middleware('auth');
Route::get('reset_password', 'StudentController@showResetPasswordForm')->name('reset_password.show')->middleware('auth');
Route::post('reset_password', 'StudentController@resetPassword')->name('reset_password.submit')->middleware('auth');
Route::get('hash', function(){
    return Hash::make('admin');
});

Route::group(['prefix' => 'student-api', 'middleware' => ['auth']], function() {
    Route::get('courses', 'StudentApiController@getCourses');
    Route::get('courses/{id}', 'StudentApiController@getCourseDetail');
    Route::get('course_student/{courseId}', 'StudentApiController@getCourseStudent');
    Route::get('courses/{courseId}/groups', 'StudentApiController@getGroups');
    Route::get('courses/{courseId}/group_members', 'StudentApiController@getGroupMembers');
    Route::get('courses/{courseId}/inter_group_assessments_answered', 'StudentApiController@getAnsweredGroupAssessments');
    Route::get('courses/{courseId}/intra_group_assessments_answered', 'StudentApiController@getAnsweredIntraGroupAssessments');
    Route::get('intra_group_assessments/{id}', 'StudentApiController@getIntraGroupAssessment');
    Route::get('intra_group_assessments/{id}/marks', 'StudentApiController@getIntraGroupAssessmentMarks');
    Route::post('intra_group_assessments/{id}/marks', 'StudentApiController@submitIntraGroupAssessmentMarks');
    Route::get('inter_group_assessments/{assessment}', 'StudentApiController@getGroupAssessment');
    Route::get('inter_group_assessments/{id}/marks', 'StudentApiController@getGroupAssessmentMarks');
    Route::get('inter_group_assessments/{id}/responses', 'StudentApiController@getGroupAssessmentResponses');
    Route::post('inter_group_assessments/{id}/marks', 'StudentApiController@submitInterGroupAssessmentMarks');
    Route::post('inter_group_assessments/{id}/responses', 'StudentApiController@submitInterGroupAssessmentResponses');
});

Route::get('admin', 'AdminController@display')->name('admin')->middleware('auth', 'can:admin');

Route::group(['prefix' => 'api', 'middleware' => ['auth', 'can:admin']], function() {
    Route::resource('courses', 'ApiCoursesController', [
        'except' => ['create', 'edit']
    ]);
    Route::post('groups/batch', 'ApiGroupsController@batchStore');
    Route::resource('groups', 'ApiGroupsController', [
        'except' => ['create', 'edit']
    ]);
    Route::post('students/batch', 'ApiStudentsController@batchStore');
    Route::post('students/{id}/change_password', 'ApiStudentsController@changePassword');
    Route::resource('students', 'ApiStudentsController', [
        'except' => ['create', 'edit']
    ]);
    Route::post('courses_students/batch', 'ApiCoursesStudentsController@batchStore');
    Route::resource('courses_students', 'ApiCoursesStudentsController', [
        'except' => ['create', 'edit']
    ]);
    Route::resource('group_assessments', 'ApiGroupAssessmentsController', [
        'except' => ['create', 'edit']
    ]);
    Route::get('intra_group_assessments/{assessment}/download/raw_data', 'ApiIntraGroupAssessmentsController@downloadRawData');
    Route::post('intra_group_assessments/{id}/calculate_marks', 'ApiIntraGroupAssessmentsController@calculateMarks');
    Route::get('intra_group_assessments/{assessment}/download/marks', 'ApiIntraGroupAssessmentsController@downloadMarks');
    Route::resource('intra_group_assessments', 'ApiIntraGroupAssessmentsController', [
        'except' => ['create', 'edit']
    ]);
    Route::get('group_assessments/{assessment}/download/raw_data', 'ApiGroupAssessmentsController@downloadRawData');
    Route::get('group_assessments/{assessment}/download/marks', 'ApiGroupAssessmentsController@downloadMarks');
    Route::post('group_assessments/{id}/calculate_marks', 'ApiGroupAssessmentsController@calculateMarks');

    Route::resource('group_assessments/{assessment}/marks', 'ApiGroupAssessmentTutorMarksController', [
        'only' => ['index', 'store']
    ]);
    Route::resource('intra_group_assessments/{assessment}/marks', 'ApiIntraGroupAssessmentTutorMarksController', [
        'only' => ['index', 'store']
    ]);
    Route::post('criterias/batch', 'ApiCriteriasController@batchStore');
    Route::put('criterias', 'ApiCriteriasController@batchUpdate');
    Route::delete('criterias', 'ApiCriteriasController@batchDestroy');
    Route::resource('criterias', 'ApiCriteriasController', [
        'except' => ['create', 'edit']
    ]);
    Route::resource('intra_group_assessment_marks_config', 'ApiIntraGroupAssessmentMarksConfigController', [
        'only' => ['store']
    ]);
    Route::resource('group_assessment_marks_config', 'ApiGroupAssessmentMarksConfigController', [
        'only' => ['store']
    ]);
    Route::post('emails/{courseId}', 'ApiEmailController@sendEmails');
});