<?php
/**
 * Assessment Page
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/AssessmentController.php';

AuthController::requireLogin();

$assessmentController = new AssessmentController();
$assessmentId = $_GET['id'] ?? 0;

$assessment = $assessmentController->getDetails($assessmentId);

if (!$assessment) {
    header('Location: dashboard.php');
    exit;
}

if ($assessment['status'] === 'completed') {
    header('Location: result.php?id=' . $assessmentId);
    exit;
}

$questions = $assessmentController->getQuestions();
$categories = $assessmentController->getCategories();

// Filter questions based on assessment type
$filteredQuestions = [];
foreach ($questions as $question) {
    if ($assessment['assessment_type'] === 'both') {
        $filteredQuestions[] = $question;
    } elseif ($assessment['assessment_type'] === $question['category_type']) {
        $filteredQuestions[] = $question;
    }
}

// Group questions by category
$questionsByCategory = [];
foreach ($filteredQuestions as $question) {
    $categoryId = $question['category_id'];
    if (!isset($questionsByCategory[$categoryId])) {
        $questionsByCategory[$categoryId] = [];
    }
    $questionsByCategory[$categoryId][] = $question;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - แบบประเมิน</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1>PDPA Assessment System</h1>
                    <p>ระบบประเมินความสอดคล้อง พรบ.คุ้มครองข้อมูลส่วนบุคคล</p>
                </div>
                <nav>
                    <ul>
                        <li><a href="dashboard.php">แดชบอร์ด</a></li>
                        <li><a href="logout.php">ออกจากระบบ</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <div class="container dashboard">
        <div class="dashboard-header">
            <h1>แบบประเมิน
                <?php 
                $types = [
                    'both' => 'ครบถ้วน (PDPA + Cyber Law)',
                    'pdpa' => 'PDPA',
                    'cyber' => 'Cyber Law'
                ];
                echo $types[$assessment['assessment_type']];
                ?>
            </h1>
            <p>กรุณาตอบคำถามทั้งหมดตามความเป็นจริงขององค์กร</p>
        </div>
        
        <div class="assessment-container" data-assessment-id="<?php echo $assessmentId; ?>">
            <form id="assessment-form" method="POST" action="api.php?action=save_all_answers">
                <input type="hidden" name="assessment_id" value="<?php echo $assessmentId; ?>">
                
                <?php foreach ($categories as $category): ?>
                    <?php if (isset($questionsByCategory[$category['id']])): ?>
                        <div class="category-section">
                            <h3><?php echo htmlspecialchars($category['name']); ?></h3>
                            <?php if ($category['description']): ?>
                                <p style="color: #64748b; margin-bottom: 1rem;">
                                    <?php echo htmlspecialchars($category['description']); ?>
                                </p>
                            <?php endif; ?>
                            
                            <?php foreach ($questionsByCategory[$category['id']] as $question): ?>
                                <div class="question-item">
                                    <p><?php echo htmlspecialchars($question['question']); ?></p>
                                    <div class="radio-group">
                                        <label>
                                            <input type="radio" 
                                                   name="question_<?php echo $question['id']; ?>" 
                                                   value="yes" 
                                                   required>
                                            ใช่
                                        </label>
                                        <label>
                                            <input type="radio" 
                                                   name="question_<?php echo $question['id']; ?>" 
                                                   value="no" 
                                                   required>
                                            ไม่ใช่
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <div style="margin-top: 2rem; text-align: center;">
                    <button type="button" 
                            onclick="completeAssessment(<?php echo $assessmentId; ?>)" 
                            class="btn btn-success" 
                            style="padding: 1rem 3rem; font-size: 1.1rem;">
                        ส่งแบบประเมิน
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
