<?php
namespace Tualo\Office\HBKSplit\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class JsLoader implements IRoute{
    public static function register(){
        BasicRoute::add('/hbksplit/loader.js',function($matches){
            App::contenttype('application/javascript');
            $list = [
                "js/StatusModel.js",
                "js/Viewport.js",
                "js/Routes.js"
            ];
            $content = '';
            foreach( $list as $item ){
                $content .= file_get_contents( dirname(__DIR__,1).'/'.$item ).PHP_EOL.PHP_EOL;
            }
            App::body( $content );
            
        },array('get'),false);

    }
}