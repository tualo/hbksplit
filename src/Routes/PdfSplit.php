<?php
namespace Tualo\Office\HBKSplit\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;


class PdfSplit implements IRoute{

    public static function register(){
        BasicRoute::add('/hbksplit/upload',function($matches){
            $db = App::get('session')->getDB();
            App::result('X',$_SERVER);
            App::result('F',$_FILES);
            App::result('R',$_REQUEST);
            App::contenttype('application/json');
            try{
                if(!defined('PDF_SPLIT_PATH'))  throw new \Exception("configuration PDF_SPLIT_PATH missed");

                $jobid = '867625432634';
                ini_set('upload_max_filesize','2GB');
                ini_set('post_max_size','2GB');
                

                $newfile = PDF_SPLIT_PATH.'/'.$jobid.'pdf';
                if (isset($_FILES['uploadfile'])){
                    $sfile = $_FILES['uploadfile']['tmp_name'];
                    $name = $_FILES['uploadfile']['name'];
                    $type = $_FILES['uploadfile']['type'];
                    $error = $_FILES['uploadfile']['error'];
                    if ($error == UPLOAD_ERR_OK){
                        move_uploaded_file($sfile, $newfile);
                    
                    }
                }else{
                    /*
                    if (!isset($_SERVER['HTTP_X_FILE_NAME'])) throw new \Exception("Unknown file name");
                    $fileName = $_SERVER['HTTP_X_FILE_NAME'];

                    if (isset($_SERVER['HTTP_X_FILENAME_ENCODER']) && 'base64' == $_SERVER['HTTP_X_FILENAME_ENCODER']) {
                        $fileName = base64_decode($fileName);
                    }
                    $fileName = htmlspecialchars($fileName);
                    $mimeType = htmlspecialchars($_SERVER['HTTP_X_FILE_TYPE']);
                    $size = intval($_SERVER['HTTP_X_FILE_SIZE']);
                    */
                    $fileName = "$jobid.current.upload";
                    $inputStream = fopen('php://input', 'r');
                    $outputFilename = PDF_SPLIT_PATH . '/' . $fileName;
                    $realSize = 0;
                    $data = '';
                    if ($inputStream) {
                        $outputStream = fopen($outputFilename, 'w');
                        if (! $outputStream) {
                            throw new \Exception( 'Error creating local file');
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
                    /*
                    $tmp_name = $outputFilename;
                    if ($realSize != $size) {
                        throw new \Exception('The actual size differs from the declared size in the headers');
                    }
                    */
                }

                App::result('success',true);
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post','put'),true);

    }
}