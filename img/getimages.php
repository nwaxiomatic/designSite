<?php
//PHP SCRIPT: getimages.php
Header("content-type: application/x-javascript");

//This function gets the file names of all images in the current directory
//and ouputs them as a JavaScript array
function expandDirectoriesMatrix($base_dir, $level = 0) {
    $directories = array();
    foreach(scandir($base_dir) as $file) {
        if($file == '.' || $file == '..') continue;
        $dir = $base_dir.DIRECTORY_SEPARATOR.$file;
        if(is_dir($dir)) {
            $directories[]= array(
                    'level' => $level,
                    'name' => $file,
                    'path' => $dir,
                    'children' => expandDirectoriesMatrix($dir, $level +1)
            );
        }
    }
    return $directories;
}

function returnimages($base_dir, $level = 0) {
    $directories = expandDirectoriesMatrix($base_dir);
    $allfiles = array();
    for($i = 0; $i < count($directories); $i++){
        $directory = $directories[$i]['name'];
        $dirname = $directories[$i]['path'];
        echo 'galleryarray["'.$directory.'"]=[];';
        $pattern="(\.jpg$)|(\.png$)|(\.jpeg$)|(\.gif$)"; //valid image extensions
        $files = array();
        $j = 0;
        if($handle = opendir($dirname)) {
            while(false !== ($file = readdir($handle))){
                if(eregi($pattern, $file)){ //if this file is a valid image
                    //Output it as a JavaScript array element
                    echo 'galleryarray["'.$directory.'"]['.$j.']="'.$file .'";';
                    $j++;
                }
            }
            closedir($handle);
        }
    }
    
    return($allfiles);
}

echo 'var galleryarray={};'; //Define array in JavaScript
returnimages($_SERVER['DOCUMENT_ROOT'].'/img/') //Output the array elements containing the image file names
?>
