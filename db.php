<?php
// Seed D3 เฉพาะภาษาไทย ไม่มีข้อมูลต่างดาว
function cii_seed_items_D3(): void {
    $pdo = db();
    $exists = (int)$pdo->query("SELECT COUNT(*) FROM cii_items WHERE section='D3'")->fetchColumn();
    if ($exists > 0) return;
    $ins = $pdo->prepare('INSERT INTO cii_items (section, num, objective, requirement, evident) VALUES (?,?,?,?,?)');
    $rows = [
        [26, "มีการตรวจสอบด้านความมั่นคงปลอดภัยไซเบอร์โดยมีข้อมูลสนับสนุนตามข้อกำหนดที่เกี่ยวข้อง", "แผนการตรวจสอบ", "รายงานหรือแผนการตรวจสอบ"],
        [27, "มีการประเมินผลกระทบทางธุรกิจ (Business Impact Analysis: BIA)", "แผนการตรวจสอบ", "แผนการทำ BIA, Audit Program/Plan, Audit Report"],
        [28, "มีการตรวจสอบในเรื่องบริการที่สำคัญที่หน่วยงานเป็นเจ้าของและใช้บริการ ตามผลการวิเคราะห์ผลกระทบทางธุรกิจ", "แผนการตรวจสอบ", "Audit Program, Audit Report, รายงานผลการวิเคราะห์ BIA"],
        [29, "มีการตรวจสอบการปฏิบัติตามประมวลแนวทางปฏิบัติ มาตรฐานการปฏิบัติงาน และคำสั่ง กฎหมาย", "แผนการตรวจสอบ", "Audit Program, Audit Report, รายงานผลการตรวจสอบ"],
        [30, "มีการสอบทานผลการปฏิบัติตามข้อกำหนดการตรวจสอบด้านความมั่นคงปลอดภัยไซเบอร์ และแจ้งผลการสอบทานให้กับหน่วยงานที่เกี่ยวข้องภายใน 30 วัน", "แผนการตรวจสอบ", "รายงานผลการสอบทาน, Audit Report, หนังสือแจ้งผล"],
        [31, "มีการทบทวนและปรับปรุงข้อกำหนดการตรวจสอบด้านความมั่นคงปลอดภัยไซเบอร์อย่างน้อยปีละ 1 ครั้ง", "แผนการตรวจสอบ", "รายงานการทบทวน, Audit Report, รายงานผลการปรับปรุง"],
        // ... เพิ่มข้อ 32-98 ตามรูปแบบเดียวกัน ...
    ];
    foreach ($rows as $r) { $ins->execute(['D3', $r[0], $r[1], $r[2], $r[3]]); }
}
// ฟังก์ชันส่งอีเมลแจ้งเตือน (ใช้ mail() พื้นฐาน)
function notify_admin_email($subject, $body) {
    $admin = getenv('ADMIN_EMAIL') ?: 'admin@example.com';
    @mail($admin, $subject, $body, "Content-Type: text/plain; charset=UTF-8");
}

function notify_user_email($to, $subject, $body) {
    if (!$to) return;
    @mail($to, $subject, $body, "Content-Type: text/plain; charset=UTF-8");
}

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $cfg = require __DIR__ . '/config.php';
    $dsn = "mysql:host={$cfg['db_host']};dbname={$cfg['db_name']};charset={$cfg['db_charset']}";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $attempts = 0; $lastErr = null;
    while ($attempts < 10) {
        try {
            $pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], $opt);
            break;
        } catch (Throwable $e) {
            $lastErr = $e; $attempts++;
            usleep(300000); // 300ms
        }
    }
    if (!$pdo) { throw new RuntimeException('DB connect failed: ' . ($lastErr?->getMessage() ?? 'unknown')); }
    return $pdo;
}

function ensure_tables(): void {
    $pdo = db();
    // Core tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('evaluator','reviewer','admin') NOT NULL DEFAULT 'evaluator',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Track every role assignment event (who assigned what role when)
    $pdo->exec("CREATE TABLE IF NOT EXISTS role_assignments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        role ENUM('evaluator','reviewer','admin') NOT NULL,
        assigned_by INT NULL,
        assigned_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX(user_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        skey VARCHAR(191) PRIMARY KEY,
        svalue TEXT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Attempt to migrate legacy role enum and values
    try {
        // Ensure enum has evaluator/reviewer/admin and default evaluator
        $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('evaluator','reviewer','admin') NOT NULL DEFAULT 'evaluator'");
    } catch (Throwable $e) { /* ignore if not needed */ }
    try {
        // Map legacy 'user' role to 'evaluator'
        $pdo->exec("UPDATE users SET role='evaluator' WHERE role='user'");
    } catch (Throwable $e) { /* ignore */ }

    $pdo->exec("CREATE TABLE IF NOT EXISTS assessments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        started_at DATETIME NOT NULL,
        completed_at DATETIME NULL,
        deleted_at DATETIME NULL,
        score FLOAT DEFAULT 0,
        risk_level VARCHAR(50) DEFAULT NULL,
        contact_email VARCHAR(255) DEFAULT NULL,
        organization_name VARCHAR(255) DEFAULT NULL,
        assessor_name VARCHAR(255) DEFAULT NULL,
        org_status VARCHAR(100) DEFAULT NULL,
        user_id INT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    // Ensure columns exist for legacy DBs
    ensure_column('assessments', 'assessor_name', "VARCHAR(255) DEFAULT NULL AFTER organization_name");
    ensure_column('assessments', 'org_status', "VARCHAR(100) DEFAULT NULL AFTER assessor_name");
    ensure_column('assessments', 'deleted_at', 'DATETIME NULL AFTER completed_at');

    $pdo->exec("CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(50) NULL UNIQUE,
        text TEXT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    // Ensure columns exist for legacy DBs
    ensure_column('questions', 'category', "VARCHAR(100) NULL AFTER text");
    ensure_column('questions', 'category_id', "INT NULL AFTER category");
    ensure_column('questions', 'weight', "INT DEFAULT 1 AFTER category_id");

    $pdo->exec("CREATE TABLE IF NOT EXISTS answers (
        assessment_id INT NOT NULL,
        question_id INT NOT NULL,
        answer_value TINYINT NOT NULL DEFAULT 0,
        notes TEXT NULL,
        PRIMARY KEY (assessment_id, question_id),
        FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    // Ensure column for legacy DBs
    ensure_column('answers', 'notes', 'TEXT NULL AFTER answer_value');

    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(100) UNIQUE,
        name VARCHAR(255) NOT NULL,
        description TEXT NULL,
        weight INT DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS documents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        assessment_id INT NOT NULL,
        category_id INT NOT NULL,
        question_id INT NULL,
        original_name VARCHAR(255) NOT NULL,
        stored_name VARCHAR(255) NOT NULL,
        mime VARCHAR(127) NULL,
        size INT NULL,
        status ENUM('PENDING','PASS','FAIL') DEFAULT 'PENDING',
        notes TEXT NULL,
        uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        reviewed_by INT NULL,
        reviewed_at DATETIME NULL,
        reviewers TEXT NULL,
        current_reviewer_idx INT DEFAULT 0,
        FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    // Add question_id column if it doesn't exist (for existing installations)
    ensure_column('documents', 'question_id', 'INT NULL AFTER category_id');
    // Ensure review meta columns for legacy databases
    ensure_column('documents', 'reviewed_by', 'INT NULL AFTER uploaded_at');
    ensure_column('documents', 'reviewed_at', 'DATETIME NULL AFTER reviewed_by');
    // Ensure reviewer workflow columns exist for older databases
    ensure_column('documents', 'reviewers', "TEXT NULL AFTER reviewed_at");
    ensure_column('documents', 'current_reviewer_idx', "INT DEFAULT 0 AFTER reviewers");

    $pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        message TEXT,
        document_id INT NULL,
        event_type VARCHAR(50) NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Backfill missing columns for notifications on legacy DBs
    ensure_column('notifications', 'document_id', 'INT NULL AFTER user_id');
    ensure_column('notifications', 'event_type', "VARCHAR(50) NULL AFTER message");

    $pdo->exec("CREATE TABLE IF NOT EXISTS log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        action VARCHAR(255),
        details TEXT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS document_review_steps (
        id INT AUTO_INCREMENT PRIMARY KEY,
        document_id INT NOT NULL,
        reviewer_id INT NULL,
        action ENUM('PENDING','PASS','FAIL','COMMENT') NOT NULL,
        notes TEXT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed admin if not exists
    try {
        $chk = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
        if ($chk === 0) {
            $hash = password_hash('admin1234', PASSWORD_BCRYPT);
            $st = $pdo->prepare('INSERT INTO users (username,email,password_hash,role) VALUES (?,?,?,\'admin\')');
            $st->execute(['admin','admin@example.com',$hash]);
        }
    } catch (Throwable $e) { /* ignore */ }
    
    // Seed sample reviewers if not exists
    try {
        $reviewerCount = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='reviewer'")->fetchColumn();
        if ($reviewerCount === 0) {
            $reviewers = [
                ['reviewer1', 'reviewer1@example.com', 'รีวิวเวอร์คนที่ 1'],
                ['reviewer2', 'reviewer2@example.com', 'รีวิวเวอร์คนที่ 2'],
                ['chief_reviewer', 'chief@example.com', 'หัวหน้ารีวิวเวอร์']
            ];
            $stmt = $pdo->prepare('INSERT INTO users (username,email,password_hash,role) VALUES (?,?,?,\'reviewer\')');
            foreach ($reviewers as $r) {
                $hash = password_hash('password123', PASSWORD_BCRYPT);
                $stmt->execute([$r[0], $r[1], $hash]);
            }
        }
    } catch (Throwable $e) { /* ignore */ }
}

function ensure_column(string $table, string $column, string $definition): void {
    $pdo = db();
    $check = $pdo->prepare("SELECT COUNT(*) AS c FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?");
    $check->execute([$table, $column]);
    $exists = (int)$check->fetch()['c'] > 0;
    if (!$exists) {
        $pdo->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
    }
}

function settings_get(string $key, ?string $default = null): ?string {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT svalue FROM settings WHERE skey = ?');
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? (string)$row['svalue'] : $default;
}

function settings_set(string $key, ?string $value): void {
    $pdo = db();
    $stmt = $pdo->prepare('INSERT INTO settings (skey, svalue) VALUES (?, ?) ON DUPLICATE KEY UPDATE svalue = VALUES(svalue)');
    $stmt->execute([$key, $value]);
}

function seed_questions(): void {
    $pdo = db();
    $count = (int)$pdo->query('SELECT COUNT(*) AS c FROM questions')->fetch()['c'];
    if ($count > 0) return;
    $questions = [
        ['code' => 'PDPA01', 'text' => 'องค์กรของคุณมีการเก็บรวบรวมข้อมูลส่วนบุคคลหรือไม่?', 'category' => 'พื้นฐาน', 'weight' => 2],
        ['code' => 'PDPA02', 'text' => 'มีนโยบายคุ้มครองข้อมูลส่วนบุคคลที่เผยแพร่ต่อสาธารณะหรือไม่?', 'category' => 'นโยบาย', 'weight' => 2],
        ['code' => 'PDPA03', 'text' => 'มีการขอความยินยอมอย่างชัดเจนก่อนเก็บข้อมูลหรือไม่?', 'category' => 'ความยินยอม', 'weight' => 3],
        ['code' => 'PDPA04', 'text' => 'มีการกำหนดวัตถุประสงค์ในการใช้ข้อมูลอย่างชัดเจนหรือไม่?', 'category' => 'วัตถุประสงค์', 'weight' => 2],
        ['code' => 'PDPA05', 'text' => 'มีมาตรการรักษาความมั่นคงปลอดภัยของข้อมูลหรือไม่?', 'category' => 'ความปลอดภัย', 'weight' => 3],
        ['code' => 'PDPA06', 'text' => 'มีการจำกัดการเข้าถึงข้อมูลตามบทบาทหน้าที่หรือไม่?', 'category' => 'การเข้าถึง', 'weight' => 2],
        ['code' => 'PDPA07', 'text' => 'มีการบันทึกการประมวลผลข้อมูล (RoPA) หรือไม่?', 'category' => 'การบันทึก', 'weight' => 2],
        ['code' => 'PDPA08', 'text' => 'มีกระบวนการรองรับคำร้องของเจ้าของข้อมูล (DSAR) หรือไม่?', 'category' => 'สิทธิของเจ้าของข้อมูล', 'weight' => 3],
        ['code' => 'PDPA09', 'text' => 'มีขั้นตอนการแจ้งเหตุข้อมูลรั่วไหล (Data Breach) หรือไม่?', 'category' => 'เหตุฉุกเฉิน', 'weight' => 3],
        ['code' => 'PDPA10', 'text' => 'มีการทำสัญญากับผู้ประมวลผลข้อมูล (Processor) อย่างเหมาะสมหรือไม่?', 'category' => 'สัญญา', 'weight' => 2],
    ];
    $stmt = $pdo->prepare('INSERT INTO questions (code, text, category, weight) VALUES (:code, :text, :category, :weight)');
    foreach ($questions as $q) {
        $stmt->execute($q);
    }
}

function seed_categories_from_questions(): void {
    $pdo = db();
    // create categories from distinct questions.category if not exist
        $catList = [
            ['code'=>'BASIC','name'=>'นโยบายพื้นฐาน'],
            ['code'=>'POLICY','name'=>'นโยบายคุ้มครอง'],
            ['code'=>'CONSENT','name'=>'การขอความยินยอม'],
            ['code'=>'PURPOSE','name'=>'วัตถุประสงค์การใช้'],
            ['code'=>'SECURITY','name'=>'ความมั่นคงปลอดภัย'],
            ['code'=>'ACCESS','name'=>'การเข้าถึงข้อมูล'],
            ['code'=>'RECORD','name'=>'การบันทึกข้อมูล'],
            ['code'=>'RIGHTS','name'=>'สิทธิของเจ้าของข้อมูล'],
            ['code'=>'EMERGENCY','name'=>'เหตุฉุกเฉิน'],
            ['code'=>'CONTRACT','name'=>'สัญญา'],
        ];
        foreach ($catList as $cat) {
            $stmt = $pdo->prepare('INSERT IGNORE INTO categories (code, name, weight) VALUES (?, ?, 1)');
            $stmt->execute([$cat['code'], $cat['name']]);
        }
}

function get_category_scores(int $assessment_id): array {
    $pdo = db();
    $sql = 'SELECT c.id AS category_id, c.name AS category_name, q.id AS qid, COALESCE(a.answer_value,0) AS val
            FROM categories c
            JOIN questions q ON (q.category_id = c.id OR q.category = c.name)
            LEFT JOIN answers a ON a.question_id = q.id AND a.assessment_id = :aid
            ORDER BY c.id, q.id';
    $st = $pdo->prepare($sql);
    $st->execute([':aid'=>$assessment_id]);
    $agg = [];
    foreach ($st as $r) {
        $cid = (int)$r['category_id'];
        if (!isset($agg[$cid])) $agg[$cid] = ['name'=>$r['category_name'], 'sum'=>0, 'count'=>0];
        $val = max(1, min(3, (int)$r['val']));
        $agg[$cid]['sum'] += $val;
        $agg[$cid]['count']++;
    }
    $out = [];
    foreach ($agg as $cid => $v) {
        $avg = $v['count']>0 ? round($v['sum']/$v['count'],2) : 0;
        $color = 'แดง';
        if ($avg >= 2.6) $color = 'เขียว';
        elseif ($avg >= 2.1) $color = 'เหลือง';
        $out[] = ['category_id'=>$cid, 'category_name'=>$v['name'], 'avg'=>$avg, 'color'=>$color];
            $catMap = [];
            foreach (db()->query('SELECT id, name FROM categories') as $row) {
                $catMap[$row['name']] = $row['id'];
            }
    }
    return $out;
}

function get_documents_for(int $assessment_id, int $category_id): array {
    $st = db()->prepare('SELECT * FROM documents WHERE assessment_id = ? AND category_id = ? ORDER BY uploaded_at DESC');
    $st->execute([$assessment_id, $category_id]);
    return $st->fetchAll();
}

function import_categories_questions_from_csv(string $csvPath): array {
    $pdo = db();
    if (!file_exists($csvPath)) throw new RuntimeException('CSV file not found');
    $f = fopen($csvPath, 'r');
    if (!$f) throw new RuntimeException('Cannot open CSV');
    $header = fgetcsv($f);
    if (!$header) throw new RuntimeException('Empty CSV');
    // Normalize headers
    $map = [];
    foreach ($header as $i => $h) { $map[strtolower(trim($h))] = $i; }
    $get = function(array $row, string $key) use ($map) { return isset($map[$key]) ? trim((string)($row[$map[$key]] ?? '')) : ''; };

    $insCat = $pdo->prepare('INSERT INTO categories (code, name, description, weight) VALUES (?,?,?,?)');
    $selCatByCode = $pdo->prepare('SELECT id FROM categories WHERE code = ?');
    $selCatByName = $pdo->prepare('SELECT id FROM categories WHERE name = ?');
    $updCat = $pdo->prepare('UPDATE categories SET name=?, description=?, weight=? WHERE id=?');
    $insQ = $pdo->prepare('INSERT INTO questions (code, text, category, category_id, weight) VALUES (?,?,?,?,?)');
    $selQ = $pdo->prepare('SELECT id FROM questions WHERE code = ?');
    $updQ = $pdo->prepare('UPDATE questions SET text=?, category=?, category_id=?, weight=? WHERE id=?');

    $stats = ['categories_created'=>0,'categories_updated'=>0,'questions_created'=>0,'questions_updated'=>0];

    while (($row = fgetcsv($f)) !== false) {
        $catCode = $get($row,'category_code');
        $catName = $get($row,'category_name');
        $catWeight = (int)($get($row,'category_weight') ?: 1);
        $catDesc = $get($row,'category_description');
        $qCode = $get($row,'question_code');
        $qText = $get($row,'question_text');
        $qWeight = (int)($get($row,'question_weight') ?: 1);
        if ($catName === '' || $qText === '') continue;
        // Upsert category
        $cid = null;
        if ($catCode !== '') {
            $selCatByCode->execute([$catCode]);
            $cid = ($r=$selCatByCode->fetch()) ? (int)$r['id'] : null;
        }
        if ($cid === null) {
            $selCatByName->execute([$catName]);
            $cid = ($r=$selCatByName->fetch()) ? (int)$r['id'] : null;
        }
        if ($cid === null) {
            $insCat->execute([$catCode ?: preg_replace('/\s+/', '_', mb_substr($catName,0,50)), $catName, $catDesc ?: null, $catWeight]);
            $cid = (int)$pdo->lastInsertId();
            $stats['categories_created']++;
        } else {
            $updCat->execute([$catName, $catDesc ?: null, $catWeight, $cid]);
            $stats['categories_updated']++;
        }
        // Upsert question by code if provided, else create new
        if ($qCode !== '') {
            $selQ->execute([$qCode]);
            $qid = ($r=$selQ->fetch()) ? (int)$r['id'] : 0;
            if ($qid > 0) {
                $updQ->execute([$qText, $catName, $cid, $qWeight, $qid]);
                $stats['questions_updated']++;
            } else {
                $insQ->execute([$qCode, $qText, $catName, $cid, $qWeight]);
                $stats['questions_created']++;
            }
        } else {
            $insQ->execute([null, $qText, $catName, $cid, $qWeight]);
            $stats['questions_created']++;
        }
    }
    fclose($f);
    return $stats;
}

function calculate_score_and_level(int $assessment_id): array {
    $pdo = db();
    $sql = 'SELECT a.answer_value FROM answers a JOIN questions q ON a.question_id = q.id WHERE a.assessment_id = ?';
    $rows = $pdo->prepare($sql);
    $rows->execute([$assessment_id]);
    $total = 0; $count = 0;
    foreach ($rows as $r) {
        $val = max(1, min(3, (int)$r['answer_value']));
        $total += $val;
        $count++;
    }
    $avg = $count > 0 ? round($total / $count, 2) : 0;
    $color = 'แดง';
    if ($avg >= 2.6) $color = 'เขียว';
    elseif ($avg >= 2.1) $color = 'เหลือง';
    return [$avg, $color];
}

// --- Notifications helpers ---
function add_notification(int $user_id, string $message, ?int $document_id = null, ?string $event_type = null): void {
    $pdo = db();
    // event_type example: 'doc_assigned','doc_reviewed','doc_uploaded'
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, document_id, event_type) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $message, $document_id, $event_type]);
}

function get_user(int $id): ?array {
    $st = db()->prepare('SELECT id, username, email, role FROM users WHERE id = ?');
    $st->execute([$id]);
    $u = $st->fetch();
    return $u ?: null;
}

function get_notifications(int $user_id, int $limit = 10): array {
    $pdo = db();
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function mark_notification_read(int $id): void {
    $pdo = db();
    $stmt = $pdo->prepare("UPDATE notifications SET is_read=1 WHERE id=?");
    $stmt->execute([$id]);
}

// --- Audit Log ---
function add_log(?int $user_id, string $action, string $details): void {
    $pdo = db();
    $stmt = $pdo->prepare("INSERT INTO log (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
}

// --- Role/Permission helpers ---

function get_user_role(int $user_id): string {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();
    return $row ? ($row['role'] ?? 'evaluator') : 'evaluator';
}

function assign_role(?int $admin_user_id, int $target_user_id, string $role): void {
    $valid = ['evaluator','reviewer','admin'];
    if (!in_array($role, $valid, true)) { $role = 'evaluator'; }
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $pdo->prepare('UPDATE users SET role=? WHERE id=?')->execute([$role, $target_user_id]);
        $pdo->prepare('INSERT INTO role_assignments (user_id, role, assigned_by) VALUES (?,?,?)')
            ->execute([$target_user_id, $role, $admin_user_id]);
        add_log($admin_user_id, 'assign_role', "Set role of user #{$target_user_id} to {$role}");
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// --- Document Review helpers ---
function get_document_review_steps(int $document_id): array {
    $st = db()->prepare('
        SELECT drs.*, u.username as reviewer_name 
        FROM document_review_steps drs
        LEFT JOIN users u ON drs.reviewer_id = u.id 
        WHERE drs.document_id = ? 
        ORDER BY drs.id ASC
    ');
    $st->execute([$document_id]);
    return $st->fetchAll();
}

/**
 * Update document status and manage multi-step reviewer flow.
 * Rules:
 * - If action PASS: advance current_reviewer_idx; if last reviewer passed -> status PASS.
 * - If action FAIL: set status FAIL and do not advance.
 * - If action COMMENT: keep status PENDING.
 * Always appends a record to document_review_steps and sets reviewed_by/at.
 */
function update_document_status(int $document_id, string $action, ?int $reviewer_id = null, ?string $notes = null): void {
    $pdo = db();
    $allowed = ['PENDING','PASS','FAIL','COMMENT'];
    if (!in_array($action, $allowed, true)) { $action = 'PENDING'; }
    $pdo->beginTransaction();
    try {
        $doc = $pdo->prepare('SELECT id, status, reviewers, current_reviewer_idx FROM documents WHERE id=? FOR UPDATE');
        $doc->execute([$document_id]);
        $d = $doc->fetch();
        if (!$d) { throw new RuntimeException('Document not found'); }
        $reviewers = [];
        if (!empty($d['reviewers'])) {
            $tmp = json_decode((string)$d['reviewers'], true);
            if (is_array($tmp)) { $reviewers = array_values(array_map('intval', $tmp)); }
        }
        $curIdx = (int)($d['current_reviewer_idx'] ?? 0);

        // Log the step first
        $pdo->prepare('INSERT INTO document_review_steps (document_id, reviewer_id, action, notes) VALUES (?,?,?,?)')
            ->execute([$document_id, $reviewer_id, $action, $notes]);

        $newStatus = $d['status'];
        $newIdx = $curIdx;
        if ($action === 'PASS') {
            if (count($reviewers) > 0 && $curIdx < count($reviewers) - 1) {
                $newIdx = $curIdx + 1; // advance to next reviewer
                $newStatus = 'PENDING';
            } else {
                $newStatus = 'PASS'; // last reviewer approved
            }
        } elseif ($action === 'FAIL') {
            $newStatus = 'FAIL';
        } elseif ($action === 'COMMENT') {
            $newStatus = 'PENDING';
        } else { // PENDING
            $newStatus = 'PENDING';
        }

        // อัปเดตสถานะ + ผู้ตรวจ + เวลาตรวจ ถ้ามีคอลัมน์ในฐานข้อมูล
        try {
            $upd = $pdo->prepare('UPDATE documents SET status=?, notes=COALESCE(NULLIF(?, ""), notes), reviewed_by=?, reviewed_at=NOW(), current_reviewer_idx=? WHERE id=?');
            $upd->execute([$newStatus, (string)$notes, $reviewer_id, $newIdx, $document_id]);
        } catch (Throwable $e) {
            // Fallback: กรณีฐานข้อมูลเก่ายังไม่มีคอลัมน์ reviewed_by / reviewed_at
            $msg = (string)$e->getMessage();
            if (strpos($msg, 'Unknown column') !== false || $e->getCode() === '42S22') {
                $upd2 = $pdo->prepare('UPDATE documents SET status=?, notes=COALESCE(NULLIF(?, ""), notes), current_reviewer_idx=? WHERE id=?');
                $upd2->execute([$newStatus, (string)$notes, $newIdx, $document_id]);
            } else {
                throw $e;
            }
        }

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

// ========================= CII Self-Assessment (D2) =========================
// Lightweight module to mirror the Excel-like scoring (1-3) with per-item rows
// and overall averages. We keep it isolated via its own tables and helpers.

function ensure_cii_tables(): void {
    $pdo = db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS cii_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section VARCHAR(20) NOT NULL,
        num INT NOT NULL,
        objective TEXT NULL,
        requirement TEXT NULL,
        evident TEXT NULL,
        UNIQUE KEY uniq_section_num (section, num)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS cii_assessments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section VARCHAR(20) NOT NULL,
        assessed_at DATE NOT NULL,
        note TEXT NULL,
        user_id INT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS cii_scores (
        assessment_id INT NOT NULL,
        item_id INT NOT NULL,
        score TINYINT NOT NULL,
        note TEXT NULL,
        PRIMARY KEY (assessment_id, item_id),
        FOREIGN KEY (assessment_id) REFERENCES cii_assessments(id) ON DELETE CASCADE,
        FOREIGN KEY (item_id) REFERENCES cii_items(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // หลักฐาน/คอมเมนต์ต่อข้อ
    $pdo->exec("CREATE TABLE IF NOT EXISTS cii_evidence (
        id INT AUTO_INCREMENT PRIMARY KEY,
        assessment_id INT NOT NULL,
        item_id INT NOT NULL,
        file VARCHAR(255) NULL,
        note TEXT NULL,
        uploaded_by INT NULL,
        uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (assessment_id) REFERENCES cii_assessments(id) ON DELETE CASCADE,
        FOREIGN KEY (item_id) REFERENCES cii_items(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}

function cii_seed_items_D2(): void {
    $pdo = db();
    $exists = (int)$pdo->query("SELECT COUNT(*) FROM cii_items WHERE section='D2'")->fetchColumn();
    if ($exists > 0) return;
    $ins = $pdo->prepare('INSERT INTO cii_items (section, num, objective, requirement, evident) VALUES (?,?,?,?,?)');
    $rows = [
        [13,
         "มีการจัดโครงสร้างองค์กรให้มีการถ่วงดุล โดยจัดโครงสร้างองค์กรพร้อมกำหนดอำนาจ บทบาทหน้าที่ และความรับผิดชอบ (Authorities, Roles and Responsibilities) ที่ชัดเจน เกี่ยวกับการบริหารจัดการความมั่นคงปลอดภัยไซเบอร์ให้มีการถ่วงดุลตามหลักการควบคุม กำกับ และตรวจสอบ (Three Lines of Defense)",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 1.1",
         "เอกสารผังโครงสร้างขององค์กร และการกำหนดอำนาจ บทบาทหน้าที่ และความรับผิดชอบ ที่แสดงการถ่วงดุลตามหลักการควบคุม กำกับ และตรวจสอบ (Three Lines of Defense)"
        ],
        [14,
         "หน่วยงานมีผู้บริหารระดับสูงที่ทำหน้าที่บริหารจัดการความมั่นคงปลอดภัยสารสนเทศ (Chief Information Security Officer : CISO) หรือเทียบเท่าที่ปฏิบัติหน้าที่เสมือน CISO ของหน่วยงาน",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 1.3",
         "เอกสารคำสั่งแต่งตั้ง ผู้บริหารระดับสูง ที่ทำหน้าที่บริหารจัดการความมั่นคงปลอดภัยสารสนเทศ (CISO) หรือเทียบเท่า"
        ],
        [15,
         "ผู้บริหารระดับสูงที่ทำหน้าที่บริหารจัดการความมั่นคงปลอดภัยสารสนเทศมีความเป็นอิสระจากงานด้านการปฏิบัติงานเทคโนโลยีสารสนเทศ (IT operation) และงานด้านพัฒนาระบบเทคโนโลยีสารสนเทศ (IT Development) และมีอำนาจหน้าที่ (Authority) เพียงพอในการปฏิบัติงานในหน้าที่ CISO ได้อย่างมีประสิทธิภาพและประสิทธิผล",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 1.3",
         "เอกสารแสดงอำนาจหน้าที่หรือความรับผิดชอบของ CISO หรือเทียบเท่า"
        ],
        [16,
         "มีการจัดทำกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์เป็นลายลักษณ์อักษร",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.1",
         "เอกสารกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์"
        ],
        [17,
         "มีเกณฑ์ประเมินความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และระดับความเสี่ยงที่ยอมรับได้ (Risk Appetite)",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.1",
         "เอกสารกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์"
        ],
        [18,
         "มีวิธีการประเมินความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.1",
         "เอกสารกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์"
        ],
        [19,
         "มีการเฝ้าระวังและติดตามความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.1",
         "เอกสารกรอบการบริหารความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์"
        ],
        [20,
         "มีการเก็บรักษารายการความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ที่ระบุไว้ในทะเบียนความเสี่ยง (Risk Register) ที่เกี่ยวข้องกับบริการที่สำคัญของหน่วยงาน",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.2",
         "เอกสารรายการความเสี่ยงด้านการรักษาความมั่นคงปลอดภัยไซเบอร์ที่ระบุไว้ในทะเบียนความเสี่ยง (Risk Register)"
        ],
        [21,
         "มีการติดตามความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ที่ระบุไว้อย่างสม่ำเสมอเพื่อให้แน่ใจว่าอยู่ภายใต้เกณฑ์ระดับความเสี่ยงที่ยอมรับได้",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 2.3",
         "เอกสารรายงานผลการติดตาม/ประเมินผลการดำเนินงานเกี่ยวกับการบริหารความเสี่ยง"
        ],
        [22,
         "มีการกำหนดและอนุมัตินโยบาย มาตรฐาน และแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญของหน่วยงาน จากภัยคุกคามทางไซเบอร์",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 3.1",
         "เอกสารนโยบาย มาตรฐานและแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญ (เอกสารที่มีการอนุมัติ/ประกาศใช้)"
        ],
        [23,
         "มีนโยบาย มาตรฐาน และแนวปฏิบัติในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญของหน่วยงาน ที่สอดคล้องกับข้อกำหนดและทิศทางระดับภาคส่วน/ระดับประเทศ",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 3.1",
         "เอกสารนโยบาย มาตรฐาน และแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญ"
        ],
        [24,
         "มีนโยบาย มาตรฐาน และแนวปฏิบัติที่มีการเผยแพร่และสื่อสารไปยังบุคลากรและบุคคลภายนอกทุกคนที่ทำหน้าที่หรือสามารถเข้าถึงบริการที่สำคัญของหน่วยงาน",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 3.1",
         "เอกสารนโยบาย มาตรฐาน และแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญ"
        ],
        [25,
         "มีการทบทวนนโยบาย มาตรฐาน และแนวทางปฏิบัติกับสภาพแวดล้อมการปฏิบัติการไซเบอร์ของบริการที่สำคัญ และภูมิทัศน์ภัยคุกคามทางไซเบอร์ในปัจจุบัน อย่างน้อยปีละ 1 ครั้ง",
         "นโยบายบริหารจัดการ ภาคผนวก ข้อ 3.2",
         "เอกสารรายงานผลการทบทวนประจำปี เกี่ยวกับนโยบาย มาตรฐาน และแนวทางในการจัดการความเสี่ยงด้านความมั่นคงปลอดภัยไซเบอร์ และการป้องกันบริการที่สำคัญ"
        ],
    ];
    foreach ($rows as $r) { $ins->execute(['D2', $r[0], $r[1], $r[2], $r[3]]); }
}

function cii_seed_items_D1(): void {
    $pdo = db();
    $exists = (int)$pdo->query("SELECT COUNT(*) FROM cii_items WHERE section='D1'")->fetchColumn();
    if ($exists > 0) return;
    $ins = $pdo->prepare('INSERT INTO cii_items (section, num, objective, requirement, evident) VALUES (?,?,?,?,?)');
    $rows = [
        [1, "มีการดำเนินการให้มีนโยบายแผนและแนวปฏิบัติฯ การรักษาความมั่นคงปลอดภัยไซเบอร์", "ม. 43", "นโยบายฯ, แผนฯ, แผนผังองค์กร, คำสั่งแต่งตั้งผู้รับผิดชอบ, CISO"],
        [2, "มีการจัดทำประมวลแนวทางปฏิบัติและกรอบมาตรฐานฯ ที่สอดคล้องกับแผนฯ", "ม. 44", "แผนการสอบทาน, ประมวลแนวทางปฏิบัติ, Incident Response Plan"],
        [3, "มีการป้องกัน รับมือ และตอบสนองเหตุการณ์ความมั่นคงไซเบอร์ฯ", "ม. 45", "แผนรับมือ, รายงานเหตุการณ์, Cybersecurity Incident Response Plan"],
        [4, "มีการประเมินระดับความเสี่ยงฯ และดำเนินการตามมาตรการฯ", "ม. 46", "รายงานประเมินความเสี่ยง, มาตรการควบคุม, รายงานผล"],
        [5, "มีการแจ้งเตือนและข้อมูลการติดต่อส่วนราชการที่เกี่ยวข้องฯ", "ม. 52", "หลักฐานการแจ้ง, หนังสือแจ้ง, Email, ช่องทางอื่น"],
        [6, "มีการเปลี่ยนแปลงหรืออัพเดทข้อมูลการติดต่อของกรมที่เกี่ยวข้องฯ", "ม. 52", "หลักฐานการแจ้งเปลี่ยนแปลง, หนังสือแจ้ง, Email, ช่องทางอื่น"],
        [7, "มีการประเมินและตรวจประเมินฯ", "ม. 54", "รายงานการตรวจประเมิน, รายงานผล, ประเมินประจำปี"],
        [8, "มีการจัดส่งผลสรุปรายงานการดำเนินการฯ", "ม. 54", "รายงานผล, รายงานผลตรวจสอบ, รายงานประจำปี"],
        [9, "มีนโยบายหรือข้อเสนอแนะในการรับมือเหตุการณ์ฯ", "ม. 56", "คู่มือ, ข้อเสนอแนะ, แนวทางปฏิบัติ, รายงาน"],
        [10, "มีการทดสอบการตอบสนองเหตุการณ์ฯ (Cyber Exercise)", "ม. 56", "หลักฐานการทดสอบ, รายงานผล, National Cyber Exercise, รายงานผลทดสอบ"],
        [11, "มีการบันทึกและรายงานผลการดำเนินงานฯ", "ม. 57", "รายงานผล, รายงานการดำเนินงาน, รายงานประจำปี"],
        [12, "หากเกิดหรือคาดว่าจะเกิดเหตุการณ์ฯ ให้แจ้งหน่วยงานที่เกี่ยวข้องฯ", "ม. 58", "หลักฐานการแจ้ง, รายงานการดำเนินการ, หนังสือแจ้ง, Email, ช่องทางอื่น"],
    ];
    foreach ($rows as $r) { $ins->execute(['D1', $r[0], $r[1], $r[2], $r[3]]); }
}

function cii_start_new_assessment(string $section, ?int $user_id = null): int {
    $pdo = db();
    $st = $pdo->prepare('INSERT INTO cii_assessments (section, assessed_at, note, user_id) VALUES (?, CURDATE(), NULL, ?)');
    $st->execute([$section, $user_id]);
    return (int)$pdo->lastInsertId();
}

function cii_get_items_with_scores(int $assessment_id): array {
    $pdo = db();
    $sql = 'SELECT i.*, s.score, s.note AS score_note
            FROM cii_items i
            LEFT JOIN cii_scores s ON s.item_id = i.id AND s.assessment_id = :aid
            WHERE i.section = (SELECT section FROM cii_assessments WHERE id = :aid)
            ORDER BY i.num';
    $st = $pdo->prepare($sql);
    $st->execute([':aid'=>$assessment_id]);
    return $st->fetchAll();
}

function cii_save_scores(int $assessment_id, array $scores, array $notes = []): void {
    $pdo = db();
    $up = $pdo->prepare('INSERT INTO cii_scores (assessment_id, item_id, score, note) VALUES (?,?,?,?)
                         ON DUPLICATE KEY UPDATE score=VALUES(score), note=VALUES(note)');
    foreach ($scores as $item_id => $val) {
        $v = (int)$val; if ($v<1 || $v>3) continue;
        $n = $notes[$item_id] ?? null; if ($n !== null) { $n = trim((string)$n); }
        $up->execute([$assessment_id, (int)$item_id, $v, $n]);
    }
}

function cii_average_for(int $assessment_id): float {
    $pdo = db();
    $st = $pdo->prepare('SELECT AVG(score) FROM cii_scores WHERE assessment_id = ?');
    $st->execute([$assessment_id]);
    $avg = (float)$st->fetchColumn();
    return $avg > 0 ? round($avg, 1) : 0.0;
}

function cii_last_average(string $section, int $excludeAssessmentId): float {
    $pdo = db();
    $st = $pdo->prepare('SELECT a.id FROM cii_assessments a WHERE a.section=? AND a.id<>? ORDER BY a.assessed_at DESC, a.id DESC LIMIT 1');
    $st->execute([$section, $excludeAssessmentId]);
    $lastId = (int)($st->fetchColumn() ?: 0);
    if ($lastId <= 0) return 0.0;
    return cii_average_for($lastId);
}

function cii_get_previous_assessment(string $section, int $excludeAssessmentId): ?array {
    $pdo = db();
    $st = $pdo->prepare('SELECT * FROM cii_assessments WHERE section=? AND id<>? ORDER BY assessed_at DESC, id DESC LIMIT 1');
    $st->execute([$section, $excludeAssessmentId]);
    $r = $st->fetch();
    return $r ?: null;
}

function cii_get_assessment(int $assessment_id): ?array {
    $st = db()->prepare('SELECT * FROM cii_assessments WHERE id=?');
    $st->execute([$assessment_id]);
    $r = $st->fetch();
    return $r ?: null;
}

function cii_get_scores_map(int $assessment_id): array {
    $map = [];
    $st = db()->prepare('SELECT item_id, score, note FROM cii_scores WHERE assessment_id=?');
    $st->execute([$assessment_id]);
    foreach ($st as $r) { $map[(int)$r['item_id']] = ['score'=>(int)$r['score'], 'note'=>$r['note']]; }
    return $map;
}
