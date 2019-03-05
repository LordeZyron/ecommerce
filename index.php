<?php 
//Inicia sessão na página, usado para login
session_start();
//Sempre igual para criar as páginas
//====================================
//Traz as funções do composer, o que o projeto precisa
require_once("vendor/autoload.php");

//Namespaces, classes necessárias
use \Slim\Slim;

//Criando uma nova aplicação de Slim, uma nova rota, um caminho
$app = new Slim();

$app->config('debug', true);

require_once("site.php");
require_once("admin.php");
require_once("admin-users.php");
require_once("admin-categories.php");
require_once("admin-products.php");

//======================================


//Executa o site
$app->run();

 ?>