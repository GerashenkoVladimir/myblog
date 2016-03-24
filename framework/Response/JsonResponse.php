<?php
namespace Framework\Response;

/**
 * Class JsonResponse
 * @package Framework\Response
 */
class JsonResponse extends Response
{
    /**
     * JsonResponse  constructor
     *
     * @access public
     *
     * @param string $content
     * @param array  $headers
     * @param int    $statusCode
     */
    public function __construct($content = '', $headers = array(), $statusCode = 200)
    {
        parent::__construct(json_encode($content), $headers, $statusCode);
    }
}