<?php

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app['debug'] = true;

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'vendegasapp',
        'user' => 'root',
        'password' => ''
    ),
));

$app->get('/create-table', function (Silex\Application $app) {
    $file = fopen(__DIR__ . '/../data/schema.sql', 'r');
    while ($line = fread($file, 4096)) {
        $app['db']->executeQuery($line);
    }
    fclose($file);
    return "Tabelas criadas";
});

/***************************************************/
// PÁGINAS
/***************************************************/
$app->get('/', function () {
    ob_start();
    include __DIR__ . '/../templates/website/index.html';
    return ob_get_clean();
});

$app->get('/app', function () {
    ob_start();
    include __DIR__ . '/../templates/app/index.html';
    return ob_get_clean();
});

$app->get('/customers', function () {
    ob_start();
    include __DIR__ . '/../templates/app/pages/customers.html';
    return ob_get_clean();
});

/***************************************************/
// ADMINISTRAÇÃO
/***************************************************/

$app->get('api/clients', function (Request $request) use ($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $query = "SELECT * FROM clients";
    $id = (int) $request->get('id');
    $params = [];
    if($id > 0){
        $query .= " WHERE id = ?";
        array_push($params,$id);
    }
    $result = $db->fetchAll($query,$params);
    return $app->json($result);
});

$app->post('api/clients/store', function (Request $request) use ($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $data = $request->request->all();
    $db->insert('clients', $data);
    $id = $db->lastInsertId();
    $row = $db->fetchAssoc("SELECT * FROM clients WHERE id = ?", [$id]);
    return $app->json($row);

});

$app->post('api/clients/update', function (Request $request) use ($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $data = $request->request->all();
    $id = $data['id'];
    unset($data['id']);
    $db->update('clients', $data, ['id' => $id]);
    $row = $db->fetchAssoc("SELECT * FROM clients WHERE id = ?", [$id]);
    return $app->json($row);
});

$app->post('api/clients/delete', function (Request $request) use ($app) {
    /** @var \Doctrine\DBAL\Connection $db */
    $db = $app['db'];
    $data = $request->request->all();
    $id = $data['id'];
    $result = $db->delete('clients', ['id' => $id]);
    return $app->json(['success' => $result != 0], $result != 0 ? 200 : 400);
});

$app->run();

