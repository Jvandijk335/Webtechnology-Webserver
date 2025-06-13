<?php

require_once __DIR__ . '/lib/router.php';

get('/index', 'views/index.php');

get('/dashboard', 'views/dashboard.php');

get('/leaderboard', 'views/leaderboard.php');

get('/api/db-status', function () {
    require_once __DIR__ . '/lib/lib.php';
    $pdo = $connectToPostgres();
    $status = $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    echo json_encode($status);
});

// temperature api

get('/api/temperature', function () {
    require_once __DIR__ . '/lib/lib.php';
    date_default_timezone_set('Europe/Brussels');
    $begin = $_GET['begin'] ?? null;
    $end = $_GET['end'] ?? date('Y-m-d H:i:s');
    $between = $begin ? ' where datetime between ? and ? ' : ' ';
    $query = 'select * from temperature' . $between . 'limit 50';
    $bindings = $begin ? [$begin, $end] : [ ];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_OK);
});

get('/api/temperature/$id', function ($id) {
    require_once __DIR__ . '/lib/lib.php';
    $query = 'select * from temperature where id = ?';
    $bindings = [$id];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_OK);
});

post('/api/temperature', function () {
    require_once __DIR__ . '/lib/lib.php';
    $query = 'insert into temperature (value) values (?) returning id';
    $value = json_decode(file_get_contents('php://input'), true)['value'];
    $bindings = [$value];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_CREATED);
});

$putAndPatchFunction = function ($id) {
    require_once __DIR__ . '/lib/lib.php';
    $query = 'update temperature set value = ? where id = ? returning *';
    $value = json_decode(file_get_contents('php://input'), true)['value'];
    $bindings = [$value, $id];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_OK);  
};
put('/api/temperature/$id', $putAndPatchFunction);
patch('/api/temperature/$id', $putAndPatchFunction);

delete('/api/temperature/$id', function ($id) {
    require_once __DIR__ . '/lib/lib.php';
    $query = 'delete from temperature where id = ? returning *';
    $bindings = [$id];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_OK);
});

// highscore api

get('/api/highscores', function () {
    require_once __DIR__ . '/lib/lib.php';
    $query = 'SELECT * FROM highscores ORDER BY score DESC, created_at ASC LIMIT 10';
    handleRequest($connectToPostgres, $query, [], $HTTP_OK);
});

get('/api/highscores/$id', function ($id) {
    require_once __DIR__ . '/lib/lib.php';
    $query = 'SELECT * FROM highscores WHERE id = ?';
    $bindings = [$id];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_OK);
});

post('/api/highscores', function () {
    require_once __DIR__ . '/lib/lib.php';
    $data = json_decode(file_get_contents('php://input'), true);

    $username = $data['username'] ?? null;
    $score = $data['score'] ?? null;

    if (!$username || !is_numeric($score)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    $query = 'INSERT INTO highscores (username, score) VALUES (?, ?) RETURNING *';
    $bindings = [$username, $score];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_CREATED);
});

$updateHighscore = function ($id) {
    require_once __DIR__ . '/lib/lib.php';
    $data = json_decode(file_get_contents('php://input'), true);

    $username = $data['username'] ?? null;
    $score = $data['score'] ?? null;

    if (!$username || !is_numeric($score)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    $query = 'UPDATE highscores SET username = ?, score = ? WHERE id = ? RETURNING *';
    $bindings = [$username, $score, $id];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_OK);
};
put('/api/highscores/$id', $updateHighscore);
patch('/api/highscores/$id', $updateHighscore);

delete('/api/highscores/$id', function ($id) {
    require_once __DIR__ . '/lib/lib.php';
    $query = 'DELETE FROM highscores WHERE id = ? RETURNING *';
    $bindings = [$id];
    handleRequest($connectToPostgres, $query, $bindings, $HTTP_OK);
});

// 404

any('/404', 'views/404.php');

