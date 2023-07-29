<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Caixa;

require __DIR__ . '/../vendor/autoload.php';

// Include the database configuration
require __DIR__ . '/config.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Create a PHP renderer
$renderer = new PhpRenderer(__DIR__ . '/templates');

// Setup Illuminate Database Connection
$capsule = new Capsule;
$capsule->addConnection($dbConfig);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Add routes
$app->get('/', function (Request $request, Response $response) use ($renderer) {
    // Get the 'tipo' parameter from the query string
    $params = $request->getQueryParams();
    $tipo = isset($params['tipo']) ? $params['tipo'] : null;

    // Filter the data based on the 'tipo' parameter
    $query = Caixa::query();
    if ($tipo !== null) {
        $query->where('tipo', 'like', '%' . $tipo . '%');
    }

    // Fetch all rows from the 'caixas' table
    $extrato = $query->get();

    // Calculate the total income (where status is 1)
    $receitas = $extrato->where('status', 1)->sum('valor');

    // Calculate the total expenses (where status is 0)
    $despesas = $extrato->where('status', 0)->sum('valor');

    // Calculate the total value
    $valor_total = $receitas - $despesas;

    // Pass the data to the view
    return $renderer->render($response, "/home.php", [
        'termo_de_busca' => $tipo,
        'receitas' => $receitas,
        'despesas' => $despesas,
        'valor_total' => $valor_total,
        'extrato' => $extrato
    ]);
});

$app->get('/adicionar', function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, "/adicionar.php");
});

// Add routes
$app->post('/cadastrar', function (Request $request, Response $response) {
    // Get the form data
    $data = $request->getParsedBody();

    // Create a new 'caixa' record
    $caixa = new Caixa;
    $caixa->tipo = $data['tipo'];
    $caixa->valor = $data['valor'];
    $caixa->status = $data['status'];
    $caixa->save();

    // Redirect back to the home page
    return $response->withHeader('Location', '/')->withStatus(302);
});

$app->get('/excluir/{id}', function (Request $request, Response $response, $args) {
    // Get the ID from the route parameters
    $id = $args['id'];
    
    // Find the record in the 'caixas' table and delete it
    $caixa = Caixa::find($id);
    if ($caixa) {
        $caixa->delete();
    }
    
    // Redirect back to the home page
    return $response->withHeader('Location', '/')->withStatus(302);
});


// =============== API ===============

// Add routes
$app->get('/api/caixas', function (Request $request, Response $response) use ($renderer) {
    // Get the 'tipo' parameter from the query string
    $params = $request->getQueryParams();
    $tipo = isset($params['tipo']) ? $params['tipo'] : null;

    // Filter the data based on the 'tipo' parameter
    $query = Caixa::query();
    if ($tipo !== null) {
        $query->where('tipo', 'like', '%' . $tipo . '%');
    }

    // Fetch all rows from the 'caixas' table
    $extrato = $query->get();

    // Calculate the total income (where status is 1)
    $receitas = $extrato->where('status', 1)->sum('valor');

    // Calculate the total expenses (where status is 0)
    $despesas = $extrato->where('status', 0)->sum('valor');

    // Calculate the total value
    $valor_total = $receitas - $despesas;

    $payload = [
        'receitas' => $receitas,
        'despesas' => $despesas,
        'valor_total' => $valor_total,
        'extrato' => $extrato->toArray() // Convert the Eloquent collection to an array
    ];
    return $response->withJson($payload);
});

// Add routes
$app->post('/api/caixas', function (Request $request, Response $response) {
    // Get the form data
    $data = $request->getParsedBody();

    // Create a new 'caixa' record
    $caixa = new Caixa;
    $caixa->tipo = $data['tipo'];
    $caixa->valor = $data['valor'];
    $caixa->status = $data['status'];
    $caixa->save();

    return $response->withJson($caixa);
});

$app->delete('/api/caixas/{id}', function (Request $request, Response $response, $args) {
    // Get the ID from the route parameters
    $id = $args['id'];
    
    // Find the record in the 'caixas' table and delete it
    $caixa = Caixa::find($id);
    if ($caixa) {
        $caixa->delete();
    }
    
    return $response->withStatus(204);
});


$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->run();
