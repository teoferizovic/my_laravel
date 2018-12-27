<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\UserImage;
use Config;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   
    protected $email;
    protected $userId;
    protected $files;

    public $timeout = 120;

    public function __construct(string $email,int $userId,array $files){
        $this->email  = $email;
        $this->userId = $userId;
        $this->files  = $files;
    }

    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
           
            $numOfFiles = count($this->files["files"]["tmp_name"]);
            echo($numOfFiles);

            $folderName = strstr(str_replace("@","_",$this->email),'.',true);
            $path = config('storage.userFilePath').$folderName;
            
            //make dir by email
            if (!file_exists($path)) { 
                mkdir($path, 0777, true);
            }

            
            for($i = 0; $i < $numOfFiles; $i ++) {
                
                $tmp_file = $this->files["files"]["tmp_name"][$i];
                $file_name = round(microtime(true) * 1000)."_".$this->files["files"]["name"][$i];
                
                $upload_dir = $path."/".$file_name;
                
                if (move_uploaded_file($tmp_file,$upload_dir) != true) {
                    return false;
                }

                //save in tbl user_images
                $userImage = new UserImage;
            
                $userImage->path     =   config('storage.userImgPath').$folderName.'/'.$file_name; 
                $userImage->user_id  =   $this->userId;
                
                $userImage->save();

            }

    }
}
