<?php
require_once "userService.php";

$userService = new userService();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$dados = json_decode(file_get_contents("php://input"));

	if (isset($dados->operacao)) {
		$operacao = $dados->operacao;

		if (!empty($operacao)) {
			if ($operacao == "cadastro") {

				if (isset($dados->usuario) && !empty($dados->usuario) && isset($dados->usuario->login) && isset($dados->usuario->email) && isset($dados->usuario->senha)) {

					$usuario = $dados->usuario;
					$login = $dados->login;
					$email = $dados->email;
					$senha = $dados->senha;

					if ($userService->ehEmailValido($email)) {
						echo $userService->cadastrarUsuario($login, $email, $senha);
					}
					else {
						echo $userService->jsonResult("Falha", "Email invalido");
					}
				}
				else {
					echo $userService->jsonResult("Falha", "Parametro(s) invalido(s)");
				}

			}
			else if ($operacao == "login") {
				if (isset($dados->usuario) && !empty($dados->usuario) && isset($dados->usuario->login) && ($dados->usuario->senha)) {

					$usuario = $dados->usuario;
					$login = $dados->login;
					$senha = $dados->senha;

					echo $userService->login($login, $senha);
				}
				else {
					echo $userService->jsonResult("Falha", "Parametro(s) invalido(s)");
				}
			}
			else if ($operacao == "alterarSenha") {
				if(isset($dados->usuario) && !empty($dados->usuario) && isset($dados->usuario->login) && isset($dados->usuario->senhAntiga) && isset($dados->user->senhaNova)){

					$usuario = $dados->usuario;
					$login = $dados->login;
					$senhaNova = $dados->senhaNova;
					$senhAntiga = $dados->senhAntiga;

					echo $userService.jsonResult($login, $senhaNova, $senhAntiga);
				}
				else {
					echo $userService->jsonResult("Falha", "Parametro(s) invalido(s)");
				}
			}
			else {
				echo $userService.jsonResult("Falha", "Parametro(s) invalido(s)");
			}
		}
		else {
			echo $userService.jsonResult("Falha", "Parametro(s) vazio(s)");
		}
	}
	else {
		echo $userService.jsonResult("Falha", "Parametro(s) invalido(s)");
	}
}
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	echo $userService.jsonResult("Falha", "Passagem de parametros via GET nao suportada");
}