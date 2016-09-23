<?php
require_once("bcrypt.php"); // Necessário para fazer a criptografia e checagem das senhas.

// Classe que gerencia a conexão com o banco de dados do aplicativo e executa comandos no mesmo (INSERT, UPDATE, DELETE)
class dbService {

	// Parâmetros de configuração
	private $host;
	private $usuario;
	private $db = 'chatServer';
	private $senha = '123';
	private $con;

	public function __construct() {
		$this->$host = getenv('IP');
		$this->$usuario = getenv('C9_USER');

		$this -> con = new PDO("mysql:host=".$this -> host.";dbname=".$this -> db, $this -> usuario, $this -> senha) or die("Erro na conexão com o banco de dados!");
	}

	// Método que insere um usuário no banco de dados e retorna true se conseguiu e false caso contrário
	public function inserirUsuario($login, $email, $senha) {

		$sql = "INSERT INTO usuarios(login, email, senha) VALUES (:login, :email, :senha)";
		$query = $this->$con->prepare($sql);

		$query->execute(array('login' => $login, 
							  'email' => $email, 
							  'senha' => $senha));

		if ($query) {
			return true;
		}
		else {
			return false;
		}
	}

	public function usuarioExiste($login) {

		$sql = "SELECT login FROM usuarios WHERE login = :login LIMIT 1";
		$query = $this->$con->prepare($sql);

		$query->execute(array('login' => $login));

		if($query) {
			return true;
		}
		else {	
			return false;
		}
	}

	// Método que verifica o login e retorna um array com as informações do usuário se conseguiu e false caso contrário
	public function verificaLogin($login, $senha) {
		
		$sql = "SELECT login, senha FROM usuarios WHERE login = :login LIMIT 1";
		$query = $this->$con->prepare($sql);

		$query->execute(array('login' => $login));

		$resultado = $query->fetch();

		if(Bcrypt::check($senha, $resultado['senha'])) {
			$usuario['id'] = $resultado['id'];
			$usuario['login'] = $resultado['login'];
			$usuario['email'] = $resultado['email'];

			return $user;
		}
		else {	
			return false;
		}
	}

	// Método que atualiza a senha de um usuário no banco de dados e retorna true se conseguiu e false caso contrário
	public function atualizarSenha($login, $senha) {

		$sql = "UPDATE usuarios SET senha = :senha WHERE login = :login";
		$query = $this->$con->prepare($sql);

		$query->execute(array('login' => $login),
							  'senha' => $senha);

		if($query) {
			return true;
		}
		else {	
			return false;
		}
	}
}

