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
                // ini_set('upload_max_filesize','2GB');
                // ini_set('post_max_size','2GB');

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
        },[ 'get','post','put' ],true);

        BasicRoute::add('/hbksplit/status',function($matches){
            $db = App::get('session')->getDB();
            
            App::contenttype('application/json');
            try{
                if(!defined('PDF_SPLIT_PATH'))  throw new \Exception("configuration PDF_SPLIT_PATH missed");
                $glob_result = glob(implode('/',[PDF_SPLIT_PATH,'*.json']));
                $states = [];
                foreach($glob_result as $file){
                    //$pdfPages[]=$file;
                    $states[] = json_decode(file_get_contents($file),true);
                }

                App::result('glob_result',$glob_result);
                App::result('data',$states);
                App::result('success',true);
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },[ 'get','post','put' ],true);

        BasicRoute::add('/hbksplit/delete/(?P<id>\w+)',function($matches){
            $db = App::get('session')->getDB();
            
            App::contenttype('application/json');
            try{
                if(!defined('PDF_SPLIT_PATH'))  throw new \Exception("configuration PDF_SPLIT_PATH missed");
                
                try{
                    $dir = implode('/',[PDF_SPLIT_PATH,'tmp',$matches['id']]);
                    $jobfile = implode('/',[PDF_SPLIT_PATH,$matches['id'].'.json']);
                    $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
                    $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
                    foreach($files as $file) {
                        if ($file->isDir()){
                            rmdir($file->getRealPath());
                        } else {
                            unlink($file->getRealPath());
                        }
                    }
                    rmdir($dir);
                    unlink($jobfile);
                }catch(\Exception $e){
                    App::result('RecursiveDirectoryIterator_msg', $e->getMessage());
                }
                
                App::result('success',true);
            }catch(\Exception $e){
                App::result('msg', $e->getMessage());
            }
        },[ 'get','post','put' ],true);
    }

    
}