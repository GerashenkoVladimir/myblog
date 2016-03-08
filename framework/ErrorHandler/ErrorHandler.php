<?php
namespace Framework\ErrorHandler;

use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Response\Response;

class ErrorHandler
{
    public static function loadErrorHandler()
    {
        set_error_handler(function($errno,$errmsg, $filename,$linenum){
            $date = date('Y-m-d H:i:s (T)');
            if(!file_exists(__DIR__ . '/../../app/logs/')){
                mkdir(__DIR__ . '/../../app/logs/');
            }

            $f = fopen(__DIR__.'/../../app/logs/errorlog.txt','a');
            if (!empty($f)) {
                $error = "____________________________________________________________\n";
                $error .= $date."\n";
                $error .= $errno."\n";
                $error .= $errmsg."\n";
                $error .= $filename."\n";
                $error .= $linenum."\n";
                $error .= "____________________________________________________________\n";
                fwrite($f, $error);
                fclose($f);
            }
            /*if (Service::get('config')['mode'] == 'user') {
                $renderer = new Renderer(Service::get('config')['layouts']);
                $renderer->set('code', 500);
                $renderer->set('message', 'Oooops');
                $content = $renderer->generatePage('500.html');
                $response = new Response($content,array(),500);
                $response->send();
            }*/

        });
    }
}