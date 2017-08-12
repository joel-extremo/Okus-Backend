<?php

namespace App\Http\Controllers;

use App\Student;
use App\Teacher;
use App\GradeSectionSubject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{

	public function __construct(){

		//$this->middleware('oauth', ['except' => ['index', 'store']]);
		$this->middleware('client_credentials', ['except' => []]);
		$this->middleware('authorize:' . __CLASS__,
			['except' => [
				'emailInSystemVerification',
				'login',
				'directLogin'
				]
			]);
	}

	public function emailInSystemVerification(Request $request){

		//validate incomming data
		$this->validate($request, ['email' => 'required|email']);

		$student = Student::where('email', $request->email)->first();

		if($student){

			return response()->json(["user_type_id" => 1], 302);

		}else{

			$teacher = Teacher::where('email', $request->email)->first();

			if($teacher){

				return response()->json(["user_type_id" => 2], 302);

			}else{

				return response()->json([], 404);

			}
		}

	}

	public function login(Request $request){

		//validate incomming data
		$this->validate($request, ['email' => 'required|email']);

		if($request->is_standard_login){

			$userData = $this->standardLogin($request);

		}else{

			$userData = $this->socialNetworkLogin($request);

		}

		if($userData){

			$result = [
				"access_token" => app("oauth2-server.authorizer")->issueAccessToken()["access_token"],
				"user_data" => $userData
			];

			return response()->json($result, 200);

		}else{

			return response()->json([] , 404);

		}

	}

	public function standardLogin($request){

		if($request->user_type_id == 1){

			$userData = Student::login($request->email, $request->password);

		}elseif($request->user_type_id == 2){

			$userData = Teacher::login($request->email, $request->password);

		}else{

			$userData = null;

		}

		return $userData;

	}

	public function socialNetworkLogin(Request $request){

		if($request->user_type_id == 1){

			$userData = Student::where('email', '=', $request->email)
							->select('id as user_id', 'status_id as user_status_id', 'username',
								'firstname', 'lastname', 'grade_section_id')->get();

		}elseif($request->user_type_id == 2){

			$userData = Teacher::loginWithoutPassword($request->email);

		}

		return $userData;
	}

}
