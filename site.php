<?php

use Hcode\Page;

//Executa função juntando o header, body e footer dos arquivos html, por fim o destrutor
$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});



?>