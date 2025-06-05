<?php

class EstatisticaService {
    private $transacaoModel;
    
    public function __construct() {
        $this->transacaoModel = new Transacao();
    }
    
    public function calcularEstatisticas() {

        $valores = $this->transacaoModel->buscarUltimos60Segundos();
        
        if (empty($valores)) {
            return [
                'count' => 0,
                'sum' => 0.0,
                'avg' => 0.0,
                'min' => 0.0,
                'max' => 0.0
            ];
        }
        
        $count = count($valores);
        $sum = array_sum($valores);
        $avg = $sum / $count;
        $min = min($valores);
        $max = max($valores);
        
        return [
            'count' => $count,
            'sum' => round($sum, 2),
            'avg' => round($avg, 2),
            'min' => round($min, 2),
            'max' => round($max, 2)
        ];
    }
}