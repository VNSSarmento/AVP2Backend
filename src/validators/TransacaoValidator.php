<?php

class TransacaoValidator {
    
    public static function validarDados($dados) {
        $erros = [];
        
        if (!isset($dados['id']) || empty($dados['id'])) {
            $erros[] = 'Campo id é obrigatório';
        }
        
        if (!isset($dados['valor'])) {
            $erros[] = 'Campo valor é obrigatório';
        }
        
        if (!isset($dados['dataHora']) || empty($dados['dataHora'])) {
            $erros[] = 'Campo dataHora é obrigatório';
        }
        
        if (!empty($erros)) {
            return $erros;
        }
        
        if (!self::validarUUID($dados['id'])) {
            $erros[] = 'ID deve seguir o padrão UUID';
        }
        
        if (!is_numeric($dados['valor'])) {
            $erros[] = 'Valor deve ser numérico';
        } elseif ($dados['valor'] < 0) {
            $erros[] = 'Valor não pode ser negativo';
        }
        
        $dataHora = self::validarDataHora($dados['dataHora']);
        if ($dataHora === false) {
            $erros[] = 'DataHora deve estar no formato ISO 8601';
        } elseif ($dataHora > new DateTime()) {
            $erros[] = 'DataHora não pode ser no futuro';
        }
        
        return $erros;
    }
    
    private static function validarUUID($uuid) {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($pattern, $uuid);
    }
    
    private static function validarDataHora($dataHora) {
        try {

            $dt = new DateTime($dataHora);
            
            $iso8601Pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d{3})?(?:Z|[+-]\d{2}:\d{2})$/';
            if (!preg_match($iso8601Pattern, $dataHora)) {
                return false;
            }
            
            return $dt;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public static function formatarDataHoraBanco($dataHora) {
        try {
            $dt = new DateTime($dataHora);
            return $dt->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            return null;
        }
    }
    
    public static function formatarDataHoraResposta($dataHora) {
        try {
            $dt = new DateTime($dataHora);
            return $dt->format('c'); // ISO 8601
        } catch (Exception $e) {
            return $dataHora;
        }
    }
}