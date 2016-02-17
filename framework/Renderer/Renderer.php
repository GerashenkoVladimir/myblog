<?php

namespace Framework\Renderer;

use Framework\Registry\Registry;

class Renderer
{
    private $data = array();
    private $path;
    private $registry;

    public function __construct($path)
    {
        $this->registry = Registry::getInstance();
        $this->path = $path;
        $this->initHelpers();
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function generatePage($template)
    {

        foreach ($this->data as $var => $value) {
            $$var = $value;
        }
        ob_start();
        include $this->path.$template.'.php';
        $content = ob_get_clean();
        ob_start();
        include $this->registry['config']['main_layout'];
        $page = ob_get_clean();
        return $page;
    }

    private function initHelpers()
    {
        $this->data = array_merge($this->data, include('helpers.php'));
    }
}