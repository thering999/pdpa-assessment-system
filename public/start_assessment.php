<?php
/**
 * Start Assessment
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/AssessmentController.php';

AuthController::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$assessmentController = new AssessmentController();
$type = $_POST['type'] ?? 'both';

$result = $assessmentController->start();

if ($result && $result['success']) {
    header('Location: assessment.php?id=' . $result['assessment_id']);
    exit;
} else {
    header('Location: dashboard.php?error=1');
    exit;
}
