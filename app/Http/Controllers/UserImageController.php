<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\UserImage;
use Config;

class UserImageController extends Controller
{
    public static function storeFile(array $input,int $userId) : bool {
    	
    	
        if(!isset($_FILES["files"]["tmp_name"])){
    		return false;
    	}

    	$numOfFiles = count($_FILES["files"]["tmp_name"]);

    	if ($numOfFiles > 0) {

    		
    		//$path = $_SERVER['DOCUMENT_ROOT']."/uploads/".$input['email'];
    		$path = config('storage.userFilePath').$input['email'];
            
    		//make dir by email
    		if (!file_exists($path)) { 
			    mkdir($path, 0777, true);
			}

            
            for($i = 0; $i < $numOfFiles; $i ++) {
                
                $tmp_file = $_FILES["files"]["tmp_name"][$i];
                $file_name = round(microtime(true) * 1000)."_".$_FILES["files"]["name"][$i];
                
                $upload_dir = $path."/".$file_name;

                if (move_uploaded_file($tmp_file,$upload_dir) != true) {
                    return false;
                }

                //save in tbl user_images
                $userImage = new UserImage;
            
                $userImage->path     =   config('storage.userImgPath').'/'.$input['email'].'/'.$file_name; 
                $userImage->user_id  =   $userId;
                
                $userImage->save();

                }

                return true;

        }

    	
    }
}
