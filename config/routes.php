<?php
$router = new Framework\Router;

// Homes Routes
$router->add('/', ["controller" => "homes", "method" => "index"]);
$router->add('/register', ["controller" => "homes", "method" => "register", "auth"=>false]);
$router->add('/forgot-password', ["controller" => "homes", "method" => "forgot-password", "auth"=>false]);
$router->add('/register-new-user', ["controller" => "homes", "method" => "register-new-user", "form"=> "post"]);
$router->add('/log-in-user', ["controller" => "homes", "method" => "log-in-user", "form"=> "post"]);
$router->add('/recover-account', ["controller" => "homes", "method" => "recover-account", "form"=> "post"]);
$router->add("/reset/password/{email:\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*}/{hash:[a-zA-Z0-9]{64}}", ["controller" => "homes", "method" => "reset-password", "auth"=>false]);
$router->add("/password/reset/{email:\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*}/{hash:[a-zA-Z0-9]{64}}", ["controller" => "homes", "method" => "password-reset", "form"=> "post"]);
$router->add("/activate/account/{email:\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*}/{hash:[a-zA-Z0-9]{64}}", ["controller" => "homes", "method" => "activate-account"]);
$router->add('/logout', ["controller" => "homes", "method" => "log-out-user"]);
$router->add('/contact-us', ["controller" => "homes", "method" => "contact-us", "form"=> "post"]);
$router->add('/contact', ["controller" => "homes", "method" => "contact"]);
$router->add('/about-us', ["controller" => "homes", "method" => "about-us"]);
$router->add('/404', ["controller" => "homes", "method" => "e404"]);
$router->add('/500', ["controller" => "homes", "method" => "e500"]);
$router->add('/test', ["controller" => "homes", "method" => "test"]);

// Admin/Users Routes
$router->add('/admin/{controller}/{method}', ["namespace" => "Admin"]);

// Users Routes
$router->add("/dashboard", ["controller" => "users", "method" => "dashboard"]);
$router->add("/profile/update", ["controller" => "users", "method" => "update-profile", "auth"=>true]);
$router->add("/update/profile", ["controller" => "users", "method" => "profile-update", "form" => "post"]);
$router->add("/profile/view", ["controller" => "users", "method" => "view-profile", "auth"=>true]);
$router->add("/update/password", ["controller" => "users", "method" => "password-update", "auth"=>true]);
$router->add("/password/update", ["controller" => "users", "method" => "update-password", "form" => "post"]);
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