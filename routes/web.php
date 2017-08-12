<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Home page
$app->get('/', function () use ($app) {
    return $app->version();
});

/**
 * @api {get} /students get all student
 * @apiName getAllStudent
 * @apiGroup Student
 */
$app->get('/students','StudentController@index');

/**
 * @api {get} /students/:id show
 * @apiName getStudent
 * @apiGroup Student
 */
$app->get('/students/{student_id}','StudentController@show');

/**
 * @api {put} /students/:id update
 * @apiName updateStudent
 * @apiGroup Student
 */
$app->put('/students/{student_id}', 'StudentController@update');

/**
 * @api {delete} /students/:id delete
 * @apiName deleteStudent
 * @apiGroup Student
 */
$app->delete('/students/{student_id}', 'StudentController@destroy');

/**
 * @api {post} /email_verification verify is email in the system
 * @apiName emailInSystemVerification
 * @apiGroup Users
 */
$app->post('/email_verification','UserController@emailInSystemVerification');

/**
 * @api {post} /login login
 * @apiName login
 * @apiGroup Users
 */
$app->post('/login','UserController@login');

/**
 * @api {post} /registration/student create student
 * @apiName create
 * @apiGroup Student
 */
$app->post('/registration/student','StudentController@store');

/**
 * @api {post} /registration/teacher create teacher
 * @apiName create
 * @apiGroup Teacher
 */
$app->post('/registration/teacher','TeacherController@store');


/**
 * @api {post} /oauth/access_token Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->post('/oauth/access_token', function() use ($app){
    return response()->json($app->make('oauth2-server.authorizer')->issueAccessToken());
});

/*********homework creator***********/

/**
 * @api {post} /oauth/access_token Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->get('/initData/homework_creator','HomeworkController@getInitDataHomeworkCreator');

/**
 * @api {post} /oauth/access_token Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->get('/subtopic/{subtopic_id}/blocks','HomeworkController@getBlocks');

/**
 * @api {post} /oauth/access_token Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->get('/teacher/{teacher_id}/homeworks/pending','HomeworkController@getTeacherPendingHomework');

/**
 * @api {post} /oauth/access_token Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->get('/student/{student_id}/homeworks/pending','HomeworkController@getStudentPendingHomework');

/**
 * @api {post} /oauth/access_token Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->get('/homework/{homework_id}/blocks','HomeworkController@getHomeworkBlocks');


/**
 * @api {post} /oauth/access_token Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->post('/create_homework','HomeworkController@store');

/*************TEST*************/
$app->get('/student/homeworks','TestController@getHomeworkStudent');

$app->get('/teacher/homeworks','TestController@getHomeworkTeacher');

$app->post('/create/homework','TestController@createHomework');

$app->get('/homework/{id}','TestController@getHomework');
