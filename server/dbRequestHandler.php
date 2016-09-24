<?php
/*
 * Script PHP principal que ira gerenciar todas as requisições feita pelo aplicativo
 * Todas as requisições serão feitas com HTTP POST
 * As respostas às requisições serão feitas em JSON
 */
 
 // Negar requisições que não sejam do tipo POST
 if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'HEAD' ||
     $_SERVER['REQUEST_METHOD'] == 'DELETE' || $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
    $resultado['resultado'] = "Falha";
    $resultado['mensagem'] = "Apenas requisicoes do tipo POST sao permitidas"; 
 }
 else {
     
    // Array para gerar a reposta em JSON
    $resposta = array();
     
    // Verificar se o parametro ação(cadastro, login, etc) foi setado
    if (isset($_POST['acao'])) {
         
        // Guardar em uma variavel com nome menor a ação requisistada
        $acao = $_POST['acao'];
         
        // Incluir a classe que faz a conexão com o banco de dados
        require_once __DIR__ . '/dbConnection.php';
        
        // Incluir a classe que criptografa/descriptografa as senhas do banco de dados
        require_once __DIR__ . '/bcrypt.php';
     
        // Fazendo a conexão com o banco de dados
        $db = new dbConnection();
         
        //Verificar qual ação foi requisitada e executá-la
        if ($acao == "login") {
             
            // Verificar se os parametros login e senha foram setados
            if (isset($_POST['login']) && isset($_POST['senha'])) {
                
                // Guardar em variaveis mais legiveis o login e senha
                $login = $_POST['login'];
                $senha = $_POST['senha'];
                 
                // Validar login e senha
                $conn = $db->connect();
                $stmt = $conn->prepare("SELECT login, senha FROM usuarios WHERE login = :login LIMIT 1");
                $stmt->execute(array('login' => $login));
                $row = $stmt->fetch();
                 
                if (is_array($row) && Bcrypt::check($senha, $row['senha'])) {
                    $resultado['resultado'] = "Sucesso";
                    $resultado['mensagem'] = "Login e senha corretos";
                }
                else {
                    $resultado['resultado'] = "Falha";
                    $resultado['mensagem'] = "Login ou senha incorretos";
                }
            }
            else {
                $resultado['resultado'] = "Falha";
                $resultado['mensagem'] = "Parametro(s) não setado(s)";
            }
         }
         else if ($acao == "cadastro") {
             
            // Verificar se os parametros login, email e senha foram setados 
            if (isset($_POST['login']) && isset($_POST['email']) && isset($_POST['senha'])) {
                
                // Guardar em variaveis mais legiveis o login, email e senha
                $login = $_POST['acao'];
                $email = $_POST['email'];
                $senha = $_POST['senha'];
                
                // Criptografar a senha
                $hash = Bcrypt::hash($senha);
                
                // Inserir registro no banco de dados
                $conn = $db->connect();
                $stmt = $conn->prepare("INSERT INTO usuarios(login, email, senha) VALUES(:login, :email, :senha)");
                $stmt->execute(array('login' => $login, 
                                     'email' => $email,
                                     'senha' => $hash));
                
                // Verificar se a inserção foi realizada com sucesso
                if ($stmt) {
                    $resultado['resultado'] = "Sucesso";
                    $resultado['mensagem'] = "Registro inserido no banco de dados";
                }
                else {
                    $resultado['resultado'] = "Falha";
                    $resultado['mensagem'] = "Possivel falha na comunição com o banco de dados. Registro não inserido no banco de dados";
                }
            }
            else {
                $resultado['resultado'] = "Falha";
                $resultado['mensagem'] = "Parametro(s) não setado(s)";
            }
         }
         else {
            $resultado['resultado'] = "Falha";
            $resultado['mensagem'] = "Parametro(s) invalidos(s)";
         }
    }
    else {
        $resultado['resultado'] = "Falha";
        $resultado['mensagem'] = "Parametro(s) nao setado(s)";
    } 
}

// Retornar o resultado da requisição em JSON para o aplicativo
echo json_encode($resultado);