<?php
$router = new Framework\Router;

// Homes Routes
$router->add('/', ["controller" => "homes", "method" => "index"]);
$router->add('/register', ["controller" => "homes", "method" => "register"]);
$router->add('/forgot-password', ["controller" => "homes", "method" => "forgot-password"]);
$router->add('/register-new-user', ["controller" => "homes", "method" => "register-new-user"]);
$router->add('/log-in-user', ["controller" => "homes", "method" => "log-in-user"]);
$router->add('/recover-account', ["controller" => "homes", "method" => "recover-account"]);
$router->add('/logout', ["controller" => "homes", "method" => "log-out-user"]);
$router->add('/contact-us', ["controller" => "homes", "method" => "contact-us"]);
$router->add('/contact', ["controller" => "homes", "method" => "contact"]);
$router->add('/about-us', ["controller" => "homes", "method" => "about-us"]);
$router->add('/404', ["controller" => "homes", "method" => "e404"]);
$router->add('/500', ["controller" => "homes", "method" => "e500"]);
$router->add('/test', ["controller" => "homes", "method" => "test"]);

// Admin/Users Routes
$router->add('/admin/{controller}/{method}', ["namespace" => "Admin"]);

// Users Routes
$router->add("/dashboard", ["controller" => "users", "method" => "dashboard"]);
$router->add("/users/profile/{username:\w+([-+.+@']\w+)*}", ["controller" => "users", "method" => "profile"]);
$router->add('/users/word/{word:[\w-]+}', ["controller" => "users", "method" => "word"]);
$router->add("/users/emailexists/{email:\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*}", ["controller" => "users", "method" => "emailExists"]);
$router->add('/users', ["controller" => "users", "method" => "index", "middleware" => "message|message|message"]);
$router->add('/users/show', ["controller" => "users", "method" => "show"]);

// Generic Controllers CRUD Routes
$router->add('/{controller}/show/{id:\d+}', ["method" => "show", "middleware"=>"deny"]);
$router->add('/{controller}/edit/{id:\d+}', ["method" => "edit"]);
$router->add('/{controller}/update/{id:\d+}', ["method" => "update", "form"=> "post"]);
$router->add('/{controller}/delete/{id:\d+}', ["method" => "delete"]);
$router->add('/{controller}/destroy/{id:\d+}', ["method" => "destroy", "form" => "post"]);

// Generic Routes
$router->add("/{controller}/{method}");
// $router->add("{username:\w+([-+.+@']\w+)*}", ["controller" => "users", "method" => "profile"]);
// $router->add("/{controller}/{method}/{id:\d+}");
// $router->add('/{title}/{id:\d+}/{page:\d+}', ["controller" => "users", "method" => "showPage"]);

return $router;