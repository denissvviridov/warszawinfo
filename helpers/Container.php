<?php


class Container
{

    public function twig(): \Twig\Environment
    {

        $loader = new \Twig\Loader\FilesystemLoader('views/');
        return new \Twig\Environment($loader);
    }

    public function connectDB(): PDO
    {
////
        $host = 'mysql8';
        $dbname = '37700246_news';
        $username = '37700246_news';
        $password = 'qwerty1234!';

//        $host = 'localhost';
//        $dbname = 'news';
//        $username = 'root';
//        $password = 'qwerty1234';


        return new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    }


}