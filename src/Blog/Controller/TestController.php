<?php

namespace Blog\Controller;

use Framework\Controller\Controller;
use Framework\Response\JsonResponse;

/**
 * Class TestController
 * @package Blog\Controller
 */
class TestController extends Controller
{
    /**
     * Test redirect action
     *
     * @access public
     *
     * @return \Framework\Response\ResponseRedirect
     */
    public function redirectAction()
    {
        return $this->redirect('/');
    }

    /**
     * Test json response
     *
     * @access public
     *
     * @return JsonResponse
     */
    public function getJsonAction()
    {
        return new JsonResponse(array('body' => 'Hello World'));
    }
} 