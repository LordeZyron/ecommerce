<?php  

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;

class Category extends Model {

	
	public static function listAll() {


		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
	}

	public function save() {

		$sql = new Sql();
		
		$results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(

			":idcategory"=>$this->getidcategory(),
			":descategory"=>$this->getdescategory()
		));

		$this->setData($results[0]);

		Category::updateFile();

	}

	public function get($idcategory) {

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
			":idcategory"=>$idcategory

		]);

		$this->setData($results[0]);
	}

	public function delete() {

		$sql = new Sql();

		$sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [':idcategory'=>$this->getidcategory()

		]);

		Category::updateFile();
	}

	//Método para atualizar dinamicamente categorias no banco de dados
	public static function updateFile() {

		$categories = Category::listAll();

		$html = [];

		foreach ($categories as $row) {
			array_push($html,'<li><a href="/categories/' .$row['idcategory']. '">' .$row['descategory']. '</a></li>' );
		}

    //implode converte array pra string e explode converte string pra array
	file_put_contents($_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html));

		}

		public function getProducts($related = true){

			$sql = new Sql();

			if ($related === true) {

				return $sql->select("
					SELECT * FROM tb_products WHERE idproduct IN(
					SELECT a.idproduct
					FROM tb_products a
					INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
					);
			", [
				':idcategory'=>$this->getidcategory()
			]);


	} else {

				return $sql->select("

					SELECT * FROM tb_products WHERE idproduct NOT IN(
					SELECT a.idproduct
					FROM tb_products a
					INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
					);

			" , [
				':idcategory'=>$this->getidcategory()
			]);

	}

}

	//Função para adicionar produtos em uma categoria
	public function addProduct(Product $product) {

		$sql = new Sql();

		$sql->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES (:idcategory, :idproduct)", [
			"idcategory"=>$this->getidcategory(),
			"idproduct"=>$product->getidproduct()

		]);

	}

	//Função para excluir produtos de uma categoria
	public function removeProduct(Product $product) {

		$sql = new Sql();

		$sql->query("DELETE FROM tb_productscategories WHERE idcategory= :idcategory AND idproduct = :idproduct", [
			"idcategory"=>$this->getidcategory(),
			"idproduct"=>$product->getidproduct()

		]);

	}

}
	

?>