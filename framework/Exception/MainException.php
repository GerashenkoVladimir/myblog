<?php

namespace Framework\Exception;


use Framework\DI\Service;

class MainException extends \Exception
{
    public function saveExceptionLog()
    {
        $date = date('Y-m-d H:i:s (T)');
        if(!file_exists(__DIR__ . '/../../app/logs/')){
            mkdir(__DIR__ . '/../../app/logs/');
        }

        $f = fopen(__DIR__ . '/../../app/logs/exceptionLog.txt', 'a');
        if (!empty($f)) {
            $error  = "____________________________________________________________\n";
            $error .= $date . "\n";
            $error .= 'Exception:         '.get_called_class(). "\n";
            $error .= 'Exception message: '.$this->message . "\n";
            $error .= 'Exception code:    '.$this->code . "\n";
            $error .= 'File:              '.$this->file . "\n";
            $error .= "Stack trace:\n".$this->getTraceAsString() . "\n";
            $error .= "____________________________________________________________\n";
            fwrite($f, $error);
            fclose($f);
        }
        if (Service::get('config')['mode'] == 'dev') {
            echo "<pre>{$this}</pre>";
        }
    }
}