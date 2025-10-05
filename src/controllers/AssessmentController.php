<?php
/**
 * Assessment Controller
 * Handles assessment operations
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Assessment.php';
require_once __DIR__ . '/AuthController.php';

class AssessmentController {
    private $assessmentModel;
    
    public function __construct() {
        $pdo = getDBConnection();
        $this->assessmentModel = new Assessment($pdo);
    }
    
    /**
     * Start new assessment
     */
    public function start() {
        AuthController::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? 'both';
            $userId = $_SESSION['user_id'];
            
            $assessmentId = $this->assessmentModel->create($userId, $type);
            
            if ($assessmentId) {
                return ['success' => true, 'assessment_id' => $assessmentId];
            } else {
                return ['success' => false, 'message' => 'ไม่สามารถสร้างการประเมินได้'];
            }
        }
    }
    
    /**
     * Save answer
     */
    public function saveAnswer() {
        AuthController::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assessmentId = $_POST['assessment_id'] ?? 0;
            $questionId = $_POST['question_id'] ?? 0;
            $answer = $_POST['answer'] ?? '';
            
            // Get question to calculate score
            $questions = $this->assessmentModel->getAllQuestions();
            $question = null;
            
            foreach ($questions as $q) {
                if ($q['id'] == $questionId) {
                    $question = $q;
                    break;
                }
            }
            
            if (!$question) {
                return ['success' => false, 'message' => 'ไม่พบคำถาม'];
            }
            
            // Calculate score based on answer
            $score = 0;
            if ($question['question_type'] === 'yes_no') {
                $score = ($answer === 'yes') ? $question['weight'] : 0;
            }
            
            $result = $this->assessmentModel->saveAnswer($assessmentId, $questionId, $answer, $score);
            
            if ($result) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'ไม่สามารถบันทึกคำตอบได้'];
            }
        }
    }
    
    /**
     * Complete assessment
     */
    public function complete() {
        AuthController::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assessmentId = $_POST['assessment_id'] ?? 0;
            
            $result = $this->assessmentModel->complete($assessmentId);
            
            if ($result) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'ไม่สามารถบันทึกผลการประเมินได้'];
            }
        }
    }
    
    /**
     * Get assessment details
     */
    public function getDetails($assessmentId) {
        AuthController::requireLogin();
        
        $assessment = $this->assessmentModel->getById($assessmentId);
        
        if (!$assessment) {
            return null;
        }
        
        // Check if user owns this assessment or is admin
        if ($assessment['user_id'] != $_SESSION['user_id'] && !AuthController::isAdmin()) {
            return null;
        }
        
        return $assessment;
    }
    
    /**
     * Get user's assessments
     */
    public function getUserAssessments() {
        AuthController::requireLogin();
        return $this->assessmentModel->getByUserId($_SESSION['user_id']);
    }
    
    /**
     * Get all assessments (admin only)
     */
    public function getAllAssessments() {
        AuthController::requireAdmin();
        return $this->assessmentModel->getAll();
    }
    
    /**
     * Get categories
     */
    public function getCategories($type = null) {
        return $this->assessmentModel->getCategories($type);
    }
    
    /**
     * Get questions
     */
    public function getQuestions() {
        return $this->assessmentModel->getAllQuestions();
    }
}
