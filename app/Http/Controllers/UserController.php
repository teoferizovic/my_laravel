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
use App\Services\AclService;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function index($id=null){

    	if($id != null){
            $users = $this->user->get($id);
            return \Response::json($users,200);
    	}

        //$users = Helper::EagerLoadingOrders();
        $users = $this->user->getAll();
    	
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

        $user = $this->user->getBy('email',$input['email']);

    	if($user) {
    		return \Response::json(['message' => 'User with that username allready exists!'], 400);
    	}

        $newUser = $this->user->new($input);

    	if($newUser) {

    		$userImage = UserImageController::storeFile($input,$newUser->id);
    	
            AclService::setPermissions($newUser);

    		return \Response::json(['message' => 'Successfully saved item!'], 201);
    	}

    	return \Response::json(['message' => 'Bad Request!'], 400);
    }

    public function login(){

    	$request = Request();
    	
    	$input = $request->all();

    	if ((isset($input['email']) == false) or (isset($input['password']) == false)) {
    		 return \Response::json(['message' => 'Bad Request!'], 400);
    	}

    	//$user = User::where('email', $input['email'])->first();
        $user = $this->user->getBy('email',$input['email']);
       
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

    public function activeUsers($id=null) {
        
        $emailArr = RedisService::getValues();

        $users = $this->user->getByArray('email', $emailArr);
        
        if($id!=null){
          return \Response::json(Helper::singleUser($users,$id) ,200);  
        }

        return \Response::json($users,200);
    }
}
