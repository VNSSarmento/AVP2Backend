<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransacaoController {
    private $transacaoService;
    
    public function __construct() {
        $this->transacaoService = new TransacaoService();
    }
    
    public function criar(Request $request, Response $response) {
        try {
            // Obter dados do corpo da requisição
            $dados = $request->getParsedBody();
            
            // Verificar se dados foram enviados
            if (empty($dados)) {
                return $response->withStatus(400);
            }
            
            // Tentar criar a transação
            $this->transacaoService->criarTransacao($dados);
            
            // Retornar 201 Created
            return $response->withStatus(201);
            
        } catch (InvalidArgumentException $e) {
            // Dados inválidos - 422 Unprocessable Entity
            return $response->withStatus(422);
            
        } catch (Exception $e) {
            // Erro interno ou JSON inválido - 400 Bad Request
            error_log("Erro ao criar transação: " . $e->getMessage());
            return $response->withStatus(400);
        }
    }
    
    public function buscarPorId(Request $request, Response $response, array $args) {
        try {
            $id = $args['id'] ?? '';
            
            $transacao = $this->transacaoService->buscarTransacao($id);
            
            if ($transacao === null) {
                return $response->withStatus(404);
            }
            
            $response->getBody()->write(json_encode($transacao));
            return $response->withStatus(200);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar transação: " . $e->getMessage());
            return $response->withStatus(500);
        }
    }
    
    public function limparTodas(Request $request, Response $response) {
        try {
            $this->transacaoService->limparTodasTransacoes();
            return $response->withStatus(200);
            
        } catch (Exception $e) {
            error_log("Erro ao limpar transações: " . $e->getMessage());
            return $response->withStatus(500);
        }
    }
    
    public function limparPorId(Request $request, Response $response, array $args) {
        try {
            $id = $args['id'] ?? '';
            
            $removido = $this->transacaoService->limparTransacao($id);
            
            if (!$removido) {
                return $response->withStatus(404);
            }
            
            return $response->withStatus(200);
            
        } catch (Exception $e) {
            error_log("Erro ao limpar transação: " . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}