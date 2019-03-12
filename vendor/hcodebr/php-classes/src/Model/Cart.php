<?php  

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
use \Hcode\Mailer;
use \Hcode\Model\User;

class Cart extends Model {

	const SESSION = "Cart";

	public static function getFromSession(){

		$cart = new Cart();

		//Se a sessão existir e se o id for maior que zero, significa que o carrinho 
		//já foi inserido no banco e já está em uma sessão
		if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0) {

			$cart->get((int)$_SESSION[Cart::SESSION]['idcart']);
	}

	else {

		$cart->getFromSessionID();
		//Se não for maior que zero
		if(!(int)$cart->getidcart() > 0){

			$data = [
				'dessessionid'=>session_id()
			];

			//Se ele não está em uma rota admin é false, ele está logado como cliente,
			//no carrinho de compras
			//Se ele está logado, o getFromSession funciona, e traz o usuário
			if (User::checkLogin(false)) {

				$user = User::getFromSession();
				//Traz o id do usuário
				$data['iduser'] = $user->getiduser();
			}

			$cart->setData($data);

			$cart->save();

			$cart->setToSession();

		}
	}

	return $cart;

}

	public function setToSession(){

		$_SESSION[Cart::SESSION] = $this->getValues();

	}



	public function getFromSessionID(){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_carts WHERE dessessionid = :dessessionid", [

			':dessessionid'=>session_id()

		]);

		if (count($results) > 0) {

			$this->setData($results[0]);
		}
	}



	public function get($idcart){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_carts WHERE idcart = :idcart", [

			':idcart'=>$idcart

		]);

		if (count($results) > 0) {

			$this->setData($results[0]);

		}
	}


	public function save(){

		$sql = new Sql();

		$results = $sql->select("CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)", [
			':idcart'=>$this->getidcart(),
			':dessessionid'=>$this->getdessessionid(),
			':iduser'=>$this->getiduser(),
			':deszipcode'=>$this->getdeszipcode(),
			':vlfreight'=>$this->getvlfreight(),
			':nrdays'=>$this->getnrdays()


		]);

			$this->setData($results[0]);

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