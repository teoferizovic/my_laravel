<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\User;
use Helper;
use Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserImageController;
use App\Http\Controllers\UserLogController;
use App\Jobs\SendEmailResetPassword;
use App\Services\RedisService;

class UserController extends Controller
{
    public function index($id=null){

    	if($id != null){
    		$users = User::with(['user_images','orders','user_logs'=> function ($query) {
                $query->orderBy('id', 'desc')->limit(1);
            }])->where('id', $id)->first();
    		
            return \Response::json($users,200);
    	}

        //$users = Helper::EagerLoadingOrders();

    	$users = User::with(['user_images','orders','user_logs'  => function ($query) {
            $query->orderBy('id', 'desc')->limit(1);
        }])->get();

    	return \Response::json($users,200);
    }

    public function indexQ(Request $request) {

        $params = ['name','email','password'];
        $query = "SELECT * FROM users";

        $queryParams = $request->query();
        
        if (!empty($queryParams)){

            $i = 0;

            foreach ($params as $param) {               

               if(isset($queryParams[$param])){
                     if($i>0){
                       $query .= " AND $param='$queryParams[$param]'"; 
                       continue;
                     } 
                     $query .= " WHERE $param='$queryParams[$param]'";
                     $i++; 
                }else{
                    continue;
                }

            }
            
        }

        $users = DB::select($query);
        return \Response::json($users,201);
        //http://127.0.0.1:8000/users/indexQ/?name=test_name&email=test@test.com
    }



    public function create(){
    	
    	$request = Request();
    	$input = $request->all();

    	if ((isset($input['email']) == false) or (isset($input['password']) == false)) {
    		 return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	$user = User::where('email', $input['email'])->first();

    	if($user) {
    		return \Response::json(['message' => 'User with that username allready exists!'], 400);
    	}
    	
    	$newUser = new User();

    	$newUser->name = "";
    	$newUser->email = $input['email'];
    	
    	//$newUser->password = password_hash($input['password'], PASSWORD_BCRYPT);
    	$newUser->password = Hash::make($input['password']);
        $newUser->role_id = isset($input['role_id']) ? $input['role_id'] : 2;

    	if($newUser->save()){

    		$userImage = UserImageController::storeFile($input,$newUser->id);
    		
    		$allRoles = DB::select('SELECT p.name from permissions as p GROUP BY(p.name)');
    	
	    	$userRoles = DB::select('SELECT p.name from users as u inner join roles as r on u.role_id = r.id inner join role_permissions as rp on r.id=rp.role_id inner join permissions as p 
	   			  on rp.permission_id = p.id where u.role_id=:role_id',["role_id" => $newUser->role_id]);

	    	$userRolesArr = [];
	    	$allRolesArr = [];
	    	$permissions = [];

	    	foreach ($userRoles as $role) {
	    		$userRolesArr[] = $role->name;
	    	}

	    	
	    	foreach ($allRoles as $role) {
	    		$allRolesArr[] = $role->name;
	    	}

	    	foreach ($allRolesArr as $role) {
	   
	    		if(in_array($role, $userRolesArr)){
	    			$permissions[$role] = true;
	    		} else {
	    			$permissions[$role] = false;
	    		}
	   
	    	}
	    	
	    	$redisAclKey = $newUser->email."-"."ACL";
	    	
	    	//Redis::set($redisAclKey,json_encode($permissions));
            RedisService::setValue($redisAclKey,json_encode($permissions),'acl-cache');

    		return \Response::json(['message' => 'Successfully saved item!'], 200);
    	}

    	
    }

    public function login(){

    	$request = Request();
    	
    	$input = $request->all();

    	if ((isset($input['email']) == false) or (isset($input['password']) == false)) {
    		 return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	$user = User::where('email', $input['email'])->first();

		//if($user == null or !password_verify($input['password'], $user->password)) {        
        if($user == null or !Hash::check($input['password'], $user->password)) {
			 return \Response::json(['message' => 'Not Found!'], 404);
		}

        if(UserLogController::create(["user_id"=>$user->id,'ip_address'=>$request->getClientIp()]) != true){
             return \Response::json(['message' => 'Server Error!'], 500);
        }

		$authToken = str_random(60);
        //$authToken = bin2hex(openssl_random_pseudo_bytes(32));
	
        RedisService::setValue($authToken,$user->email);

		$user->api_token = $authToken;
		
    	return \Response::json($user, 200);

    }


    public function logout($token=null){

    	if($token==null) {
    		return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	//find auth_token in Redis and delete it
        $user = RedisService::getValue($token);
    	
    	if (empty($user)){
    		return \Response::json(['message' => 'Not Found!'], 404);
    	}
        
        RedisService::removeValue($token);

    	return \Response::json(['message' => 'Successfully logged out!'], 200);
    }

    public function forgot_password(){
        
        $request = Request();        
        $input = $request->all();

        $user = User::whereEmail($input['email'])->first();
        
        if ($user == null){
            return \Response::json(['message' => 'Not Found!'], 404);
        }

        $forgotToken = str_random(60);

        $user->forgot_token         =  $forgotToken;
        $user->forgot_token_expire  = date('Y-m-d H:i:s', strtotime('now +20 minutes'));
        
        if($user->save()){
            SendEmailResetPassword::dispatch($user);
            return \Response::json(['message' => 'Successfully edit user!'], 200);
        }
    }

    public function reset_password(){

        $request = Request();        
        $input = $request->all();

        $user = User::whereForgot_token($input['forgot_token'])->where('forgot_token_expire','>=',date('Y-m-d H:i:s', strtotime('now')))->first();
        
        if ($user == null){
            return \Response::json(['message' => 'Not Found!'], 404);
        } 
        
        $user->password             =  Hash::make($input['password']);
        $user->forgot_token         =  null; 
        $user->forgot_token_expire  =  null;
        
        if($user->save()){
            return \Response::json(['message' => 'Successfully edit user!'], 200);
        }
    }

    public function active_users($id=null){
        
        $emailArr = Helper::activeUsers();

        $users = User::whereIn('email',$emailArr)->get();
       
        if($id!=null){
          return \Response::json(Helper::singleUser($users,$id) ,200);  
        }

        return \Response::json($users,200);
    }
}
