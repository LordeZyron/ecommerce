<?php 
//Inicia sessão na página, usado para login
session_start();
//Sempre igual para criar as páginas
//====================================
//Traz as funções do composer, o que o projeto precisa
require_once("vendor/autoload.php");

//Namespaces, classes necessárias
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\USer;

//Criando uma nova aplicação de Slim, uma nova rota, um caminho
$app = new Slim();

$app->config('debug', true);

//======================================

//Executa função juntando o header, body e footer dos arquivos html, por fim o destrutor
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

$app->get('/admin', function() {
    
	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->get('/admin/login/', function() {

	//Desabilitando o header e footer padrão
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
]);

	$page->setTpl("login");

});

$app->post('/admin/login/', function () {

	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;
});

$app->get('/admin/logout/', function() {

	User::logout();

	header("Location: /admin/login");
	exit;

});

//Executa o site
$app->run();

 ?>