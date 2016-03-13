<?php

namespace Framework\Response;

use Framework\DI\Service;

/**
 * Class Response
 *
 * @package Framework\Response
 */
class Response
{
    /**
     * Http headers
     *
     * @protected
     * @var array
     */
    protected $headers = array();

    /**
     * Status code
     *
     * @protected
     * @var int
     */
    protected $statusCode;

    /**
     * Content
     *
     * @protected
     * @var string
     */
    protected $content = '';

    /**
     * Cookies
     *
     * @protected
     * @var array
     */
    protected $cookies = array();

    /**
     * Response constructor
     *
     * @public
     *
     * @param string $content    Content
     * @param array  $headers    Http headers
     * @param int    $statusCode Status code
     */
    public function __construct($content = '', $headers = array(), $statusCode = 200)
    {
        $this->content = $content;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    /**
     * Sets the content
     *
     * @public
     *
     * @param $content
     *
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Sets http header
     *
     * @public
     *
     * @param mixed $header
     *
     * @return void
     */
    public function setHeader($header)
    {
        array_push($this->headers, $header);
    }

    /**
     * Sets status code
     *
     * @public
     *
     * @param mixed $statusCode
     *
     * @return void
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Sets cookie
     *
     * @public
     *
     * @param        $name
     * @param string $value
     * @param int    $expire
     * @param string $path
     * @param string $domain
     * @param int    $secure
     * @param int    $httpOnly
     *
     * @return void
     */
    public function setCookie($name, $value = '', $expire = 0, $path = '', $domain = '', $secure = 0, $httpOnly = 0)
    {
        $this->cookies[] = array(
            'name'     => $name,
            'value'    => $value,
            'expire'   => $expire,
            'path'     => $path,
            'domain'   => $domain,
            'secure'   => $secure,
            'httpOnly' => $httpOnly
        );

    }

    /**
     * Sends all headers
     *
     * @public
     * @throws \Framework\Exception\ServiceException
     * @return void
     */
    public function sendHeaders()
    {
        header(Service::get('request')->getServerProtocol() . " $this->statusCode");

        foreach ($this->headers as $header) {
            header($header);
        }
    }

    /**
     * Sends all cookies
     *
     * @public
     * @return void
     */
    public function sendCookies()
    {
        foreach ($this->cookies as $cookie) {
            setcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'],
                $cookie['secure'], $cookie['httpOnly']);
        }
    }

    /**
     * Sends content
     *
     * @public
     * @return void
     */
    public function sendContent()
    {
        if (!is_null($this->content)) {
            echo $this->content;
        }
    }

    /**
     * Sends headers, content and cookies together
     *
     * @public
     * @return void
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendCookies();
        $this->sendContent();
    }
}