<?php
/**
 * Result Page
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/AssessmentController.php';

AuthController::requireLogin();

$assessmentController = new AssessmentController();
$assessmentId = $_GET['id'] ?? 0;

$assessment = $assessmentController->getDetails($assessmentId);

if (!$assessment || $assessment['status'] !== 'completed') {
    header('Location: dashboard.php');
    exit;
}

$percentage = $assessment['percentage'];
$scoreClass = '';
$scoreText = '';
$recommendations = [];

if ($percentage >= 80) {
    $scoreClass = 'score-excellent';
    $scoreText = 'ดีเยี่ยม';
    $recommendations[] = 'องค์กรของคุณมีความสอดคล้องกับ PDPA ในระดับดีเยี่ยม';
    $recommendations[] = 'ควรรักษามาตรฐานที่มีอยู่และทบทวนเป็นประจำ';
} elseif ($percentage >= 60) {
    $scoreClass = 'score-good';
    $scoreText = 'ดี';
    $recommendations[] = 'องค์กรของคุณมีความสอดคล้องกับ PDPA ในระดับดี';
    $recommendations[] = 'ควรปรับปรุงในส่วนที่ยังขาดอยู่เพื่อเพิ่มความสอดคล้อง';
} elseif ($percentage >= 40) {
    $scoreClass = 'score-fair';
    $scoreText = 'พอใช้';
    $recommendations[] = 'องค์กรของคุณมีความสอดคล้องกับ PDPA ในระดับพอใช้';
    $recommendations[] = 'ควรให้ความสำคัญในการปรับปรุงมาตรการต่างๆ';
    $recommendations[] = 'แนะนำให้จัดทำแผนการปรับปรุงอย่างเป็นระบบ';
} else {
    $scoreClass = 'score-poor';
    $scoreText = 'ต้องปรับปรุง';
    $recommendations[] = 'องค์กรของคุณยังมีความสอดคล้องกับ PDPA ในระดับต่ำ';
    $recommendations[] = 'จำเป็นต้องดำเนินการปรับปรุงอย่างเร่งด่วน';
    $recommendations[] = 'ควรพิจารณาจ้างที่ปรึกษาเพื่อช่วยในการปรับปรุง';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - ผลการประเมิน</title>
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
            <h1>ผลการประเมิน</h1>
            <p>ประเมินเมื่อ: <?php echo date('d/m/Y H:i', strtotime($assessment['completed_at'])); ?></p>
        </div>
        
        <div class="result-card">
            <h2>คะแนนรวม</h2>
            <div class="score-circle <?php echo $scoreClass; ?>">
                <?php echo number_format($percentage, 1); ?>%
            </div>
            <h3 style="color: inherit;"><?php echo $scoreText; ?></h3>
            <p style="margin-top: 1rem; color: #64748b;">
                คุณได้คะแนน <?php echo number_format($assessment['total_score'], 1); ?> 
                จาก <?php echo number_format($assessment['max_score'], 1); ?> คะแนน
            </p>
        </div>
        
        <div class="assessment-container">
            <h2 style="margin-bottom: 1rem;">คำแนะนำ</h2>
            <ul style="line-height: 2; font-size: 1.1rem;">
                <?php foreach ($recommendations as $rec): ?>
                    <li><?php echo htmlspecialchars($rec); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="assessment-container">
            <h2 style="margin-bottom: 1rem;">แนวทางการปรับปรุง</h2>
            <div style="background: var(--light-bg); padding: 1.5rem; border-radius: 8px;">
                <h3 style="margin-bottom: 1rem;">สำหรับคำถามที่ตอบว่า "ไม่ใช่" ควรดำเนินการดังนี้:</h3>
                <ul style="line-height: 2;">
                    <li><strong>การจัดการข้อมูลส่วนบุคคล:</strong> จัดทำนโยบายและแต่งตั้ง DPO</li>
                    <li><strong>สิทธิของเจ้าของข้อมูล:</strong> จัดทำกระบวนการรับคำขอใช้สิทธิ</li>
                    <li><strong>มาตรการรักษาความปลอดภัย:</strong> ติดตั้งระบบรักษาความปลอดภัยที่เหมาะสม</li>
                    <li><strong>การแจ้งเหตุละเมิด:</strong> จัดทำแผนรับมือและกระบวนการแจ้งเหตุ</li>
                    <li><strong>การประเมินผลกระทบ:</strong> ทำ Privacy Impact Assessment เป็นประจำ</li>
                    <li><strong>ความมั่นคงปลอดภัยไซเบอร์:</strong> ติดตั้งและอัปเดต Firewall, Antivirus</li>
                    <li><strong>การตอบสนองภัยคุกคาม:</strong> จัดตั้งทีมและฝึกซ้อมเป็นระยะ</li>
                    <li><strong>การสำรองข้อมูล:</strong> จัดทำระบบสำรองและทดสอบการกู้คืน</li>
                </ul>
            </div>
        </div>
        
        <div style="text-align: center; margin: 2rem 0;">
            <a href="dashboard.php" class="btn btn-primary" style="padding: 1rem 3rem;">กลับสู่แดชบอร์ด</a>
            <button onclick="window.print()" class="btn btn-success" style="padding: 1rem 3rem; margin-left: 1rem;">พิมพ์รายงาน</button>
        </div>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
