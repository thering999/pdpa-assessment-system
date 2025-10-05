<?php
/**
 * Assessment Model
 * Handles assessment-related database operations
 */

class Assessment {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create new assessment
     */
    public function create($userId, $assessmentType = 'both') {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO assessments (user_id, assessment_type, status) VALUES (?, ?, 'in_progress')"
            );
            $stmt->execute([$userId, $assessmentType]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get assessment by ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM assessments WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Get user's assessments
     */
    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM assessments WHERE user_id = ? ORDER BY started_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all assessments (admin)
     */
    public function getAll() {
        $stmt = $this->pdo->query(
            "SELECT a.*, u.username, u.full_name, u.organization 
             FROM assessments a 
             JOIN users u ON a.user_id = u.id 
             ORDER BY a.started_at DESC"
        );
        return $stmt->fetchAll();
    }
    
    /**
     * Save answer
     */
    public function saveAnswer($assessmentId, $questionId, $answer, $score) {
        try {
            // Check if answer already exists
            $stmt = $this->pdo->prepare(
                "SELECT id FROM assessment_answers WHERE assessment_id = ? AND question_id = ?"
            );
            $stmt->execute([$assessmentId, $questionId]);
            $existing = $stmt->fetch();
            
            if ($existing) {
                // Update existing answer
                $stmt = $this->pdo->prepare(
                    "UPDATE assessment_answers SET answer = ?, score = ?, answered_at = CURRENT_TIMESTAMP 
                     WHERE assessment_id = ? AND question_id = ?"
                );
                return $stmt->execute([$answer, $score, $assessmentId, $questionId]);
            } else {
                // Insert new answer
                $stmt = $this->pdo->prepare(
                    "INSERT INTO assessment_answers (assessment_id, question_id, answer, score) 
                     VALUES (?, ?, ?, ?)"
                );
                return $stmt->execute([$assessmentId, $questionId, $answer, $score]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get assessment answers
     */
    public function getAnswers($assessmentId) {
        $stmt = $this->pdo->prepare(
            "SELECT aa.*, aq.question, aq.question_en, aq.weight, ac.name as category_name 
             FROM assessment_answers aa
             JOIN assessment_questions aq ON aa.question_id = aq.id
             JOIN assessment_categories ac ON aq.category_id = ac.id
             WHERE aa.assessment_id = ?
             ORDER BY aq.display_order"
        );
        $stmt->execute([$assessmentId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Complete assessment and calculate score
     */
    public function complete($assessmentId) {
        try {
            // Calculate total score
            $stmt = $this->pdo->prepare(
                "SELECT SUM(aa.score) as total_score, 
                        SUM(aq.weight) as max_score
                 FROM assessment_answers aa
                 JOIN assessment_questions aq ON aa.question_id = aq.id
                 WHERE aa.assessment_id = ?"
            );
            $stmt->execute([$assessmentId]);
            $result = $stmt->fetch();
            
            $totalScore = $result['total_score'] ?? 0;
            $maxScore = $result['max_score'] ?? 0;
            $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
            
            // Update assessment
            $stmt = $this->pdo->prepare(
                "UPDATE assessments 
                 SET status = 'completed', 
                     completed_at = CURRENT_TIMESTAMP,
                     total_score = ?,
                     max_score = ?,
                     percentage = ?
                 WHERE id = ?"
            );
            
            return $stmt->execute([$totalScore, $maxScore, $percentage, $assessmentId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get categories
     */
    public function getCategories($type = null) {
        if ($type) {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM assessment_categories WHERE category_type = ? ORDER BY display_order"
            );
            $stmt->execute([$type]);
        } else {
            $stmt = $this->pdo->query(
                "SELECT * FROM assessment_categories ORDER BY display_order"
            );
        }
        return $stmt->fetchAll();
    }
    
    /**
     * Get questions by category
     */
    public function getQuestionsByCategory($categoryId) {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM assessment_questions WHERE category_id = ? ORDER BY display_order"
        );
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all questions
     */
    public function getAllQuestions() {
        $stmt = $this->pdo->query(
            "SELECT aq.*, ac.name as category_name, ac.category_type 
             FROM assessment_questions aq
             JOIN assessment_categories ac ON aq.category_id = ac.id
             ORDER BY ac.display_order, aq.display_order"
        );
        return $stmt->fetchAll();
    }
}
