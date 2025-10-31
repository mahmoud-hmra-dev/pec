<?php

namespace App\Traits;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


trait FileHandler
{
    private $files;
    /**
     * this function takes a base64 encoded image and store it in the filesystem and return the name of it
     * (ex. 12546735.png) that will be stored in DB
     * @param $file
     * @param $dir
     * @param false $is_base_64
     * @return string
     */
    public function storeFile($file, $dir){
        $this->files = new Filesystem();
        $this->makeDirectory(storage_path('app/public/'.$dir));
        return Storage::disk('public')->putFile($dir, $file);
    }

    /**
     * this function takes $newImage(base64 encoded) and $oldImage(DB name) ,
     * it deletes the $oldImage from the filesystem and store the $newImage and return it's name that will be stored in DB
     * @param $new_file
     * @param $old_file
     * @param $dir
     * @return string
     */
    public  function updateFile($new_file, $old_file, $dir){
        $this->deleteFile($old_file);
        $name=$this->storeFile($new_file,$dir);
        return $name;
    }

    /**
     * this function takes image(DB name) and deletes it from the filesystem ,
     * returns true if deleted and false if not found
     * @param $file
     * @return bool
     */
    public  function deleteFile($file){

        if(file_exists(storage_path('app/public/').$file)){
            Storage::disk('public')->delete($file);
            return true;
        }
        return false;
    }

    /**
     * make directory for files
     * @param $path
     * @return mixed
     */
    private function makeDirectory($path)
    {
        $this->files->makeDirectory($path, 0777, true,true);
        return $path;
    }
}

