<?php

class ModuleCopyRename {

    function getModulePath($name) {
        return drupal_get_path('module', $name);
    }

    function directory_recurse_copy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->directory_recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
        return $dst;
    }


    function file_rename($old, $new, $directory) {
        $dir = opendir($directory);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $filepath = $directory . '/' . $file;
                $dash_old = str_replace('_','-',$old);

                if($old == $dash_old) {
                    $dash_old = $old .'-';
                }

                $dash_new = str_replace('_','-',$new);
                if (is_dir($filepath)) {
                    if(strpos($file, $old) !== false){
                        $new_filepath = $directory . "/" . str_replace($old, $new, $file);
                        rename($filepath, $new_filepath);
                        $filepath = $new_filepath;
                    }

                    if(strpos($file, $dash_old) !== false){
                        $new_filepath = $directory . "/" . str_replace($dash_old, $dash_new, $file);
                        rename($filepath, $new_filepath);
                        $filepath = $new_filepath;
                    }

                    $this->file_rename($old, $new, $filepath);
                }
                else {
                    if(strpos($file, $old) !== false){
                        $new_filepath = $directory . "/" . str_replace($old, $new, $file);
                        rename($filepath, $new_filepath);
                        $filepath = $new_filepath;
                    }

                    if(strpos($file, $dash_old) !== false){
                        $new_filepath = $directory . "/" . str_replace($dash_old, $dash_new, $file);
                        rename($filepath, $new_filepath);
                        $filepath = $new_filepath;
                    }
                    $str = file_get_contents($filepath);
                    if(strpos($str, $old) !== false){
                        file_put_contents($filepath, str_replace($old, $new, $str));
                    }

                    if(strpos($str, $dash_old) !== false){
                        file_put_contents($filepath, str_replace($dash_old, $dash_new, $str));
                    }
                }
            }
        }

        return $new;
    }

    function stringReplace($directory, $old, $new) {
        $dir = opendir($directory);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $filepath = $directory . '/' . $file;
                if (is_dir($filepath)) {
                    $this->stringReplace($filepath, $old, $new);
                }
                else {
                    $str = file_get_contents($filepath);
                    if(strpos($str, $old) !== false){
                        file_put_contents($filepath, str_replace($old, $new, $str));
                    }
                }
            }
        }
    }

    function copy($old, $newpath) {
        $oldpath = $this->getModulePath($old);

        return $this->directory_recurse_copy($oldpath, $newpath);
    }

    function rename($old, $new) {
        $dir = $this->getModulePath($old);
        $base_dir = substr($dir, 0, (0 - strlen(basename($dir))));
        $base_dir = substr($base_dir, 0, -1); //get rid of trailing slash
        $new_dir = $base_dir . "/$new";

        $this->file_rename($old, $new, $dir);

        rename($dir, $new_dir);

        return $new_dir;
    }

    function copyRename($old, $new, $base_dir) {
        $new_dir = $base_dir . "/$new";

        if(file_exists($new_dir)) {
            drush_log('Directory Exists at ' . $new_dir, 'error');
            return false;
        }

        $this->copy($old, $base_dir . "/$old");

        $this->file_rename($old, $new, $base_dir . "/$old");

        rename($base_dir . "/$old", $new_dir);

        return $new_dir;
    }
}