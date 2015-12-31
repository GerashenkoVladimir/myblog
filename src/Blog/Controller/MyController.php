<?php

namespace Blog\Controller;

class MyController
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
        echo 'Hello World!!!';
    }

}