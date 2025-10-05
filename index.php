<?php
// --- Helper functions must be defined before any router logic ---
function flash(string $key, ?string $val = null) {
    if ($val === null) {
        $v = $_SESSION['flash_'.$key] ?? null; unset($_SESSION['flash_'.$key]); return $v;
    } else {
        $_SESSION['flash_'.$key] = $val; return null;
    }
}

function form_token_issue(): string { $t = bin2hex(random_bytes(8)); $_SESSION['form_token'] = $t; return $t; }
function form_token_check(string $t): bool { $ok = isset($_SESSION['form_token']) && hash_equals($_SESSION['form_token'], $t); unset($_SESSION['form_token']); return $ok; }

function view(string $name, array $data = []) {
    extract($data);
    if (file_exists(__DIR__.'/vendor/autoload.php')) { require_once __DIR__.'/vendor/autoload.php'; }
    include __DIR__ . "/views/header.php";
    include __DIR__ . "/views/$name.php";
    include __DIR__ . "/views/footer.php";
}

function require_login(?string $after = null): void {
    if (empty($_SESSION['user'])) {
        if ($after !== null) {
            $_SESSION['after_login'] = $after;
        } else {
            $_SESSION['after_login'] = ($_SERVER['QUERY_STRING'] ?? '') ? ('?'.$_SERVER['QUERY_STRING']) : '?';
        }
        flash('auth','กรุณาเข้าสู่ระบบก่อนทำแบบประเมิน');
        header('Location: ?a=login');
        exit;
    }
}

function require_admin(): void {
    if (empty($_SESSION['is_admin'])) {
        header('Location: ?a=admin_login');
        exit;
    }
}

require_once __DIR__.'/db.php';
session_start();

$action = $_GET['a'] ?? 'home';

try {
    switch ($action) {
        // ...existing cases...
        case 'logout':
            session_unset();
            session_destroy();
            header('Location: ?');
            exit;

        case 'login':
            view('auth/login', ['flash' => flash('auth')]);
            break;

        case 'login_submit':
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $pdo = db();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $u = $stmt->fetch();
            if ($u && password_verify($password, $u['password_hash'])) {
                $_SESSION['user'] = ['id' => (int)$u['id'], 'username' => $u['username'], 'role' => $u['role']];
                $_SESSION['is_admin'] = ($u['role'] === 'admin' || $u['username'] === 'admin');
                flash('auth','เข้าสู่ระบบสำเร็จ');
                $after = $_SESSION['after_login'] ?? null; unset($_SESSION['after_login']);
                if ($after) { header('Location: '.$after); exit; }
                header('Location: ?'); exit;
            }
            flash('auth','ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'); header('Location: ?a=login'); exit;

        case 'register':
            view('auth/register', ['flash' => flash('auth')]);
            break;

        case 'register_submit':
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if ($username === '' || $email === '' || $password === '') { flash('auth','กรุณากรอกข้อมูลให้ครบ'); header('Location: ?a=register'); exit; }
            $pdo = db();
            try {
                $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
                $stmt->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT)]);
                flash('auth','สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ');
                header('Location: ?a=login');
                exit;
            } catch (Throwable $e) {
                flash('auth','ชื่อผู้ใช้หรืออีเมลถูกใช้แล้ว');
                header('Location: ?a=register');
                exit;
            }

        // ลบ assessment (admin หรือเจ้าของเท่านั้น)
        case 'delete_assessment':
            $id = (int)($_GET['id'] ?? 0);
            $pdo = db();
            $uid = $_SESSION['user']['id'] ?? null;
            $isAdmin = ($_SESSION['role'] ?? '') === 'admin';
            $owner = $pdo->prepare('SELECT user_id FROM assessments WHERE id=?');
            $owner->execute([$id]);
            $row = $owner->fetch();
            if (!$row) { echo 'ไม่พบข้อมูล'; exit; }
            if (!$isAdmin && $row['user_id'] != $uid) { http_response_code(403); exit('forbidden'); }
            $pdo->prepare('DELETE FROM assessments WHERE id=?')->execute([$id]);
            $pdo->prepare('DELETE FROM answers WHERE assessment_id=?')->execute([$id]);
            $pdo->prepare('DELETE FROM documents WHERE assessment_id=?')->execute([$id]);
            flash('auth','ลบ assessment เรียบร้อย');
            header('Location: ?a=history'); exit;

        // แก้ไข assessment (admin หรือเจ้าของเท่านั้น)
        case 'edit_assessment':
            $id = (int)($_GET['id'] ?? 0);
            $pdo = db();
            $uid = $_SESSION['user']['id'] ?? null;
            $isAdmin = ($_SESSION['role'] ?? '') === 'admin';
            $owner = $pdo->prepare('SELECT * FROM assessments WHERE id=?');
            $owner->execute([$id]);
            $row = $owner->fetch();
            if (!$row) { echo 'ไม่พบข้อมูล'; exit; }
            if (!$isAdmin && $row['user_id'] != $uid) { http_response_code(403); exit('forbidden'); }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $org = $_POST['organization_name'] ?? '';
                $assessor = $_POST['assessor_name'] ?? '';
                $status = $_POST['org_status'] ?? '';
                $pdo->prepare('UPDATE assessments SET organization_name=?, assessor_name=?, org_status=? WHERE id=?')->execute([$org, $assessor, $status, $id]);
                flash('auth','บันทึกข้อมูลเรียบร้อย');
                header('Location: ?a=history'); exit;
            }
            // แสดงฟอร์มแก้ไข
            ?>
            <form method="post">
                <label>หน่วยงาน: <input name="organization_name" value="<?= htmlspecialchars($row['organization_name']) ?>"></label><br>
                <label>ชื่อผู้ประเมิน: <input name="assessor_name" value="<?= htmlspecialchars($row['assessor_name']) ?>"></label><br>
                <label>สถานะหน่วยงาน: <input name="org_status" value="<?= htmlspecialchars($row['org_status']) ?>"></label><br>
                <button type="submit">บันทึก</button>
            </form>
            <?php
            exit;

        // สำรองข้อมูล (admin เท่านั้น)
        case 'backup':
            if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('forbidden'); }
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment;filename="backup_'.date('Ymd_His').'.zip"');
            // Demo: export assessments, answers, users as CSV in zip
            $zip = new ZipArchive();
            $tmp = tempnam(sys_get_temp_dir(), 'zip');
            $zip->open($tmp, ZipArchive::CREATE);
            foreach ([
                'assessments'=>['SELECT * FROM assessments','assessments.csv'],
                'answers'=>['SELECT * FROM answers','answers.csv'],
                'users'=>['SELECT * FROM users','users.csv']
            ] as $tbl=>$info) {
                $rows = $pdo->query($info[0])->fetchAll();
                $csv = fopen('php://temp','r+');
                if ($rows) fputcsv($csv, array_keys($rows[0]));
                foreach ($rows as $r) fputcsv($csv, $r);
                rewind($csv); $csvData = stream_get_contents($csv); fclose($csv);
                $zip->addFromString($info[1], $csvData);
            }
            $zip->close();
            readfile($tmp); unlink($tmp); exit;

        // กู้คืนข้อมูล (admin เท่านั้น, stub)
        case 'restore':
            if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('forbidden'); }
            ?>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="zip" accept=".zip" required>
                <button type="submit">Restore</button>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['zip'])) {
                // TODO: implement restore logic
                flash('auth','(Demo) ยังไม่รองรับการกู้คืน');
                header('Location: ?a=admin'); exit;
            }
            exit;

        // ฟีดแบ็ก/แจ้งปัญหา
        case 'feedback':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $msg = trim($_POST['msg'] ?? '');
                if ($msg) file_put_contents(__DIR__.'/feedback.log', date('c')."\t".($_SESSION['user']['username']??'guest')."\t$msg\n", FILE_APPEND);
                flash('auth','ขอบคุณสำหรับข้อเสนอแนะ/แจ้งปัญหา');
                header('Location: ?'); exit;
            }
            ?>
            <form method="post">
                <textarea name="msg" required style="width:100%;height:100px"></textarea><br>
                <button type="submit">ส่งฟีดแบ็ก/แจ้งปัญหา</button>
            </form>
            <?php
            exit;

        // เริ่มต้นแบบประเมิน
        case 'start':
            if (empty($_SESSION['user'])) { $_SESSION['after_login'] = '?a=start'; flash('auth','กรุณาเข้าสู่ระบบก่อนเริ่มทำแบบประเมิน'); header('Location: ?a=login'); exit; }
            view('start_form');
            break;

        case 'start_submit':
            require_login('?a=start');
            $org = trim($_POST['organization_name'] ?? '');
            $assessor = trim($_POST['assessor_name'] ?? '');
            $org_status = trim($_POST['org_status'] ?? '');
            $email = trim($_POST['contact_email'] ?? '');
            if ($org === '' || $assessor === '' || $org_status === '' || $email === '') {
                $error = 'กรุณากรอกข้อมูลให้ครบถ้วน';
                view('start_form', [
                    'error' => $error,
                    'organization_name' => $org,
                    'assessor_name' => $assessor,
                    'org_status' => $org_status,
                    'contact_email' => $email
                ]);
                break;
            }
            ensure_column('assessments', 'organization_name', 'VARCHAR(255) DEFAULT NULL');
            ensure_column('assessments', 'assessor_name', 'VARCHAR(255) DEFAULT NULL');
            ensure_column('assessments', 'org_status', 'VARCHAR(100) DEFAULT NULL');
            $pdo = db();
            $userId = $_SESSION['user']['id'] ?? null;
            if ($userId) { ensure_column('assessments','user_id','INT NULL'); }
            if ($userId) {
                $stmt = $pdo->prepare('INSERT INTO assessments (started_at, organization_name, assessor_name, org_status, contact_email, user_id) VALUES (NOW(), ?, ?, ?, ?, ?)');
                $stmt->execute([$org, $assessor, $org_status, $email, $userId]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO assessments (started_at, organization_name, assessor_name, org_status, contact_email) VALUES (NOW(), ?, ?, ?, ?)');
                $stmt->execute([$org, $assessor, $org_status, $email]);
            }
            $_SESSION['assessment_id'] = (int)$pdo->lastInsertId();
            // แจ้งเตือนแอดมินทางอีเมล
            notify_admin_email(
                'มีการประเมินใหม่ในระบบ PDPA',
                "องค์กร: $org\nผู้ประเมิน: $assessor\nสถานะ: $org_status\nอีเมล: $email\nเวลา: ".date('Y-m-d H:i:s')
            );
            header('Location: ?a=questions');
            exit;

        // Questions & answers
        case 'questions':
            require_login('?a=questions');
            $assessment_id = $_SESSION['assessment_id'] ?? null;
            if (!$assessment_id) { header('Location: ?'); exit; }
            $pdo = db();
            // Fetch all questions with category mapping
            $qs = $pdo->query('SELECT q.*, COALESCE(q.category_id, c.id) as cat_id, COALESCE(c.name, q.category) as cat_name
                               FROM questions q
                               LEFT JOIN categories c ON c.id = q.category_id OR c.name = q.category
                               ORDER BY cat_name, q.id')->fetchAll();
            $ansStmt = $pdo->prepare('SELECT question_id, answer_value, notes FROM answers WHERE assessment_id = ?');
            $ansStmt->execute([$assessment_id]);
            $answers = [];
            $notes = [];
            foreach ($ansStmt as $r) {
                $qid = (int)$r['question_id'];
                $answers[$qid] = (int)$r['answer_value'];
                if (array_key_exists('notes', $r)) { $notes[$qid] = (string)($r['notes'] ?? ''); }
            }
            // Group by category
            $byCat = [];
            foreach ($qs as $q) {
                $cid = (int)($q['cat_id'] ?? 0);
                $name = $q['cat_name'] ?? 'อื่นๆ';
                if (!isset($byCat[$cid])) $byCat[$cid] = ['id'=>$cid,'name'=>$name,'questions'=>[]];
                $byCat[$cid]['questions'][] = $q;
            }
            // Get category scores for progress header
            $catsScores = get_category_scores((int)$assessment_id);
            $scoreMap = [];
            foreach ($catsScores as $c) { $scoreMap[(int)$c['category_id']] = $c; }
            // Document counts per category for current assessment
            $dcStmt = $pdo->prepare('SELECT category_id, COUNT(*) AS c FROM documents WHERE assessment_id = ? GROUP BY category_id');
            $dcStmt->execute([$assessment_id]);
            $docCounts = [];
            foreach ($dcStmt as $r) { $docCounts[(int)$r['category_id']] = (int)$r['c']; }
            foreach ($byCat as $cid => &$cat) { $cat['doc_count'] = $docCounts[$cid] ?? 0; }
            unset($cat);
            view('questions', ['byCat' => $byCat, 'answers' => $answers, 'notes' => $notes, 'scoreMap' => $scoreMap]);
            break;
        case 'save_answers':
            require_login('?a=questions');
            $assessment_id = $_SESSION['assessment_id'] ?? null;
            if (!$assessment_id) { header('Location: ?'); exit; }
            if (!form_token_check($_POST['form_token'] ?? '')) { header('Location: ?a=questions'); exit; }
            $pdo = db();
            $pdo->beginTransaction();
            try {
                $stmtQ = $pdo->query('SELECT id FROM questions ORDER BY id');
                $upsert = $pdo->prepare('INSERT INTO answers (assessment_id, question_id, answer_value, notes) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE answer_value = VALUES(answer_value), notes = VALUES(notes)');
                foreach ($stmtQ as $q) {
                    $qid = (int)$q['id'];
                    $raw = $_POST['q'.$qid] ?? '';
                    $val = (int)$raw;
                    if (!in_array($val, [1,2,3], true)) { $val = 0; }
                    $note = trim($_POST['notes'.$qid] ?? '');
                    $upsert->execute([$assessment_id, $qid, $val, $note]);
                }
                [$percent, $level] = calculate_score_and_level($assessment_id);
                $finish = $pdo->prepare('UPDATE assessments SET completed_at = NOW(), score = ?, risk_level = ? WHERE id = ?');
                $finish->execute([$percent, $level, $assessment_id]);
                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }
            header('Location: ?a=result');
            exit;

        // Autosave a single answer (AJAX)
        case 'save_answer':
            header('Content-Type: application/json; charset=utf-8');
            $assessment_id = $_SESSION['assessment_id'] ?? null;
            if (!$assessment_id) { echo json_encode(['ok'=>false,'error'=>'no_assessment']); exit; }
            $qid = (int)($_POST['qid'] ?? 0);
            if ($qid<=0) { echo json_encode(['ok'=>false,'error'=>'bad_qid']); exit; }
            $hasVal = array_key_exists('val', $_POST);
            $hasNote = array_key_exists('note', $_POST);
            $val = (int)($_POST['val'] ?? 0);
            $note = $hasNote ? trim((string)$_POST['note']) : null;

            // Validate value if provided
            if ($hasVal && !in_array($val, [1,2,3], true)) {
                echo json_encode(['ok'=>false,'error'=>'bad_val']);
                exit;
            }

            // Branch to avoid unintentionally overwriting existing fields
            if ($hasVal && $hasNote) {
                $upsert = db()->prepare('INSERT INTO answers (assessment_id, question_id, answer_value, notes) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE answer_value=VALUES(answer_value), notes=VALUES(notes)');
                $upsert->execute([$assessment_id, $qid, $val, $note]);
            } elseif ($hasVal) {
                $upsert = db()->prepare('INSERT INTO answers (assessment_id, question_id, answer_value) VALUES (?,?,?) ON DUPLICATE KEY UPDATE answer_value=VALUES(answer_value)');
                $upsert->execute([$assessment_id, $qid, $val]);
            } elseif ($hasNote) {
                // Insert with default 0 if new; update only notes if exists
                $upsert = db()->prepare('INSERT INTO answers (assessment_id, question_id, answer_value, notes) VALUES (?,?,0,?) ON DUPLICATE KEY UPDATE notes=VALUES(notes)');
                $upsert->execute([$assessment_id, $qid, $note]);
            } else {
                echo json_encode(['ok'=>false,'error'=>'no_fields']);
                exit;
            }

            echo json_encode(['ok'=>true]);
            exit;

        // Result & print
        case 'result':
            require_login('?a=result');
            $assessment_id = $_SESSION['assessment_id'] ?? null;
            if (!$assessment_id) { header('Location: ?'); exit; }
            $pdo = db();
            $assess = $pdo->prepare('SELECT * FROM assessments WHERE id = ?');
            $assess->execute([$assessment_id]);
            $assessment = $assess->fetch();
            [$percent, $level] = [$assessment['score'] ?? 0, $assessment['risk_level'] ?? 'ไม่ทราบ'];
            $answers = $pdo->prepare('SELECT q.code, q.text, q.category, q.weight, a.answer_value FROM questions q LEFT JOIN answers a ON a.question_id = q.id AND a.assessment_id = ? ORDER BY q.id');
            $answers->execute([$assessment_id]);
            view('result', [
                'assessment' => $assessment,
                'answers' => $answers->fetchAll(),
                'percent' => $percent,
                'level' => $level,
                'categories' => get_category_scores((int)$assessment['id']),
            ]);
            break;
        case 'print':
            require_login('?a=print');
            $assessment_id = $_SESSION['assessment_id'] ?? null;
            if (!$assessment_id) { header('Location: ?'); exit; }
            $pdo = db();
            $assess = $pdo->prepare('SELECT * FROM assessments WHERE id = ?');
            $assess->execute([$assessment_id]);
            $assessment = $assess->fetch();
            $answers = $pdo->prepare('SELECT q.code, q.text, q.category, q.weight, a.answer_value FROM questions q LEFT JOIN answers a ON a.question_id = q.id AND a.assessment_id = ? ORDER BY q.id');
            $answers->execute([$assessment_id]);
            view('print', [
                'assessment' => $assessment,
                'answers' => $answers->fetchAll(),
            ]);
            break;

        // Dashboard by categories
        case 'dashboard':
            require_login('?a=dashboard');
            $assessment_id = $_SESSION['assessment_id'] ?? null;
            
            // If no assessment_id in session, try to get the latest assessment for this user
            if (!$assessment_id) {
                $pdo = db();
                $user = $_SESSION['user'] ?? null;
                if ($user) {
                    $stmt = $pdo->prepare('SELECT id FROM assessments WHERE user_id = ? ORDER BY started_at DESC LIMIT 1');
                    $stmt->execute([$user['id']]);
                    $row = $stmt->fetch();
                    if ($row) {
                        $assessment_id = (int)$row['id'];
                        $_SESSION['assessment_id'] = $assessment_id;
                    }
                }
            }
            
            if (!$assessment_id) { 
                view('dashboard', ['cats' => [], 'error' => 'กรุณาทำแบบประเมินก่อนใช้งาน Dashboard']);
                break;
            }
            
            $cats = get_category_scores((int)$assessment_id);
            view('dashboard', ['cats' => $cats]);
            break;

        // Documents upload & review per category
        case 'upload_doc':
            require_login();
            $assessment_id = $_SESSION['assessment_id'] ?? null; $cid = (int)($_GET['cid'] ?? 0);
            if (!$assessment_id || $cid<=0) { header('Location: ?'); exit; }
            view('upload_doc', ['cid'=>$cid]);
            break;
        case 'upload_doc_submit':
            require_login();
            $assessment_id = $_SESSION['assessment_id'] ?? null; $cid = (int)($_POST['cid'] ?? 0);
            if (!$assessment_id || $cid<=0) { header('Location: ?'); exit; }
            if (!isset($_FILES['doc']) || $_FILES['doc']['error'] !== UPLOAD_ERR_OK) { echo 'อัปโหลดไฟล์ไม่สำเร็จ'; exit; }
            $dir = __DIR__ . '/uploads'; if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
            $orig = $_FILES['doc']['name']; $tmp = $_FILES['doc']['tmp_name'];
            $stored = uniqid('doc_') . '_' . preg_replace('/[^a-zA-Z0-9_\.\-]/','_', $orig);
            move_uploaded_file($tmp, $dir . '/' . $stored);
            $stmt = db()->prepare('INSERT INTO documents (assessment_id, category_id, original_name, stored_name, mime, size) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$assessment_id, $cid, $orig, $stored, $_FILES['doc']['type'] ?? null, (int)($_FILES['doc']['size'] ?? 0)]);
            // แจ้งเตือนแอดมินทางอีเมล
            $cat = db()->prepare('SELECT name FROM categories WHERE id=?');
            $cat->execute([$cid]);
            $catName = $cat->fetchColumn();
            notify_admin_email(
                'มีการแนบเอกสารใหม่ในระบบ PDPA',
                "หมวด: $catName\nไฟล์: $orig\nเวลา: ".date('Y-m-d H:i:s')
            );
            header('Location: ?a=dashboard'); exit;
        case 'doc_review':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_GET['id'] ?? 0);
            $stmt = db()->prepare('SELECT d.*, c.name AS category_name FROM documents d JOIN categories c ON c.id = d.category_id WHERE d.id = ?');
            $stmt->execute([$id]);
            $doc = $stmt->fetch();
            view('admin/doc_review', ['doc'=>$doc]);
            break;
        case 'doc_review_save':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'PENDING';
            $notes = trim($_POST['notes'] ?? '');
            db()->prepare('UPDATE documents SET status=?, notes=? WHERE id=?')->execute([$status, $notes, $id]);
            
            // แจ้งเตือนผู้ใช้ทางอีเมลและระบบ
            $q = db()->prepare('SELECT a.contact_email, a.user_id, d.original_name, c.name AS category_name FROM documents d JOIN assessments a ON a.id=d.assessment_id JOIN categories c ON c.id=d.category_id WHERE d.id=?');
            $q->execute([$id]);
            $row = $q->fetch();
            if ($row) {
                // แปลงสถานะเป็นภาษาไทย
                $status_th = '';
                switch($status) {
                    case 'PASS': $status_th = 'อนุมัติ'; break;
                    case 'FAIL': $status_th = 'ไม่อนุมัติ(มีการแก้ไข)'; break;
                    case 'PENDING': $status_th = 'รอตรวจสอบ'; break;
                    default: $status_th = $status;
                }
                
                $msg = "เอกสาร: {$row['original_name']}\nหมวด: {$row['category_name']}\nสถานะ: $status_th\nหมายเหตุ: $notes";
                
                // ส่งอีเมล
                if ($row['contact_email']) {
                    notify_user_email($row['contact_email'], 'ผลการรีวิวเอกสารในระบบ PDPA', $msg);
                }
                
                // เพิ่มการแจ้งเตือนในระบบ
                if ($row['user_id']) {
                    $notif_msg = "เอกสาร '{$row['original_name']}' ในหมวด '{$row['category_name']}' ได้รับการตรวจสอบแล้ว สถานะ: $status_th";
                    if ($notes) $notif_msg .= " หมายเหตุ: $notes";
                    add_notification((int)$row['user_id'], $notif_msg);
                }
                
                // Log การกระทำ
                add_log($_SESSION['user']['id'] ?? null, 'document_review', "Reviewed document ID $id, status: $status");
            }
            
            echo "<script>alert('บันทึกผลการตรวจสอบเรียบร้อย และแจ้งเตือนผู้ใช้แล้ว'); window.location='?a=admin_documents';</script>";
            exit;

        // Admin
        case 'admin':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $qs = db()->query('SELECT * FROM questions ORDER BY id')->fetchAll();
            view('admin/questions_list', ['qs' => $qs, 'flash' => flash('admin')]);
            break;
            // --- Admin: Export/Import ---
            case 'admin_export_import':
                if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
                include 'admin_export_import.php';
                exit;

            // --- Executive Dashboard ---
            case 'executive_dashboard':
                if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
                include 'views/executive_dashboard.php';
                exit;

            // --- FAQ/คู่มือออนไลน์ ---
            case 'faq':
                include 'views/faq.php';
                exit;
        case 'admin_login':
            view('admin/login');
            break;
        case 'admin_login_submit':
            $pwd = $_POST['password'] ?? '';
            $hash = settings_get('admin_password_hash');
            if ($hash) {
                if (password_verify($pwd, $hash)) { $_SESSION['is_admin'] = true; header('Location: ?a=admin'); exit; }
            } else {
                $adminPass = getenv('ADMIN_PASS') ?: 'admin1234';
                if ($pwd === $adminPass) { $_SESSION['is_admin'] = true; header('Location: ?a=admin'); exit; }
            }
            view('admin/login', ['error' => 'รหัสผ่านไม่ถูกต้อง']);
            break;
        case 'admin_users':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $users = db()->query('SELECT id, username, email, role, created_at FROM users ORDER BY id DESC')->fetchAll();
            view('admin/users', ['users' => $users, 'flash' => flash('users')]);
            break;
        case 'admin_user_role':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_POST['id'] ?? 0);
            $role = $_POST['role'] ?? 'user';
            if (!in_array($role, ['user','admin'], true)) { $role = 'user'; }
            db()->prepare('UPDATE users SET role = ? WHERE id = ?')->execute([$role, $id]);
            flash('users','อัพเดทสิทธิ์ผู้ใช้แล้ว');
            header('Location: ?a=admin_users');
            exit;
        case 'admin_logout':
            $_SESSION['is_admin'] = false; header('Location: ?'); exit;
        case 'admin_new_q':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            view('admin/question_form');
            break;
        case 'admin_edit_q':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_GET['id'] ?? 0);
            $stmt = db()->prepare('SELECT * FROM questions WHERE id = ?');
            $stmt->execute([$id]);
            $q = $stmt->fetch();
            view('admin/question_form', ['q' => $q]);
            break;
        case 'admin_save_q':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            if (!form_token_check($_POST['form_token'] ?? '')) { flash('admin','การส่งฟอร์มไม่ถูกต้อง'); header('Location: ?a=admin'); exit; }
            $id = (int)($_POST['id'] ?? 0);
            $code = trim($_POST['code'] ?? '');
            $text = trim($_POST['text'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $weight = (int)($_POST['weight'] ?? 1);
            if ($id > 0) {
                $stmt = db()->prepare('UPDATE questions SET code=?, text=?, category=?, weight=? WHERE id=?');
                $stmt->execute([$code, $text, $category, $weight, $id]);
            } else {
                $stmt = db()->prepare('INSERT INTO questions (code, text, category, weight) VALUES (?, ?, ?, ?)');
                $stmt->execute([$code, $text, $category, $weight]);
            }
            flash('admin','บันทึกสำเร็จ');
            header('Location: ?a=admin');
            exit;
        case 'admin_delete_q':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_GET['id'] ?? 0);
            if ($id > 0) { db()->prepare('DELETE FROM questions WHERE id = ?')->execute([$id]); }
            flash('admin','ลบสำเร็จ');
            header('Location: ?a=admin');
            exit;
        case 'admin_export_csv':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $pdo = db();
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="pdpa_assessments.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id','organization_name','contact_email','started_at','completed_at','score','risk_level']);
            foreach ($pdo->query('SELECT id, organization_name, contact_email, started_at, completed_at, score, risk_level FROM assessments ORDER BY id DESC') as $row) {
                fputcsv($out, [$row['id'],$row['organization_name'],$row['contact_email'],$row['started_at'],$row['completed_at'],$row['score'],$row['risk_level']]);
            }
            fclose($out);
            exit;
        case 'admin_settings':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            view('admin/settings', ['flash' => flash('settings')]);
            break;
        case 'admin_settings_save':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            if (!form_token_check($_POST['form_token'] ?? '')) { flash('settings','การส่งฟอร์มไม่ถูกต้อง'); header('Location: ?a=admin_settings'); exit; }
            $pwd = trim($_POST['new_password'] ?? '');
            if ($pwd === '') { flash('settings','กรุณากรอกรหัสผ่านใหม่'); header('Location: ?a=admin_settings'); exit; }
            $hash = password_hash($pwd, PASSWORD_BCRYPT);
            settings_set('admin_password_hash', $hash);
            flash('settings','บันทึกรหัสผ่านใหม่แล้ว');
            header('Location: ?a=admin_settings');
            exit;

        case 'admin_categories':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $cats = db()->query('SELECT * FROM categories ORDER BY id')->fetchAll();
            view('admin/categories', ['cats'=>$cats, 'flash' => flash('cats')]);
            break;
        case 'admin_categories_save':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_POST['id'] ?? 0);
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $weight = (int)($_POST['weight'] ?? 1);
            if ($id>0) {
                db()->prepare('UPDATE categories SET code=?, name=?, description=?, weight=? WHERE id=?')->execute([$code,$name,$desc?:null,$weight,$id]);
                flash('cats','อัพเดทหมวดสำเร็จ');
            } else {
                db()->prepare('INSERT INTO categories (code,name,description,weight) VALUES (?,?,?,?)')->execute([$code,$name,$desc?:null,$weight]);
                flash('cats','เพิ่มหมวดสำเร็จ');
            }
            header('Location: ?a=admin_categories'); exit;
        case 'admin_categories_delete':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_GET['id'] ?? 0);
            if ($id>0) db()->prepare('DELETE FROM categories WHERE id=?')->execute([$id]);
            flash('cats','ลบหมวดแล้ว'); header('Location: ?a=admin_categories'); exit;

        case 'admin_import':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            view('admin/import');
            break;
        case 'admin_import_submit':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            if (!isset($_FILES['csv']) || $_FILES['csv']['error']!==UPLOAD_ERR_OK) { echo 'อัปโหลด CSV ไม่สำเร็จ'; exit; }
            $tmp = $_FILES['csv']['tmp_name'];
            $stats = import_categories_questions_from_csv($tmp);
            view('admin/import_result', ['stats'=>$stats]);
            break;
        case 'admin_import_xlsx':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            view('admin/import_xlsx');
            break;
        case 'admin_import_xlsx_submit':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            if (!isset($_FILES['xlsx']) || $_FILES['xlsx']['error']!==UPLOAD_ERR_OK) { echo 'อัปโหลด XLSX ไม่สำเร็จ'; exit; }
            // Ensure Composer autoload is loaded for PhpSpreadsheet
            if (file_exists(__DIR__.'/vendor/autoload.php')) { require_once __DIR__.'/vendor/autoload.php'; }
            $tmp = $_FILES['xlsx']['tmp_name'];
            // Parse XLSX: expected columns like CSV variant (first sheet)
            $rows = [];
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmp);
            $sheet = $spreadsheet->getActiveSheet();
            $header = null;
            foreach ($sheet->toArray(null, true, true, true) as $r) {
                if ($header === null) { $header = array_map(fn($v)=>strtolower(trim((string)$v)), array_values($r)); continue; }
                $row = array_values($r); $assoc = [];
                foreach ($header as $i=>$key) { $assoc[$key] = $row[$i] ?? ''; }
                $rows[] = $assoc;
            }
            // write temp CSV-like to reuse importer
            $tmpCsv = tempnam(sys_get_temp_dir(), 'xlsx_').'.csv';
            $f = fopen($tmpCsv,'w');
            fputcsv($f, ['category_code','category_name','category_weight','category_description','question_code','question_text','question_weight']);
            foreach ($rows as $a) {
                fputcsv($f, [
                    $a['category_code'] ?? '',
                    $a['category_name'] ?? '',
                    $a['category_weight'] ?? '',
                    $a['category_description'] ?? '',
                    $a['question_code'] ?? '',
                    $a['question_text'] ?? '',
                    $a['question_weight'] ?? ''
                ]);
            }
            fclose($f);
            $stats = import_categories_questions_from_csv($tmpCsv);
            @unlink($tmpCsv);
            view('admin/import_result', ['stats'=>$stats]);
            break;

        case 'admin_documents':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $docs = db()->query('SELECT d.*, c.name AS category_name, a.organization_name, a.contact_email FROM documents d JOIN categories c ON c.id=d.category_id JOIN assessments a ON a.id=d.assessment_id ORDER BY d.uploaded_at DESC, d.id DESC')->fetchAll();
            // เพิ่มข้อมูล reviewer/lifecycle
            foreach($docs as &$doc) {
                $doc['reviewers'] = $doc['reviewers'] ? json_decode($doc['reviewers'],true) : [];
            }
            view('admin/documents', ['docs'=>$docs]);
            break;

        // --- Edit reviewers for document ---
        case 'admin_documents_edit_reviewers':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            include 'views/admin_documents_edit_reviewers.php';
            exit;

            // --- Document review/approval workflow ---
            case 'doc_review':
                if (isset($_GET['id'])) {
                    $user = $_SESSION['user'] ?? null;
                    if (!$user || !in_array($user['role'], ['reviewer','admin'])) {
                        echo 'Permission denied'; exit;
                    }
                    $doc_id = (int)$_GET['id'];
                    $pdo = db();
                    $doc = $pdo->query("SELECT * FROM documents WHERE id=$doc_id")->fetch();
                    if (!$doc) { echo 'Document not found'; exit; }
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $status = $_POST['status'] ?? 'PENDING';
                        update_document_status($doc_id, $status, $user['id']);
                        add_log($user['id'], 'doc_review', "Document #$doc_id status: $status");
                        // แจ้งเตือนเจ้าของเอกสาร
                        add_notification($doc['user_id'], "เอกสารของคุณถูกรีวิว: $status");
                        header('Location: ?a=admin_documents'); exit;
                    }
                    // ...แสดงฟอร์มรีวิว...
                    echo '<form method="post">';
                    echo '<h3>Review Document: '.htmlspecialchars($doc['original_name']).'</h3>';
                    echo '<select name="status">';
                    foreach(['PASS','FAIL','PENDING'] as $s) {
                        $sel = $doc['status']===$s?'selected':'';
                        echo "<option value='$s' $sel>$s</option>";
                    }
                    echo '</select>';
                    echo '<button type="submit">บันทึกผล</button>';
                    echo '</form>';
                    exit;
                }
                break;

        case 'export_summary_excel':
            require_login('?a=summary');
            $assessment_id = $_SESSION['assessment_id'] ?? null;
            if (!$assessment_id) { header('Location: ?'); exit; }
            if (file_exists(__DIR__.'/vendor/autoload.php')) { require_once __DIR__.'/vendor/autoload.php'; }
            $cats = get_category_scores((int)$assessment_id);
            foreach ($cats as &$c) { $c['docs'] = get_documents_for((int)$assessment_id, (int)$c['category_id']); }
            unset($c);
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'หมวด');
            $sheet->setCellValue('B1', 'คะแนนเฉลี่ย');
            $sheet->setCellValue('C1', 'ระดับสี');
            $sheet->setCellValue('D1', 'เอกสารแนบ');
            $row = 2;
            foreach ($cats as $c) {
                $docs = [];
                if (!empty($c['docs'])) {
                    foreach ($c['docs'] as $d) {
                        $docs[] = $d['original_name'].' ('.$d['status'].')';
                    }
                }
                $sheet->setCellValue('A'.$row, $c['category_name']);
                $sheet->setCellValue('B'.$row, $c['avg'] ?? '-');
                $sheet->setCellValue('C'.$row, $c['color']);
                $sheet->setCellValue('D'.$row, implode(", ", $docs));
                $row++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="summary_export.xlsx"');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        case 'export_excel':
            $assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['assessment_id'] ?? null);
            if (!$assessment_id) { echo 'ไม่พบข้อมูล'; exit; }
            $pdo = db();
            $assessment = $pdo->prepare('SELECT * FROM assessments WHERE id = ?');
            $assessment->execute([$assessment_id]);
            $a = $assessment->fetch();
            if (!$a) { echo 'ไม่พบข้อมูล'; exit; }
            $answers = $pdo->prepare('SELECT q.code, q.text, q.category, q.weight, a.answer_value, a.notes FROM questions q LEFT JOIN answers a ON a.question_id = q.id AND a.assessment_id = ? ORDER BY q.id');
            $answers->execute([$assessment_id]);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="assessment_'.($a['organization_name']??'').'_'.date('Ymd_His').'.xls"');
            echo "<table border='1'>";
            echo "<tr><th>ลำดับ</th><th>รหัส</th><th>รายการ (Objective)</th><th>หมวด</th><th>น้ำหนัก</th><th>คะแนน</th><th>หมายเหตุ</th></tr>";
            $i=1;
            foreach ($answers as $row) {
                echo "<tr>";
                echo "<td>".($i++)."</td>";
                echo "<td>".htmlspecialchars($row['code'])."</td>";
                echo "<td>".htmlspecialchars($row['text'])."</td>";
                echo "<td>".htmlspecialchars($row['category']??'-')."</td>";
                echo "<td>".(int)($row['weight']??1)."</td>";
                echo "<td>".(int)($row['answer_value']??0)."</td>";
                echo "<td>".htmlspecialchars($row['notes']??'')."</td>";
                echo "</tr>";
            }
            echo "</table>";
            exit;
        case 'export_pdf':
            $assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['assessment_id'] ?? null);
            if (!$assessment_id) { echo 'ไม่พบข้อมูล'; exit; }
            $pdo = db();
            $assessment = $pdo->prepare('SELECT * FROM assessments WHERE id = ?');
            $assessment->execute([$assessment_id]);
            $a = $assessment->fetch();
            if (!$a) { echo 'ไม่พบข้อมูล'; exit; }
            $answers = $pdo->prepare('SELECT q.code, q.text, q.category, q.weight, a.answer_value, a.notes FROM questions q LEFT JOIN answers a ON a.question_id = q.id AND a.assessment_id = ? ORDER BY q.id');
            $answers->execute([$assessment_id]);
            ob_start();
            echo "<h2>ผลการประเมิน PDPA Self Assessment for CII</h2>";
            echo "<p>หน่วยงาน: ".htmlspecialchars($a['organization_name'])."<br>ผู้ประเมิน: ".htmlspecialchars($a['assessor_name'])."<br>วันที่: ".htmlspecialchars($a['started_at'])."</p>";
            echo "<table border='1' cellpadding='4' cellspacing='0'>";
            echo "<tr><th>ลำดับ</th><th>รหัส</th><th>รายการ</th><th>หมวด</th><th>น้ำหนัก</th><th>คะแนน</th><th>หมายเหตุ</th></tr>";
            $i=1;
            foreach ($answers as $row) {
                echo "<tr>";
                echo "<td>".($i++)."</td>";
                echo "<td>".htmlspecialchars($row['code'])."</td>";
                echo "<td>".htmlspecialchars($row['text'])."</td>";
                echo "<td>".htmlspecialchars($row['category']??'-')."</td>";
                echo "<td>".(int)($row['weight']??1)."</td>";
                echo "<td>".(int)($row['answer_value']??0)."</td>";
                echo "<td>".htmlspecialchars($row['notes']??'')."</td>";
                echo "</tr>";
            }
            echo "</table>";
            $html = ob_get_clean();
            
            // Check if mPDF is available
            if (file_exists(__DIR__.'/vendor/autoload.php')) {
                require_once __DIR__.'/vendor/autoload.php';
                if (class_exists('\Mpdf\Mpdf')) {
                    try {
                        $mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__.'/tmp']);
                        $mpdf->WriteHTML($html);
                        $mpdf->Output('assessment_'.($a['organization_name']??'').'_'.date('Ymd_His').'.pdf', 'D');
                        exit;
                    } catch (Exception $e) {
                        // Fall through to alternative method
                    }
                }
            }
            
            // Fallback: Generate HTML for print/save as PDF
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: inline; filename="assessment_'.($a['organization_name']??'').'_'.date('Ymd_His').'.html"');
            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>PDPA Assessment Report</title>';
            echo '<style>body{font-family:Arial,sans-serif;margin:20px;}table{border-collapse:collapse;width:100%;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background-color:#f2f2f2;}.print-instruction{background:#ffffcc;padding:10px;margin:10px 0;border:1px solid #ffeb3b;}@media print{.print-instruction{display:none;}}</style>';
            echo '</head><body>';
            echo '<div class="print-instruction"><strong>วิธีการบันทึกเป็น PDF:</strong> กด Ctrl+P แล้วเลือก "Save as PDF" หรือ "Microsoft Print to PDF"</div>';
            echo $html;
            echo '</body></html>';
            exit;

        // --- Audit Log ---
        case 'audit_log':
            require_admin();
            $pdo = db();
            $logs = $pdo->query('SELECT l.*, u.username FROM log l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC LIMIT 200')->fetchAll();
            view('audit_log', ['logs' => $logs]);
            break;
        case 'admin_audit_log':
            // แสดง audit log (admin เท่านั้น)
            if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('forbidden'); }
            $logs = db()->query('SELECT * FROM log ORDER BY created_at DESC LIMIT 200')->fetchAll();
            view('admin/audit_log', ['logs'=>$logs]);
            break;
        case 'admin_dashboard':
            // Dashboard ผู้บริหาร (KPI)
            if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('forbidden'); }
            // ตัวอย่าง KPI (จริงควร query/aggregate)
            $kpi = db()->query('SELECT category, COUNT(*) as count, AVG(score) as avg, MAX(risk_level) as level FROM assessments GROUP BY category')->fetchAll();
            view('admin/dashboard', ['kpi'=>$kpi]);
            break;
        case 'admin_user_import_export':
            if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('forbidden'); }
            view('admin/user_import_export');
            break;
        case 'import_users':
            if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('forbidden'); }
            if (!isset($_FILES['csv']) || $_FILES['csv']['error']!==UPLOAD_ERR_OK) { flash('auth','อัปโหลดไฟล์ CSV ไม่สำเร็จ'); header('Location: ?a=admin_user_import_export'); exit; }
            $pdo = db();
            $f = fopen($_FILES['csv']['tmp_name'],'r');
            $header = fgetcsv($f);
            $map = array_flip($header);
            $count = 0;
            while (($row = fgetcsv($f)) !== false) {
                $username = $row[$map['username']] ?? '';
                $email = $row[$map['email']] ?? '';
                $role = $row[$map['role']] ?? 'evaluator';
                if ($username && $email) {
                    $exists = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username=? OR email=?');
                    $exists->execute([$username, $email]);
                    if ($exists->fetchColumn() == 0) {
                        $pass = bin2hex(random_bytes(4));
                        $stmt = $pdo->prepare('INSERT INTO users (username,email,password_hash,role) VALUES (?,?,?,?)');
                        $stmt->execute([$username, $email, password_hash($pass, PASSWORD_BCRYPT), $role]);
                        $count++;
                    }
                }
            }
            fclose($f);
            flash('auth',"นำเข้า $count ผู้ใช้ใหม่เรียบร้อย");
            header('Location: ?a=admin_user_import_export');
            exit;
        case 'export_users':
            if (($_SESSION['role'] ?? '') !== 'admin') { http_response_code(403); exit('forbidden'); }
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="users.csv"');
            $pdo = db();
            $rows = $pdo->query('SELECT id,username,email,role,created_at FROM users')->fetchAll();
            $out = fopen('php://output','w');
            fputcsv($out, ['id','username','email','role','created_at']);
            foreach ($rows as $r) fputcsv($out, $r);
            fclose($out);
            exit;
        case 'reset_password':
            view('auth/reset_password');
            break;
        case 'reset_password_submit':
            $email = trim($_POST['email'] ?? '');
            $pdo = db();
            $st = $pdo->prepare('SELECT id FROM users WHERE email=?');
            $st->execute([$email]);
            $u = $st->fetch();
            if (!$u) { flash('auth','ไม่พบอีเมลนี้ในระบบ'); header('Location: ?a=reset_password'); exit; }
            $token = bin2hex(random_bytes(16));
            $pdo->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([$token, $u['id']]); // ใช้ token ชั่วคราวแทน password_hash
            // ส่งอีเมล (demo: แสดงลิงก์)
            $resetLink = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?a=reset_password_link&token=' . $token;
            flash('auth','ส่งลิงก์รีเซ็ต: <a href="'.$resetLink.'">'.$resetLink.'</a>');
            header('Location: ?a=login');
            exit;
        case 'reset_password_link':
            $token = $_GET['token'] ?? '';
            $pdo = db();
            $st = $pdo->prepare('SELECT id FROM users WHERE password_hash=?');
            $st->execute([$token]);
            $u = $st->fetch();
            if (!$u) { exit('ลิงก์ไม่ถูกต้องหรือหมดอายุ'); }
            // แสดงฟอร์มตั้งรหัสผ่านใหม่
            echo '<form method="post" action="?a=reset_password_new&token='.htmlspecialchars($token).'">'
                .'<label>รหัสผ่านใหม่: <input type="password" name="password" required></label>'
                .'<button type="submit">ตั้งรหัสผ่านใหม่</button></form>';
            exit;
        case 'reset_password_new':
            $token = $_GET['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $pdo = db();
            $st = $pdo->prepare('SELECT id FROM users WHERE password_hash=?');
            $st->execute([$token]);
            $u = $st->fetch();
            if (!$u) { exit('ลิงก์ไม่ถูกต้องหรือหมดอายุ'); }
            $pdo->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([password_hash($password, PASSWORD_BCRYPT), $u['id']]);
            flash('auth','ตั้งรหัสผ่านใหม่เรียบร้อย');
            header('Location: ?a=login');
            exit;
        // --- User assessment history ---
        case 'history':
            require_login('?a=history');
            $pdo = db();
            $user = $_SESSION['user'] ?? null;
            $isAdmin = ($_SESSION['role'] ?? '') === 'admin';
            if ($isAdmin) {
                $rows = $pdo->query('SELECT a.*, u.username FROM assessments a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.started_at DESC, a.id DESC')->fetchAll();
            } else {
                $uid = $user['id'] ?? 0;
                $stmt = $pdo->prepare('SELECT * FROM assessments WHERE user_id = ? ORDER BY started_at DESC, id DESC');
                $stmt->execute([$uid]);
                $rows = $stmt->fetchAll();
            }
            view('history', ['rows' => $rows, 'isAdmin' => $isAdmin]);
            break;

        // --- User notifications ---
        case 'notifications':
            require_login('?a=notifications');
            $pdo = db();
            $user = $_SESSION['user'] ?? null;
            $uid = $user['id'] ?? 0;
            $rows = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC, id DESC');
            $rows->execute([$uid]);
            $notifications = $rows->fetchAll();
            view('notifications', ['notifications' => $notifications]);
            break;
            
        // --- Compare assessment ---
        case 'compare_assessment':
            require_login('?a=compare_assessment');
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) { header('Location: ?a=history'); exit; }
            
            $pdo = db();
            $user = $_SESSION['user'] ?? null;
            $isAdmin = ($_SESSION['role'] ?? '') === 'admin';
            
            // Check if user owns this assessment or is admin
            $stmt = $pdo->prepare('SELECT * FROM assessments WHERE id = ?');
            $stmt->execute([$id]);
            $assessment = $stmt->fetch();
            
            if (!$assessment || (!$isAdmin && $assessment['user_id'] != ($user['id'] ?? 0))) {
                echo 'ไม่พบข้อมูลหรือไม่มีสิทธิ์เข้าถึง'; exit;
            }
            
            // Get current assessment data
            $answers = $pdo->prepare('SELECT q.code, q.text, q.category, q.weight, a.answer_value, a.notes FROM questions q LEFT JOIN answers a ON a.question_id = q.id AND a.assessment_id = ? ORDER BY q.id');
            $answers->execute([$id]);
            $current_answers = $answers->fetchAll();
            
            // Get user's other assessments for comparison
            $other_stmt = $pdo->prepare('SELECT id, organization_name, started_at, score, risk_level FROM assessments WHERE user_id = ? AND id != ? ORDER BY started_at DESC');
            $other_stmt->execute([$assessment['user_id'], $id]);
            $other_assessments = $other_stmt->fetchAll();
            
            view('compare_assessment', [
                'assessment' => $assessment,
                'answers' => $current_answers,
                'other_assessments' => $other_assessments,
                'categories' => get_category_scores($id)
            ]);
            break;
            
        // Home
        case 'home':
        default:
            view('home', []);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo '<pre style="padding:16px">เกิดข้อผิดพลาด: ' . htmlspecialchars($e->getMessage()) . "\n";
    echo htmlspecialchars($e->getFile() . ':' . $e->getLine()) . "\n\n";
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
}
