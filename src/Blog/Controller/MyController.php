<?php

namespace Blog\Controller;

use Framework\Controller\Controller;

class MyController extends Controller
{
    public function myAction($id, $name)
    {
        echo   "<form method='post' action='/my_route/$id/$name'>
                    <input type='submit'>
                </form>";
    }

    public function  postAction($id, $name)
    {
        echo "ID: $id";
        echo "<br>";
        echo "Имя: $name";

    }

    public function simpleAction()
    {
        $this->registry['hello'] = 'Hello world!!!';

        echo $this->registry['hello'];

    }

}