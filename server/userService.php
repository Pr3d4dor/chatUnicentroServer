<?php
require_once("dbService.php"); // Necessário para fazer a conexao ao banco de dados.

// Classe que gerencia o login/registro de usuários no aplicativo e retorna as resposta no formato JSON

class userService {
	private $dbService;

	function __construct() {
		$this->$dbService = new dbService();
	}

	function cadastrarUsuario($login, $email, $senha) {
		if (!empty($login) && !empty($email) && !empty($senha)) {
			if ($dbService->usuarioExiste($login)) {
				return jsonResult("Falha", "Usuario ja esta cadastrado");
			}
			else {

				$resultado = $dbService->inserirUsuario($login, $email, $senha);

				if ($resultado) {
					return jsonResult("Sucesso", "Usuario registrado"); 
				}
				else {
					return jsonResult("Falha", "Erro na insercao do registro no banco de dados");
				}
			}
		}
		else {
			return jsonResult("Falha", "Parametro vazio");
		}
	}

	public function login($login, $senha) {
		if (!empty($login) && !empty($senha)) {
			if (!$dbService->verificaLogin($login, $senha)) {
				return jsonResult("Falha", "Login ou senha incorretos");
			}
			else {
				return jsonResult("Sucesso", "Login e senha corretos");
			}
		}
		else {
			return jsonResult("Falha", "Parametro(s) vazio(s)");
		}
	}

	public function alterarSenha($login, $senha, $senhaAntiga) {
		if (!empty($login) && !empty($senha)) { 
			if (!dbService->verificaSenha($login, $senha)) {
				return jsonResult("Falha", "Senha antiga incorreta");
			}
			else {

				$resultado = $dbService->atualizarSenha($login, $senha);

				if ($resultado) {
					return jsonResult("Sucesso", "Senha atualizada");
				}
				else {
					return jsonResult("Falha", "Erro na atualizacao do registro no banco de dados");
				}
			}
		}
		else {
			return jsonResult("Falha", "Parametro(s) vazio(s)");
		}
	}

	public function ehEmailValido($email){
  		return filter_var($email, FILTER_VALIDATE_EMAIL);
	} 

	public function jsonResult($resultado, $mensagem) {
		$resposta["resultado"] = $resultado;
		$resposta["mensagem"] = $mensagem;

		return json_encode($resposta);
	}

}