<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/controllers/TransacaoController.php';
require_once __DIR__ . '/src/controllers/EstatisticaController.php';
require_once __DIR__ . '/src/models/Transacao.php';
require_once __DIR__ . '/src/services/TransacaoService.php';
require_once __DIR__ . '/src/services/EstatisticaService.php';
require_once __DIR__ . '/src/validators/TransacaoValidator.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$app->addErrorMiddleware(true, true, true);

$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    return $response->withHeader('Content-Type', 'application/json');
});

$transacaoController = new TransacaoController();
$estatisticaController = new EstatisticaController();

// Rotas

// POST /transacao - Criar transação
$app->post('/transacao', [$transacaoController, 'criar']);

// GET /transacao/{id} - Buscar transação por ID
$app->get('/transacao/{id}', [$transacaoController, 'buscarPorId']);

// DELETE /transacao - Limpar todas as transações
$app->delete('/transacao', [$transacaoController, 'limparTodas']);

// DELETE /transacao/{id} - Limpar transação específica
$app->delete('/transacao/{id}', [$transacaoController, 'limparPorId']);

// GET /estatistica - Calcular estatísticas dos últimos 60 segundos
$app->get('/estatistica', [$estatisticaController, 'calcular']);

// Executar a aplicação
$app->run();