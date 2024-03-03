<?php


$routes = [

    //client
    'home' => 'MainController|index',
    'post/read/.*' => 'MainController|singlePost',

    'likes' => 'MainController|handlerLike',
    'create_comment' => 'MainController|createComment',

    'posts_show' => 'MainController|postsShow',
    'show_more' => 'MainController|showMoreCategoryPost',
    'posts/category/.*' => 'MainController|postsOnCategory',
    'more_comments' => 'MainController|showMoreComments',

    'error' => 'MainController|error',
    'load_more' => 'MainController|index',


    //admin
    'admin' => 'AdminController|index',
    'admin/login' => 'AdminController|login',
    'admin/category' => 'AdminController|category',
    'admin/telegram' => 'AdminController|telegram',
    'admin/exit' => 'AdminController|exit',

];