<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\UserImage;
use Config;
use App\Jobs\UploadImage;

class UserImageController extends Controller
{
    public static function storeFile(array $input,int $userId) : bool {
    	
        
        if(!isset($_FILES["files"]["tmp_name"])){
    		
            //save in tbl user_images
            $userImage = new UserImage;
            
            $userImage->path     =   config('storage.userImgPath').'default/default.png'; 
            $userImage->user_id  =   $userId;
                
            $userImage->save();
            return true;
    	}

    	$numOfFiles = count($_FILES["files"]["tmp_name"]);

        //UploadImage::dispatch($input['email'],$userId,$_FILES);
    	//return true;
        if ($numOfFiles > 0) {

    		
    		//$path = $_SERVER['DOCUMENT_ROOT']."/uploads/".$input['email'];
            $folderName = strstr(str_replace("@","_",$input['email']),'.',true);
    		$path = config('storage.userFilePath').$folderName;
            
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
            
                $userImage->path     =   config('storage.userImgPath').$folderName.'/'.$file_name; 
                $userImage->user_id  =   $userId;
                
                $userImage->save();

            }

            return true;

        }
    	
    }
}
