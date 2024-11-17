<?php

session_start();

define("BASE_URL", "http://localhost/SISTEMA-FINANCIERO/");


//url para incluir
define("CONFIG", BASE_URL . "config/");
define("PAGES", BASE_URL . "pages/");
define("CONTROLLERS", BASE_URL . "controller/");

define("CSS", BASE_URL . "assets/css/");
define("JS", BASE_URL . "assets/js/");
define("EXTRAS", BASE_URL . "assets/extras/");
define("IMG", BASE_URL . "assets/img/");

//opciones
define("APP_NAME", "BCP - Credito");

//rutas locales
// Define constantes para las rutas
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', 'C:\xampp\htdocs\SISTEMA-FINANCIERO' . DS);
define('PRODIMG', ROOT . 'pages' . DS . 'productos' . DS . 'img' . DS);


require_once "bd.php";
