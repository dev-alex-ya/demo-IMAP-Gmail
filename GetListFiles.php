<?php
function GetListFiles($folder, &$all_files){
    $fp = opendir($folder);
    while($cv_file = readdir($fp)) {
        if(is_file($folder . "/" . $cv_file)) {
            $all_files[] = $folder . "/" . $cv_file;
        }elseif($cv_file != "." && $cv_file != ".." && is_dir($folder . "/" . $cv_file)){
            GetListFiles($folder . "/" . $cv_file, $all_files);
        }
    }
    closedir($fp);
}
