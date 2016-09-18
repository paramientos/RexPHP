<?php

class io {

    /**
     * 
     * @param type $folder
     * @return boolean
     */
    function recursive_dir($folder) {
        if (is_dir($folder)) {
            return true;
        }

        $folder = str_replace('/', DIRECTORY_SEPARATOR, $folder);
        $folder = str_replace('\\', DIRECTORY_SEPARATOR, $folder);

        $dirs = explode(DIRECTORY_SEPARATOR, $folder);
        $dir = '';

        foreach ($dirs as $part) {
            if (empty($part) || ($part == '.')) {
                continue;
            }

            $dir .=$part . DIRECTORY_SEPARATOR;

            if ($part == '..') {
                continue;
            }

            if (!is_dir($dir)) {
                $ok = @mkdir($dir, 0755);

                if (!$ok)
                    return false;
            }
        }

        clearstatcache();

        return is_dir($folder);
    }

    function force_download($file) {
        if ((isset($file)) && (file_exists($file))) {
            header("Content-length: " . filesize($file));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            readfile("$file");
        } else {
            echo "No file selected";
        }
    }

    /**
     * http://webdeveloperplus.com/php/21-really-useful-handy-php-code-snippets/
     * @dir - Directory to destroy 
     * @virtual[optional]- whether a virtual directory 
     */
    function rmdir($dir, $virtual = false) {
        $ds = DIRECTORY_SEPARATOR;
        $dir = $virtual ? realpath($dir) : $dir;
        $dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
        if (is_dir($dir) && $handle = opendir($dir)) {
            while ($file = readdir($handle)) {
                if ($file == '.' || $file == '..') {
                    continue;
                } elseif (is_dir($dir . $ds . $file)) {
                    rmdir($dir . $ds . $file);
                } else {
                    unlink($dir . $ds . $file);
                }
            }
            closedir($handle);
            rmdir($dir);
            return true;
        } else {
            return false;
        }
    }

    function randomize_fileName($real_file_name) {
        $name_parts = @explode(".", $real_file_name);
        $ext = "";
        if (count($name_parts) > 0) {
            $ext = $name_parts[count($name_parts) - 1];
        }
        return substr(md5(uniqid(rand(), 1)), -16) . "." . $ext;
    }

    function file_size($url) {
        $size = filesize($url);
        if ($size >= 1073741824) {
            $fileSize = round($size / 1024 / 1024 / 1024, 1) . 'GB';
        } elseif ($size >= 1048576) {
            $fileSize = round($size / 1024 / 1024, 1) . 'MB';
        } elseif ($size >= 1024) {
            $fileSize = round($size / 1024, 1) . 'KB';
        } else {
            $fileSize = $size . ' bytes';
        }
        return $fileSize;
    }

    function get_file_extension($file) {
        $info = pathinfo($file);
        return $info['extension'];
    }

    function human_size($url) {
        $size = filesize($url);
        if ($size <= 1024)
            return $size . ' Bytes';
        else if ($size <= (1024 * 1024))
            return sprintf('%d KB', (int) ($size / 1024));
        else if ($size <= (1024 * 1024 * 1024))
            return sprintf('%.2f MB', ($size / (1024 * 1024)));
        else
            return sprintf('%.2f Gb', ($size / (1024 * 1024 * 1024)));
    }

    
    /**
     * no curl is needed
     * @param type $filename
     * @param type $path
     * @param type $mimetype
     */
    function download_document($filename, $path = "", $mimetype = "application/octet-stream") {
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Disposition: attachment; filename = $filename");
        header("Content-Length: " . filesize($pathto . $filename));
        header("Content-Type: $mimetype");
        echo file_get_contents($pathto . $filename);
    }

    /*
     * require curl
     * file downloads to root
     */

    function download($url) {
        $pi = pathinfo($url);
        $ext = $pi['extension'];
        $name = $pi['filename'];


// create a new cURL resource 
        $ch = curl_init();

// set URL and other appropriate options 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// grab URL and pass it to the browser 
        $opt = curl_exec($ch);

// close cURL resource, and free up system resources 
        curl_close($ch);

        $saveFile = $name . '.' . $ext;
        if (preg_match("/[^0-9a-z\.\_\-]/i", $saveFile))
            $saveFile = md5(microtime(true)) . '.' . $ext;

        $handle = fopen($saveFile, 'wb');
        fwrite($handle, $opt);
        fclose($handle);
    }

}
?>