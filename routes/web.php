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


/*************STUDENTS*************/

/**
 * @api {get} /students get all student
 * @apiName getStudents
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
 * @api {post} /student/registration create student
 * @apiName create
 * @apiGroup Student
 */
$app->post('/student/registration','StudentController@store');


/*************USERS*************/


/**
 * @api {post} /login login
 * @apiName login
 * @apiGroup Users
 */
$app->post('/login','UserController@login');

/**
 * @api {post} /email_verification verify is email in the system
 * @apiName emailInSystemVerification
 * @apiGroup Users
 */
$app->post('/email/verification','UserController@emailInSystemVerification');


/*************TEACHERS*************/


/**
 * @api {post} /teacher/registration create teacher
 * @apiName create
 * @apiGroup Teacher
 */
$app->post('/teacher/registration','TeacherController@store');


/*************HOMEWORKS*************/


/**
 * @api {post} /initData/homework_creator Get the data that initializa homework creator view
 * @apiName getInitDatahomework
 * @apiGroup Homeworks
 */
$app->get('/initData/homework_creator','HomeworkController@getInitDataHomeworkCreator');

/**
 * @api {get} /subtopic/:subtopic_id/blocks Get blocks of a subtopic
 * @apiName getSubtopicBlocks
 * @apiGroup Homeworks
 */
$app->get('/subtopic/{subtopic_id}/blocks','HomeworkController@getSubtopicBlocks');

/**
 * @api {get} /teacher/:teacher_id/homeworks/pending Get all pending homework of a teacher
 * @apiName getTeacherPendingHomework
 * @apiGroup Homework
 */
$app->get('/teacher/{teacher_id}/homeworks/pending','HomeworkController@getTeacherPendingHomework');

/**
 * @api {get} /student/{student_id}/homeworks/pending Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->get('/student/{student_id}/homeworks/pending','HomeworkController@getStudentPendingHomework');

/**
 * @api {get} /homework/{homework_id}/blocks Get all blocks of a homework
 * @apiName token
 * @apiGroup Homework
 */
$app->get('/homework/{homework_id}/blocks','HomeworkController@getHomeworkBlocks');

/**
 * @api {post} /student/:student_id/homework/block/:homework_block_id Get block data to play the game
 * @apiName getBlock
 * @apiGroup Homework
 */
$app->get('/student/{student_id}/homework/block/{homework_block_id}','HomeworkController@getBlock');

/**
 * @api {post} /homework/block/submit/:student_block_id Process block data 
 * @apiName homeworkBlockSubmit
 * @apiGroup Homework
 */
$app->post('/homework/block/submit/{student_block_id}','HomeworkController@homeworkBlockSubmit');

/**
 * @api {post} /create_homework create homework
 * @apiName createHomework
 * @apiGroup Homework
 */
$app->post('/create_homework','HomeworkController@store');


/*************OAUTH2*************/


/**
 * @api {post} /oauth/access_token Oauth2 token
 * @apiName token
 * @apiGroup Oauth2
 */
$app->post('/oauth/access_token', function() use ($app){
    return response()->json($app->make('oauth2-server.authorizer')->issueAccessToken());
});


