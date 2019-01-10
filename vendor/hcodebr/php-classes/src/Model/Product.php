<?php 

namespace Hcode\Model;
use \Hcode\DB\SQL;
use \Hcode\Model;
use \Hcode\Mailer;

class Product extends Model {


	

	public static function listAll()	
	{

		$sql =  new  Sql();

		return $sql ->select("SELECT * FROM tb_products ORDER BY desproduct");
	}

	public function save()
	{

		$sql = new  Sql();

		$results =   $sql->select("CALL sp_products_save(:idproduct, :desproduct,:vlprice,:vlwidth,:vlheight,:vllength,:vlweight,:desurl)",array(
			":idproduct"=>$this->getidproduct(),
			":desproduct"=>$this->getdesproduct(),
			":vlprice"=>$this->getvlprice(),
			":vlwidth"=>$this->getvlwidth(),
			":vlheight"=>$this->getvlheight(),
			":vllength"=>$this->getvllength(),
			":vlweight"=>$this->getvlweight(),
			":desurl"=>$this->getdesurl()
		));

  
        $this->setData($results[0]);

     

	}


	public function get($idproduct)
	{

		$sql = new  Sql();


		$results = $sql->select("SELECT  * FROM   tb_products WHERE idproduct   =  :idproduct",[
			"idproduct" =>$idproduct
		]
		
		);
		$this->setData($results[0]);


	}

	public function delete()
	{

		$sql = new Sql();

		$sql->query("DELETE FROM tb_products  WHERE  idproduct = :idproduct",[
				"idproduct" =>$this->getidproduct()
		]);

		
	}

	//metodo checkphoto para detectar se foto ou  se vai exibir foto padrao 
	public function  checkPhoto()
	{

		if (file_exists($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.
			"res".DIRECTORY_SEPARATOR.
			"site".DIRECTORY_SEPARATOR.
			"img".DIRECTORY_SEPARATOR.
			"products".DIRECTORY_SEPARATOR.
			$this->getidproduct().".jpg")){

			$url =  "/res/site/img/products/". $this->getidproduct().".jpg";
		}else  {

			$url =  "/res/site/img/product.jpg";
		}
		return  $this->setdesphoto($url);

	}
	//reescrevendo metodo getvalue da classe pai para setar a foto
	public function getValues()
	{

		$this->checkPhoto();

		$values = parent::getValues();

		return $values;
	}


	public function setPhoto($file)
	{


		$extension= explode('.', $file['name']);
		$extension = end($extension);

		switch ($extension) {
			case "jpg":
			case "jpeg":
			//tmp_name nome temporario que fica no server os arquivos de upload
				$image= imagecreatefromjpeg($file["tmp_name"]);
			break;

			case "gif":	
				$image= imagecreatefromgif($file["tmp_name"]);
			break;

			case "png":
				$image= imagecreatefrompng($file["tmp_name"]);
			break;
		}

		$dist = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.
			"res".DIRECTORY_SEPARATOR.
			"site".DIRECTORY_SEPARATOR.
			"img".DIRECTORY_SEPARATOR.
			"products".DIRECTORY_SEPARATOR.
			$this->getidproduct().".jpg";

		imagejpeg($image,$dist);
		imagedestroy($image);
		$this->checkPhoto();

	}


	
}




 ?>