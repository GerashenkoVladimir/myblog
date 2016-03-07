<?php

namespace Framework\Renderer;

use Framework\DI\Service;

class Renderer
{
    private $data = array();
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
        $this->initHelpers();
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function generatePage($template)
    {
        extract($this->data);

        ob_start();
        include $this->path.$template.'.php';
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