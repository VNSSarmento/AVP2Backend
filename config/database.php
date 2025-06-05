<?php

class Database {
    private static $instance = null;
    private $connection;
    
    private $host = 'localhost';
    private $database = 'transacoes_api';
    private $username = 'root';
    private $password = '';
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function criarTabelas() {
        $sql = "
            CREATE TABLE IF NOT EXISTS transacoes (
                id VARCHAR(36) PRIMARY KEY,
                valor DECIMAL(15,2) NOT NULL,
                dataHora DATETIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_dataHora (dataHora)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ";
        
        $this->connection->exec($sql);
    }
}

try {
    $database = Database::getInstance();
    $database->criarTabelas();
} catch (Exception $e) {
    error_log("Erro ao inicializar banco de dados: " . $e->getMessage());
}