<?php
/**
 * Application entry point.
 */
require_once(__DIR__ . '/../framework/Loader.php');

Loader::addNamespacePath('Blog\\', __DIR__ . '/../src/Blog');
\Framework\ErrorHandler\ErrorHandler::loadErrorHandler();

$app = new \Framework\Application(__DIR__ . '/../app/config/config.php');

$app->run();
