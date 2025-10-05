<?php
/**
 * API Endpoint
 * Handles AJAX requests
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/AssessmentController.php';

header('Content-Type: application/json');

AuthController::requireLogin();

$action = $_GET['action'] ?? '';
$assessmentController = new AssessmentController();

switch ($action) {
    case 'save_answer':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $assessmentController->saveAnswer();
            echo json_encode($result);
        }
        break;
        
    case 'complete_assessment':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $assessmentController->complete();
            echo json_encode($result);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
