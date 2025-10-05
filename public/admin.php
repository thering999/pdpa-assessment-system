<?php
/**
 * Admin Page - Manage System
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/AssessmentController.php';

AuthController::requireAdmin();

$assessmentController = new AssessmentController();
$allAssessments = $assessmentController->getAllAssessments();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - จัดการระบบ</title>
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
                        <li><a href="admin.php">จัดการระบบ</a></li>
                        <li><a href="logout.php">ออกจากระบบ</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <div class="container dashboard">
        <div class="dashboard-header">
            <h1>จัดการระบบ</h1>
            <p>ดูและจัดการข้อมูลการประเมินทั้งหมด</p>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>การประเมินทั้งหมด</h3>
                <div class="stat-value"><?php echo count($allAssessments); ?></div>
            </div>
            <div class="stat-card">
                <h3>เสร็จสิ้น</h3>
                <div class="stat-value">
                    <?php 
                    $completed = array_filter($allAssessments, function($a) { 
                        return $a['status'] === 'completed'; 
                    });
                    echo count($completed);
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>คะแนนเฉลี่ยระบบ</h3>
                <div class="stat-value">
                    <?php 
                    $totalScore = 0;
                    $count = 0;
                    foreach ($allAssessments as $a) {
                        if ($a['status'] === 'completed') {
                            $totalScore += $a['percentage'];
                            $count++;
                        }
                    }
                    echo $count > 0 ? number_format($totalScore / $count, 1) : '0.0';
                    ?>%
                </div>
            </div>
        </div>
        
        <div class="assessment-container">
            <h2 style="margin-bottom: 1rem;">รายการการประเมินทั้งหมด</h2>
            
            <?php if (empty($allAssessments)): ?>
                <p style="text-align: center; padding: 2rem; color: #64748b;">ยังไม่มีข้อมูลการประเมิน</p>
            <?php else: ?>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>ผู้ใช้</th>
                                <th>องค์กร</th>
                                <th>ประเภท</th>
                                <th>วันที่เริ่ม</th>
                                <th>สถานะ</th>
                                <th>คะแนน</th>
                                <th>การกระทำ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allAssessments as $assessment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($assessment['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($assessment['organization'] ?? '-'); ?></td>
                                    <td>
                                        <?php 
                                        $types = [
                                            'both' => 'ครบถ้วน',
                                            'pdpa' => 'PDPA',
                                            'cyber' => 'Cyber'
                                        ];
                                        echo $types[$assessment['assessment_type']];
                                        ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($assessment['started_at'])); ?></td>
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
