<?php

namespace Framework\Renderer;

use Framework\DI\Service;

/**
 * Class Renderer
 * @package Framework\Renderer
 */
class Renderer
{
    /**
     * All data what can be need for rendering
     *
     * @access private
     *
     * @var array
     */
    private $data = array();

    /**
     * Path to template
     *
     * @access private
     *
     * @var string
     */
    private $path;

    /**
     * If path to template is fool $isFool == true, else $isFool == false
     *
     * @access private
     *
     * @var bool
     */
    private $isFool;

    /**
     * Renderer constructor
     *
     * @access public
     *
     * @param string     $path
     * @param bool|false $isFull
     */
    public function __construct($path, $isFull = false)
    {
        $this->path = $path;
        $this->isFool = $isFull;
        $this->initHelpers();
    }

    /**
     * Sets data for rendering
     *
     * @access public
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Generates page
     *
     * @access public
     *
     * @param string|null $template
     *
     * @return string
     * @throws \Framework\Exception\ServiceException
     */
    public function generatePage($template = null)
    {
        extract($this->data);

        ob_start();
        $path = $this->path . $template . '.php';
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

    /**
     * Initialize "helpers"
     *
     * @access private
     *
     * @return void
     */
    private function initHelpers()
    {
        $this->data = array_merge($this->data, include('helpers.php'));
    }
}