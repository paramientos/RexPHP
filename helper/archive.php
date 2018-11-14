<?php

/**
 * zip : taken from http://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php
 * add some usefull codes by me
 * unzip : Author : Subranil Dalal
 * http://burnignorance.com/php-programming-tips/php-class-for-unzipping-zip-file-on-linuxwindows/.
 */
class archive
{
    /**
     * @param type $to_zip
     * @param type $zipFile
     */
    public function zip($to_zip, $zipFile = 'zipped.zip')
    {
        $zip = new ZipArchive();
        $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if (is_array($to_zip)) {
            foreach ($to_zip as $to_zip_one) {
                if (is_dir($to_zip_one)) {
                    $rootPath = realpath($to_zip_one);
                    $files = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY
                    );

                    foreach ($files as $name => $file) {
                        // Skip directories (they would be added automatically)
                        if (!$file->isDir()) {
                            // Get real and relative path for current file
                            $filePath = $file->getRealPath();
                            $relativePath = substr($filePath, strlen($rootPath) + 1);

                            // Add current file to archive
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                } else {
                    $filePath = realpath($to_zip_one);
                    $relativePath = substr($filePath, strlen(dirname($filePath)) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        } else {
            if (is_dir($to_zip)) { // if it is dir
                $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    // Skip directories (they would be added automatically)
                    if (!$file->isDir()) {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($rootPath) + 1);

                        // Add current file to archive
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            } else { // else it is file
                if (is_array($to_zip)) {
                    foreach ($to_zip as $to_zip_file) {
                        $filePath = realpath($to_zip_file);
                        $relativePath = substr($filePath, strlen(dirname($filePath)) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                } else {
                    $filePath = realpath($to_zip);
                    $relativePath = substr($filePath, strlen(dirname($filePath)) + 1);
                    $zip->addFile($filePath, $relativePath);
                }

                $zip->close();
            }
        }
    }

    public function unzip($sourceFileName, $destinationPath = null)
    {
        $destinationPath = $destinationPath == null ? substr($sourceFileName, 0, -4) : $destinationPath; //remove ext -> .zip
        if (stristr(PHP_OS, 'WIN')) {
            $this->unzip_on_win($sourceFileName, $destinationPath);
        } else {
            $this->unzip_on_linux($sourceFileName, $destinationPath);
        }
    }

    /**
     * Function: unzip_on_win($sourceFileName,$destinationPath)
     * Unzipping a zip file on windows.
     *
     * @param string $sourceFileName,  source zip file name with absolute path
     * @param string $destinationPath, destination fath for unzipped file (absolute path)
     */
    public function unzip_on_win($sourceFileName, $destinationPath)
    {
        $directoryPos = strrpos($sourceFileName, '/');
        $directory = substr($sourceFileName, 0, $directoryPos + 1);
        if (file_exists($directory)) {
            $dir = opendir($directory);
        }
        $info = pathinfo($sourceFileName);
        if (strtolower($info['extension']) == 'zip') {
            $zip = new ZipArchive();
            $response = $zip->open($sourceFileName);
            if ($response === true) {
                $zip->extractTo($destinationPath);
                $zip->close();
            }
        }
        if ($directoryPos) {
            closedir($dir);
        }
    }

    /**
     * Function: unzip_on_linux($sourceFileName,$destinationPath)
     * Unzipping a zip file on linux.
     *
     * @param string $sourceFileName,  source zip file name with absolute path
     * @param string $destinationPath, destination fath for unzipped file (absolute path)
     */
    public function unzip_on_linux($sourceFileName, $destinationPath)
    {
        $directoryPos = strrpos($sourceFileName, '/');
        $directory = substr($sourceFileName, 0, $directoryPos + 1);
        $dir = opendir($directory);
        $info = pathinfo($sourceFileName);
        if (strtolower($info['extension']) == 'zip') {
            system('unzip -q '.$sourceFileName.'  -d '.$destinationPath);
        }
        closedir($dir);
    }
}
