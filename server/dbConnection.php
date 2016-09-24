<?php

class dbConnection {
	
	// Variavel de conexão com o banco de dados
	private $conn;
	
	// Construtor
	function __construct() {
		// Conectando com o banco de dados
		$this->connect();
	}
	
	// Destrutor
	function __destruct() {
		// Fecha a conexão quando o objeto é destruído
		// Como foi utizado PDO para evitar injeção de SQL a conexão só está aberta enquanto existir o objeto de conexão
		$conn = null;
	}
	
	/**
     * Função para conectar com banco de dados
     */
	function connect() {
		// Importar as variaves de conexão
		require_once __DIR__ . '/dbConfig.php';
		
		// Conectando com o banco de dados
		try {
            $this->conn = new PDO("mysql:host=". DB_HOST . ";" . "dbname=" . DB_DATABASE, DB_USUARIO, DB_SENHA);
            
            // Setar o PDO para o modo de erro exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Desativar modo emulado de querys para evitar injeção de sql de segunda ordem.
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch(PDOException $e) {
            echo "Erro na conexao com o baco de dados: " . $e->getMessage();
        }
		
		// Retornar o objeto da conexão
		return $this->conn;
	}
}