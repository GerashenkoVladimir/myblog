<?php

namespace Framework\Exception;


use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Response\Response;

/**
 * Class MainException
 * @package Framework\Exception
 */
class MainException extends \Exception
{
    /**
     * Saves exception log in log file
     *
     * @access public
     *
     * @return void
     * @throws ServiceException
     */
    public function saveExceptionLog()
    {
        $date = date('Y-m-d H:i:s (T)');
        if (!file_exists(__DIR__ . '/../../app/logs/')) {
            mkdir(__DIR__ . '/../../app/logs/');
        }

        $f = fopen(__DIR__ . '/../../app/logs/exceptionLog.txt', 'a');
        if (!empty($f)) {
            $error = "____________________________________________________________\n";
            $error .= $date . "\n";
            $error .= 'Exception:         ' . get_called_class() . "\n";
            $error .= 'Exception message: ' . $this->message . "\n";
            $error .= 'Exception code:    ' . $this->code . "\n";
            $error .= 'File:              ' . $this->file . "\n";
            $error .= "Stack trace:\n" . $this->getTraceAsString() . "\n";
            $error .= "____________________________________________________________\n";
            fwrite($f, $error);
            fclose($f);
        }
        if (Service::get('config')['mode'] == 'dev') {
            echo "<pre>{$this}</pre>";
        }
    }

    /**
     * Handles exception for user
     *
     * @access public
     * @static
     *
     * @param \Exception $exception
     * @param array      $messages
     *
     * @return Response
     * @throws ServiceException
     */
    public static function handleForUser(\Exception $exception, $messages = array())
    {
        if ($exception == null) {
            $exception = new \Exception('Sorry for the inconvenience. We are working to resolve this issue.
            Thank you for your patience.');
        }
        $renderer = new Renderer(Service::get('config')['error_500'], true);
        foreach ($messages as $message => $m) {
            $renderer->set($message, $m);
        }
        $content = $renderer->generatePage();
        $response = new Response($content, array(), 500);
        $exception->saveExceptionLog();
        return $response;
    }
}