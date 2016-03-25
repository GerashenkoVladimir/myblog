<?php
ini_set('display_errors',0);
/**
 * Application entry point.
 */

require_once(__DIR__ . '/../framework/Loader.php');
\Framework\ErrorHandler\ErrorHandler::loadErrorHandler();
Loader::addNamespacePath('Blog\\', __DIR__ . '/../src/Blog');


$app = new \Framework\Application(__DIR__ . '/../app/config/config.php');

$app->run();
