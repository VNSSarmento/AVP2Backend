<?php
// src/models/Transacao.php - Modelo de dados da Transação

class Transacao {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($id, $valor, $dataHora) {
        try {
            $sql = "INSERT INTO transacoes (id, valor, dataHora) VALUES (:id, :valor, :dataHora)";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':id' => $id,
                ':valor' => $valor,
                ':dataHora' => $dataHora
            ]);
        } catch (PDOException $e) {
            // Se for erro de chave duplicada
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT id, valor, dataHora FROM transacoes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $resultado = $stmt->fetch();
        
        if ($resultado) {
            return [
                'id' => $resultado['id'],
                'valor' => (float) $resultado['valor'],
                'dataHora' => $resultado['dataHora']
            ];
        }
        
        return null;
    }
    
    public function limparTodas() {
        $sql = "DELETE FROM transacoes";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
    
    public function limparPorId($id) {
        $sql = "DELETE FROM transacoes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->rowCount() > 0;
    }
    
    public function buscarUltimos60Segundos() {
        $sql = "SELECT valor FROM transacoes 
                WHERE dataHora >= DATE_SUB(NOW(), INTERVAL 60 SECOND)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $resultados = $stmt->fetchAll();
        $valores = [];
        
        foreach ($resultados as $resultado) {
            $valores[] = (float) $resultado['valor'];
        }
        
        return $valores;
    }
    
    public function verificarIdExiste($id) {
        $sql = "SELECT COUNT(*) as count FROM transacoes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $resultado = $stmt->fetch();
        return $resultado['count'] > 0;
    }
}