<?php

require_once __DIR__ . '/lib/router.php';

get('/index', 'views/index.php');

get('/leaderboard', 'views/leaderboard.php');

get('/about', 'views/project.php');

get('/graph', 'views/graph.php');

get('/api/db-status', function () {
    require_once __DIR__ . '/lib/lib.php';
    $pdo = $connectToPostgres();
    $status = $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    echo json_encode($status);
});

// highscore api

get('/api/highscores', function () {
    require_once __DIR__ . '/lib/lib.php';

    $validSortBy = ['id', 'username', 'score', 'created_at'];
    $validOrder = ['asc', 'desc'];

    $sortBy = $_GET['sort_by'] ?? 'score';
    $order = $_GET['order'] ?? 'desc';

    if (!in_array(strtolower($sortBy), $validSortBy)) {
        $sortBy = 'score';
    }
    if (!in_array(strtolower($order), $validOrder)) {
        $order = 'desc';
    }

   $query = "SELECT * FROM highscores ORDER BY $sortBy $order, created_at ASC LIMIT 50";
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

    $fields = [];
    $bindings = [];

    if (isset($data['username'])) {
        $fields[] = 'username = ?';
        $bindings[] = $data['username'];
    }

    if (isset($data['score']) && is_numeric($data['score'])) {
        $fields[] = 'score = ?';
        $bindings[] = $data['score'];
    }

    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(['error' => 'No valid fields to update']);
        exit;
    }

    $bindings[] = $id;
    $query = 'UPDATE highscores SET ' . implode(', ', $fields) . ' WHERE id = ? RETURNING *';
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

