<?php 

//Sempre igual para criar as páginas
//====================================
//Traz as funções do composer, o que o projeto precisa
require_once("vendor/autoload.php");

//Namespaces, classes necessárias
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;

//Criando uma nova aplicação de Slim, uma nova rota, um caminho
$app = new \Slim\Slim();

$app->config('debug', true);

//======================================

//Executa função juntando o header, body e footer dos arquivos html, por fim o destrutor
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

$app->get('/admin', function() {
    
	$page = new PageAdmin();

	$page->setTpl("index");

});

//Executa o site
$app->run();

 ?>