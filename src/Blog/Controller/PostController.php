<?php

namespace Blog\Controller;

use Blog\Model\Post;
use Framework\Controller\Controller;
use Framework\Exception\BadTokenException;
use Framework\Exception\DatabaseException;
use Framework\Exception\HttpNotFoundException;
use Framework\Exception\RequestExceptions;
use Framework\Request\Request;
use Framework\Response\Response;
use Framework\Validation\Validator;

/**
 * Class PostController
 * @package Blog\Controller
 */
class PostController extends Controller
{
    /**
     * Index Action
     *
     * @access public
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('index.html', array('posts' => Post::find('all')));
    }

    /**
     * Get post action
     *
     * @access public
     *
     * @param int $id Post id
     *
     * @return Response
     */
    public function getPostAction($id)
    {
        return new Response('Post: #' . $id);
    }

    /**
     * Add post action
     *
     * @access public
     *
     * @return Response|\Framework\Response\ResponseRedirect
     * @throws BadTokenException
     */
    public function addAction()
    {
        if ($this->getRequest()->isPost()) {
            if (!$this->getRequest()->checkToken('token')) {
                throw new BadTokenException('You do not have permission for this operation !', 403);
            }
            try {
                $post = new Post();
                $date = new \DateTime();
                $post->title = $this->getRequest()->post('title');
                $post->content = trim($this->getRequest()->post('content'));
                $post->date = $date->format('Y-m-d H:i:s');

                $validator = new Validator($post);
                if ($validator->isValid()) {
                    $post->save();

                    return $this->redirect($this->generateRoute('home'), 'The data has been saved successfully');
                } else {
                    $error = $validator->getErrors();
                }
            } catch (RequestExceptions $e) {
                $error = $e->getMessage();
            } catch (DatabaseException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render(
            'add.html',
            array('action' => $this->generateRoute('add_post'), 'errors' => isset($error) ? $error : null)
        );
    }

    /**
     * Show post action
     *
     * @param int $id Post id
     *
     * @return Response
     * @throws HttpNotFoundException
     */
    public function showAction($id)
    {
        if (!$post = Post::find((int)$id)) {
            throw new HttpNotFoundException('Page Not Found!');
        }

        return $this->render('show.html', array('post' => $post));
    }
}