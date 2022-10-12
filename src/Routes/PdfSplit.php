<?php
namespace Tualo\Office\HBKSplit\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;


class PdfSplit implements IRoute{

    public static function register(){
        BasicRoute::add('/hbksplit/upload',function($matches){
            $db = App::get('session')->getDB();
            App::contenttype('application/json');
            try{

                $jobid = '867625432634';
                ini_set('upload_max_filesize','2GB');
                ini_set('post_max_size','2GB');
                

                $newfile = PDF_SPLIT_PATH.'/'.$jobid.'pdf';
                if (isset($_FILES['userfile'])){
                    $sfile = $_FILES['userfile']['tmp_name'];
                    $name = $_FILES['userfile']['name'];
                    $type = $_FILES['userfile']['type'];
                    $error = $_FILES['userfile']['error'];
                    if ($error == UPLOAD_ERR_OK){
                        move_uploaded_file($sfile, $newfile);
                    if (file_exists($newfile)){
                      $config = json_decode(file_get_contents($newfile),true);
                      unlink($newfile);
                    }
                  }
                }else{
                    if (!isset($_SERVER['HTTP_X_FILE_NAME'])) throw new \Exception("Unknown file name");
                    $fileName = $_SERVER['HTTP_X_FILE_NAME'];
                    if (isset($_SERVER['HTTP_X_FILENAME_ENCODER']) && 'base64' == $_SERVER['HTTP_X_FILENAME_ENCODER']) {
                        $fileName = base64_decode($fileName);
                    }
                    $fileName = htmlspecialchars($fileName);
                    $mimeType = htmlspecialchars($_SERVER['HTTP_X_FILE_TYPE']);
                    $size = intval($_SERVER['HTTP_X_FILE_SIZE']);
                    $inputStream = fopen('php://input', 'r');
                    $outputFilename = PDF_SPLIT_PATH . '/' . $fileName;
                    $realSize = 0;
                    $data = '';
                    if ($inputStream) {
                        if (! $config['fake']) {
                            $outputStream = fopen($outputFilename, 'w');
                            if (! $outputStream) {
                                throw new \Exception( 'Error creating local file');
                            }
                        }

                        while (! feof($inputStream)) {
                            $bytesWritten = 0;
                            $data = fread($inputStream, 1024);

                            $bytesWritten = fwrite($outputStream, $data);

                            if (false === $bytesWritten) {
                                throw new \Exception('Error writing data to file');
                            }
                            $realSize += $bytesWritten;
                        }

                        fclose($outputStream);

                    } else {
                        throw new \Exception('Error reading input');
                    }
                    $tmp_name = $outputFilename;
                    if ($realSize != $size) {
                        throw new \Exception('The actual size differs from the declared size in the headers');
                    }
                }

                App::result('success',true);
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post'),true);

    }
}