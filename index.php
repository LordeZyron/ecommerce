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


$app->get("/admin/users", function(){

	//Verifica se o usuário está logado
	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(

		"users"=>$users
	));

});

$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

//Método para deletar um usuário
$app->get("/admin/users/:iduser/delete", function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

//Método para editar um usuário
$app->get('/admin/users/:iduser', function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new PageAdmin();

	$page->setTpl("users-update", array(

		"user"=>$user->getValues()
	));

});

//Método para criar um usuário
$app->post("/admin/users/create", function () {

 	User::verifyLogin();

	$user = new User();

 	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

 	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 		"cost"=>12

 	]);

 	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
 	exit;

});


$app->post("/admin/users/:iduser", function($iduser) {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;

});





//Executa o site
$app->run();

 ?>