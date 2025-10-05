<?php
// Simple REST API for assessment data (read-only demo)
require_once __DIR__.'/db.php';
header('Content-Type: application/json; charset=utf-8');

$path = $_GET['path'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

function auth() {
    // TODO: implement real API key/JWT auth
    return true;
}

if (!auth()) {
    http_response_code(401);
    echo json_encode(['error'=>'Unauthorized']);
    exit;
}

switch ($path) {
    case 'assessments':
        $pdo = db();
        $uid = $_GET['user_id'] ?? null;
        $sql = $uid ? 'SELECT * FROM assessments WHERE user_id=? ORDER BY started_at DESC' : 'SELECT * FROM assessments ORDER BY started_at DESC';
        $st = $pdo->prepare($sql);
        $st->execute($uid ? [$uid] : []);
        $rows = $st->fetchAll();
        echo json_encode($rows);
        break;
    case 'assessment':
        $id = (int)($_GET['id'] ?? 0);
        $pdo = db();
        $st = $pdo->prepare('SELECT * FROM assessments WHERE id=?');
        $st->execute([$id]);
        $row = $st->fetch();
        echo json_encode($row ?: []);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error'=>'Not found']);
}
