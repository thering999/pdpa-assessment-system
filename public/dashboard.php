<?php
/**
 * Dashboard Page
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/AssessmentController.php';

AuthController::requireLogin();

$assessmentController = new AssessmentController();
$assessments = $assessmentController->getUserAssessments();

// Get statistics
$totalAssessments = count($assessments);
$completedAssessments = 0;
$averageScore = 0;

foreach ($assessments as $assessment) {
    if ($assessment['status'] === 'completed') {
        $completedAssessments++;
        $averageScore += $assessment['percentage'];
    }
}

if ($completedAssessments > 0) {
    $averageScore = $averageScore / $completedAssessments;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - แดชบอร์ด</title>
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
                        <?php if (AuthController::isAdmin()): ?>
                            <li><a href="admin.php">จัดการระบบ</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">ออกจากระบบ</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <div class="container dashboard">
        <div class="dashboard-header">
            <h1>สวัสดี, <?php echo htmlspecialchars($_SESSION['full_name']); ?></h1>
            <p>ยินดีต้อนรับสู่ระบบประเมิน PDPA</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>การประเมินทั้งหมด</h3>
                <div class="stat-value"><?php echo $totalAssessments; ?></div>
            </div>
            <div class="stat-card">
                <h3>ประเมินเสร็จสิ้น</h3>
                <div class="stat-value"><?php echo $completedAssessments; ?></div>
            </div>
            <div class="stat-card">
                <h3>คะแนนเฉลี่ย</h3>
                <div class="stat-value"><?php echo number_format($averageScore, 1); ?>%</div>
            </div>
        </div>
        
        <div class="assessment-container">
            <h2 style="margin-bottom: 1rem;">เริ่มการประเมินใหม่</h2>
            <p style="margin-bottom: 1.5rem;">เลือกประเภทการประเมินที่คุณต้องการทำ</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                <form method="POST" action="start_assessment.php" style="margin: 0;">
                    <input type="hidden" name="type" value="both">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem;">แบบประเมินครบถ้วน</h3>
                        <p style="font-size: 0.875rem; font-weight: normal;">ประเมินทั้ง PDPA และ Cyber Law</p>
                    </button>
                </form>
                
                <form method="POST" action="start_assessment.php" style="margin: 0;">
                    <input type="hidden" name="type" value="pdpa">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem;">แบบประเมิน PDPA</h3>
                        <p style="font-size: 0.875rem; font-weight: normal;">ประเมินความสอดคล้อง พรบ.PDPA</p>
                    </button>
                </form>
                
                <form method="POST" action="start_assessment.php" style="margin: 0;">
                    <input type="hidden" name="type" value="cyber">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.5rem;">
                        <h3 style="margin-bottom: 0.5rem;">แบบประเมิน Cyber Law</h3>
                        <p style="font-size: 0.875rem; font-weight: normal;">ประเมินความมั่นคงปลอดภัยไซเบอร์</p>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="assessment-container">
            <h2 style="margin-bottom: 1rem;">ประวัติการประเมิน</h2>
            
            <?php if (empty($assessments)): ?>
                <p style="text-align: center; padding: 2rem; color: #64748b;">ยังไม่มีประวัติการประเมิน</p>
            <?php else: ?>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>วันที่เริ่ม</th>
                                <th>ประเภท</th>
                                <th>สถานะ</th>
                                <th>คะแนน</th>
                                <th>การกระทำ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assessments as $assessment): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($assessment['started_at'])); ?></td>
                                    <td>
                                        <?php 
                                        $types = [
                                            'both' => 'ครบถ้วน (PDPA + Cyber)',
                                            'pdpa' => 'PDPA',
                                            'cyber' => 'Cyber Law'
                                        ];
                                        echo $types[$assessment['assessment_type']];
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($assessment['status'] === 'completed'): ?>
                                            <span style="color: var(--success-color);">เสร็จสิ้น</span>
                                        <?php else: ?>
                                            <span style="color: var(--warning-color);">กำลังดำเนินการ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($assessment['status'] === 'completed'): ?>
                                            <?php echo number_format($assessment['percentage'], 1); ?>%
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($assessment['status'] === 'completed'): ?>
                                            <a href="result.php?id=<?php echo $assessment['id']; ?>" class="btn btn-sm btn-primary">ดูผล</a>
                                        <?php else: ?>
                                            <a href="assessment.php?id=<?php echo $assessment['id']; ?>" class="btn btn-sm btn-success">ดำเนินการต่อ</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="js/main.js"></script>
</body>
</html>
