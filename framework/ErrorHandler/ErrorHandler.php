<?php
namespace Framework\ErrorHandler;

class ErrorHandler
{
    public static function loadErrorHandler()
    {
        set_error_handler(function($errno,$errmsg, $filename,$linenum){
            $date = date('Y-m-d H:i:s (T)');
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
        });
    }
}