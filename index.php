<?php
use App\Models\Tarefa;
use App\Models\Usuario;
use App\Database\Mariadb;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$banco = new Mariadb();

$app->get('/usuario/{id}/tarefas', function (Request $request, Response $response, array $args) use ($banco) {
    $user_id = (int)$args['id'];
    $tarefa = new Tarefa($banco->getConnection());
    $tarefas = $tarefa->getAllByUser($user_id);
    $response->getBody()->write(json_encode($tarefas));
    return $response;
});
$app->get('/tarefas/{id}', function (Request $request, Response $response, array $args) use ($banco) {
    $user_id = (int)$args['id'];
    $tarefa = new Tarefa($banco->getConnection());
    $resultado = $tarefa->getTarefaById($user_id);
    $response->getBody()->write(json_encode($resultado));
    return $response;
});

// cadastra usuário
$app->post('/usuario', function(Request $request, Response $response, array $args) use ($banco)
{
    $campos_obrigatórios = ['nome', 'login', 'senha', "email"];
    $body = $request->getParsedBody();
    try{
        $usuario = new Usuario($banco->getConnection());
        // $usuario->id = $args['id'];
        $usuario->nome = $body['nome'] ?? '';
        $usuario->login = $body['login'] ?? '';
        $usuario->senha = $body['senha'] ?? '';
        $usuario->email = $body['email'] ?? '';
        $usuario->foto_path = $body['foto_path'] ?? '';
        foreach ($campos_obrigatórios as $campo) {
            if (empty($usuario->{$campo})) {
               throw new \Exception("O campo {$campo} é obrigatório.");
            }
        }
        $usuario->create();
    }catch(\Exception $exception){
        $response->getBody()->write(json_encode(['message' => $exception->getMessage()
        ]));
    }
    $response->getBody()->write(json_encode([
        'message' => 'Usuário cadastrado com sucesso!'
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/tarefa', function(Request $request, Response $response, array $args) use ($banco)
{
    $campos_obrigatórios = ['titulo', 'descricao', 'status', 'user_id'];
    $body = $request->getParsedBody();
    try{
        $Tarefa = new Tarefa($banco->getConnection());
        $Tarefa->titulo = $body['titulo'] ?? '';
        $Tarefa->descricao = $body['descricao'] ?? '';
        $Tarefa->status = $body['status'] ?? '';
        $Tarefa->user_id = $body['user_id'] ?? '';
        foreach ($campos_obrigatórios as $campo) {
            if (empty($Tarefa->{$campo})) {
               throw new \Exception("O campo {$campo} é obrigatório.");
            }
        }
    }catch(\Exception $exception){
        $response->getBody()->write(json_encode(['message' => $exception->getMessage()
        ]));
    }
    $response->getBody()->write(json_encode([
        'message' => 'Tarefa cadastrada com sucesso!'
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/tarefa/{id}', function(Request $request, Response $response, array $args) use ($banco)
{
    $campos_obrigatórios = ['nome', 'login', 'senha', "email"];
    $body = $request->getParsedBody();
    try{
       $Tarefa = new Tarefa($banco->getConnection());
        $Tarefa->titulo = $body['titulo'] ?? '';
        $Tarefa->descricao = $body['descricao'] ?? '';
        $Tarefa->status = $body['status'] ?? '';
        $Tarefa->user_id = $body['user_id'] ?? '';
        foreach ($campos_obrigatórios as $campo) {
            if (empty($Tarefa->{$campo})) {
               throw new \Exception("O campo {$campo} é obrigatório.");
            }
        }
        $Tarefa->update();
    }catch(\Exception $exception){
        $response->getBody()->write(json_encode(['message' => $exception->getMessage()
]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    $response->getBody()->write(json_encode([
        'message' => 'Tarefa atualizada com sucesso!'
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->put('/usuario/{id}', function(Request $request, Response $response, array $args) use ($banco)
{
    $campos_obrigatórios = ['nome', 'login', 'senha', "email"];
    $body = $request->getParsedBody();
    try{
        $usuario = new Usuario($banco->getConnection());
        $usuario->id = $args['id'];
        $usuario->nome = $body['nome'] ?? '';
        $usuario->login = $body['login'] ?? '';
        $usuario->senha = $body['senha'] ?? '';
        $usuario->email = $body['email'] ?? '';
        $usuario->foto_path = $body['foto_path'] ?? '';
        foreach ($campos_obrigatórios as $campo) {
            if (empty($usuario->{$campo})) {
               throw new \Exception("O campo {$campo} é obrigatório.");
            }
        }
        $usuario->update();
    }catch(\Exception $exception){
        $response->getBody()->write(json_encode(['message' => $exception->getMessage()
]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
    $response->getBody()->write(json_encode([
        'message' => 'Usuário atualizado com sucesso!'
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});
    
$app->delete('/usuario/{id}', 
    function(Request $request, Response $response, array $args) use ($banco)
 {
    $id = $args['id'];
    $usuario = new Usuario($banco->getConnection());
    $usuario->delete($id);
    $response->getBody()->write(json_encode(['message' => 'Usuário excluído']));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/tarefa/{id}', 
    function(Request $request, Response $response, array $args) use ($banco)
 {
    $id = $args['id'];
    $Tarefa = new Tarefa($banco->getConnection());
    $Tarefa->delete($id);
    $response->getBody()->write(json_encode(['message' => 'Tarefa excluída']));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();