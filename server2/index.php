<?php
require_once 'connection.php';
require_once 'bcrypt.php';

class User {

	private $db;
	private $connection;

	function __construct() {
		$this -> db = new DB_Connection();
		$this -> connection = $this->db->getConnection();
	}

	public function does_user_exist($login, $password) {

		$query = "SELECT login, senha FROM usuarios WHERE login = ? LIMIT 1";
		$query->bind_param("s", $login);

		$result = mysqli_query($this->connection, $query);
		
		if(mysqli_num_rows($result) == 1){
			$json['sucesso'] = ' Bem vindo '.$login;
			echo json_encode($json);
			mysqli_close($this->connection);
		}
		else{
			$query = "INSERT INTO usuarios(login, email, senha) values (?, ?, ?)";
			$query->bind_param("sss", $login, "teste@email.com", $senha);
			
			$result= mysqli_query($this->connection, $query);
			
			if($result == 1 ){
				$json['sucesso'] = 'Usuario cadastrado';
			}else{
				$json['erro'] = 'Senha ou login incorreto';
			}
			echo json_encode($json);
			mysqli_close($this->connection);
		}
	}
}

$user = new User();
if(isset($_POST['login'], $_POST['senha'])) {
	$email = $_POST['login'];
	$password = $_POST['senha'];

	if(!empty($login) && !empty($senha)){

		$hash = Bcrypt::hash($senha);
		$user->does_user_exist($login, $password);

	}
	else{
		echo json_encode("Todos os parametros precisam ser preenchidos");
	}

}
