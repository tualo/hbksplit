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
                if (!isset($_FILES['uploadfile']))  throw new \Exception("upload file missed");
                if ( $_FILES['uploadfile']['type']!="application/pdf" )  throw new \Exception("only pdf is allowed");
                
                $jobid = '867625432634';
                ini_set('upload_max_filesize','2GB');
                ini_set('post_max_size','2GB');
                

                $newfile = PDF_SPLIT_PATH.'/'.$jobid.'.pdf';
                $sfile = $_FILES['uploadfile']['tmp_name'];
                $name = $_FILES['uploadfile']['name'];
                $type = $_FILES['uploadfile']['type'];
                $error = $_FILES['uploadfile']['error'];
                if ($error == UPLOAD_ERR_OK){
                    move_uploaded_file($sfile, $newfile);
                }
                App::result('success',true);
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },array('get','post','put'),true);

    }
}