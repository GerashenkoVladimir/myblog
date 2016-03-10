<?php

namespace Framework\Renderer;

use Framework\DI\Service;

class Renderer
{
    private $data = array();
    private $path;
    private $isFool;

    public function __construct($path, $isFull = false)
    {
        $this->path   = $path;
        $this->isFool = $isFull;
        $this->initHelpers();
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function generatePage($template = null)
    {
        extract($this->data);

        ob_start();
        $path = $this->path.$template.'.php';
        if ($template == null && $this->isFool) {
            $path = $this->path;
        }
        require_once $path;
        $content = ob_get_clean();

        ob_start();
        include Service::get('config')['main_layout'];
        $page = ob_get_clean();

        return $page;
    }

    private function initHelpers()
    {
        $this->data = array_merge($this->data, include('helpers.php'));
    }
}