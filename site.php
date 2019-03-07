<?php

use Hcode\Page;
use Hcode\Model\Product;
use Hcode\Model\Category;

//Executa função juntando o header, body e footer dos arquivos html, por fim o destrutor
$app->get('/', function() {
    
	$products = Product::listAll();

	$page = new Page();

	$page->setTpl("index", [
		'products' => Product::checkList($products)

	]);	
});

$app->get("/categories/:idcategory", function($idcategory){

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new Page();

	$page->setTpl("category", ['category'=>$category->getValues(),
		'products'=>Product::checkList($category->getProducts())

	]);

});



?>