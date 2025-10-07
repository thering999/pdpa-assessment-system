<?php

session_start();

// CSRF token helpers
function form_token_issue() {
    if (empty($_SESSION['form_token'])) {
        $_SESSION['form_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['form_token'];
}
function form_token_check($token) {
    return isset($_SESSION['form_token']) && hash_equals($_SESSION['form_token'], $token);
}

// Flash message helper: set or get a flash message by key
function flash($key, $msg = null) {
    if ($msg !== null) {
        $_SESSION['flash'][$key] = $msg;
        return;
    }
    if (!empty($_SESSION['flash'][$key])) {
        $val = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $val;
    }
    return null;
}

// Render a view file with variables
// Render a view file with variables
function view($file, $vars = []) {
    $path = __DIR__ . '/views/' . str_replace(['..', '\\', '//'], '', $file) . '.php';
    if (!file_exists($path)) {
        http_response_code(404);
        echo "View not found: " . htmlspecialchars($file);
        exit;
    }
    extract($vars);
    include $path;
}
// Check if current user is allowed to access a page (by key)
function user_can_access($page_key) {
    $user = $_SESSION['user'] ?? null;
    if (!$user) return false;
    if (!empty($user['role']) && $user['role'] === 'admin') return true; // admin always allowed
    if (empty($user['allowed_pages'])) return true; // if not set, allow all
    $allowed = json_decode($user['allowed_pages'], true);
    if (!is_array($allowed)) return true;
    return in_array($page_key, $allowed, true);
}


// Require user to be logged in, otherwise redirect to login page
function require_login($redirect = '?a=login') {
    if (empty($_SESSION['user'])) {
        $_SESSION['after_login'] = $_SERVER['REQUEST_URI'] ?? null;
        header('Location: ' . $redirect);
        exit;
    }
}

require_once __DIR__.'/db.php';

// Ensure database tables exist (including new role/workflow tables)
try { ensure_tables(); } catch (Throwable $e) { /* ignore init errors here */ }

$action = $_GET['a'] ?? 'home';

try {
    switch ($action) {
        // --- User Registration ---
        case 'register':
            view('auth/register', ['flash' => flash('auth')]);
            break;
        case 'register_submit':
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if (!$username || !$email || !$password) {
                flash('auth', 'กรุณากรอกข้อมูลให้ครบถ้วน');
                header('Location: ?a=register');
                exit;
            }
            $pdo = db();
            $exists = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username=? OR email=?');
            $exists->execute([$username, $email]);
            if ($exists->fetchColumn() > 0) {
                flash('auth', 'ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้แล้ว');
                header('Location: ?a=register');
                exit;
            }
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
            $stmt->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT), 'evaluator']);
            flash('auth', 'สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ');
            header('Location: ?a=login');
            exit;
        // --- User Logout ---
        case 'logout':
            session_unset();
            session_destroy();
            header('Location: ?a=home');
            exit;
        // --- User Login ---
        case 'login':
            view('auth/login', ['flash' => flash('auth')]);
            break;
        case 'login_submit':
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $pdo = db();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'allowed_pages' => $user['allowed_pages'] ?? null
                ];
                if (($user['role'] ?? '') === 'reviewer') {
                    $redirect = $_SESSION['after_login'] ?? '?a=reviewer_documents';
                } else {
                    $redirect = $_SESSION['after_login'] ?? '?a=dashboard';
                }
                unset($_SESSION['after_login']);
                header('Location: ' . $redirect);
                exit;
            } else {
                flash('auth', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
                header('Location: ?a=login');
                exit;
            }
        // --- Admin: User Management (Edit, Delete, Change Password) ---
        case 'admin_user_delete':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                // Prevent self-delete and deleting other admins (optional: add more checks)
                if ($id == ($_SESSION['user']['id'] ?? 0)) {
                    flash('users','ไม่สามารถลบผู้ใช้ตนเองได้');
                } else {
                    db()->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
                    flash('users','ลบผู้ใช้สำเร็จ');
                }
            }
            header('Location: ?a=admin_users');
            exit;

        case 'admin_user_edit':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_GET['id'] ?? 0);
            $stmt = db()->prepare('SELECT id, username, email, role, allowed_pages FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            if (!$user) { flash('users','ไม่พบผู้ใช้'); header('Location: ?a=admin_users'); exit; }
            view('admin/user_edit', ['user' => $user]);
            break;

        case 'admin_user_update':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_POST['id'] ?? 0);
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $allowed_pages = isset($_POST['allowed_pages']) ? json_encode($_POST['allowed_pages']) : null;
            if ($id > 0 && $username && $email) {
                db()->prepare('UPDATE users SET username=?, email=?, allowed_pages=? WHERE id=?')->execute([$username, $email, $allowed_pages, $id]);
                flash('users','อัพเดทข้อมูลผู้ใช้แล้ว');
            }
            header('Location: ?a=admin_users');
            exit;

        case 'admin_user_password':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_GET['id'] ?? 0);
            $stmt = db()->prepare('SELECT id, username, email FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            if (!$user) { flash('users','ไม่พบผู้ใช้'); header('Location: ?a=admin_users'); exit; }
            view('admin/user_password', ['user' => $user]);
            break;

        case 'admin_user_password_update':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_POST['id'] ?? 0);
            $password = $_POST['password'] ?? '';
            if ($id > 0 && $password) {
                db()->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([password_hash($password, PASSWORD_BCRYPT), $id]);
                flash('users','เปลี่ยนรหัสผ่านผู้ใช้แล้ว');
            }
            header('Location: ?a=admin_users');
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
            if (!user_can_access('dashboard')) { echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; exit; }
            // Check user role
            $user_role = get_user_role($_SESSION['user']['id'] ?? 0);
            // For reviewers and admins, allow dashboard access without assessment
            if (in_array($user_role, ['reviewer', 'admin'])) {
                $cats = [];
                view('dashboard', ['cats' => $cats, 'user_role' => $user_role]);
                break;
            }
            // For evaluators, require assessment
            $assessment_id = $_SESSION['assessment_id'] ?? null;
            if (!$assessment_id) {
                $pdo = db();
                $user = $_SESSION['user'] ?? null;
                if ($user) {
                        $stmt = $pdo->prepare('SELECT id FROM assessments WHERE user_id = ? AND deleted_at IS NULL ORDER BY started_at DESC LIMIT 1');
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
                        if (!user_can_access('documents')) { echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; exit; }
                        $assessment_id = $_SESSION['assessment_id'] ?? null; $cid = (int)($_GET['cid'] ?? 0);
                        if (!$assessment_id || $cid<=0) { header('Location: ?'); exit; }
                        $pdo = db();
                        // ดึงชื่อหมวด
                                    $catStmt = $pdo->prepare('SELECT name FROM categories WHERE id=?');
                                    $catStmt->execute([$cid]);
                                    $catName = $catStmt->fetchColumn();
                                    // ดึงข้อคำถามในหมวดนี้
                                    $qs = $pdo->prepare('SELECT * FROM questions WHERE category_id=? OR category=(SELECT name FROM categories WHERE id=?) ORDER BY id');
                                    $qs->execute([$cid, $cid]);
                                    $questions = $qs->fetchAll();
                                    // ดึงไฟล์ที่แนบแล้วใน assessment/category/question
                                    $docsStmt = $pdo->prepare('SELECT * FROM documents WHERE assessment_id=? AND category_id=?');
                                    $docsStmt->execute([$assessment_id, $cid]);
                                    $docs = $docsStmt->fetchAll();
                                    $docsByQ = [];
                                    foreach ($docs as $d) {
                                        $qid = (int)($d['question_id'] ?? 0);
                                        if (!isset($docsByQ[$qid])) $docsByQ[$qid] = [];
                                        $docsByQ[$qid][] = $d;
                                    }
                                    // ส่วนหัวและพื้นหลังแบบ dark theme พร้อม effects
                                    echo '<style>
                                    * { box-sizing: border-box; }
                                    body { line-height: 1.6; margin: 0; padding: 0; }
                                    .upload-container {
                                        background: linear-gradient(135deg, #181c2a, #2d3748);
                                        min-height: 100vh;
                                        padding: 0;
                                        margin: 0;
                                    }
                                    .upload-header {
                                        padding: 32px 0 0 0;
                                        max-width: 900px;
                                        margin: auto;
                                        text-align: center;
                                    }
                                    .upload-title {
                                        color: #ffffff;
                                        font-size: 2.2rem;
                                        font-weight: bold;
                                        margin-bottom: 8px;
                                        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
                                    }
                                    .upload-nav {
                                        display: flex;
                                        gap: 12px;
                                        margin-bottom: 24px;
                                        justify-content: center;
                                        flex-wrap: wrap;
                                    }
                                    .upload-nav-btn {
                                        background: linear-gradient(145deg, #232846, #2d3a5f);
                                        color: #fff;
                                        padding: 12px 20px;
                                        border: none;
                                        border-radius: 8px;
                                        text-decoration: none;
                                        font-weight: 600;
                                        transition: all 0.3s ease;
                                        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                                    }
                                    .upload-nav-btn:hover {
                                        background: linear-gradient(145deg, #2d3a5f, #3a4a6b);
                                        transform: translateY(-2px);
                                        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                    }
                                    .upload-card {
                                        max-width: 900px;
                                        margin: 32px auto 0 auto;
                                        background: #ffffff;
                                        border-radius: 16px;
                                        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
                                        overflow: hidden;
                                        animation: slideIn 0.5s ease-out;
                                    }
                                    @keyframes slideIn {
                                        from { opacity: 0; transform: translateY(20px); }
                                        to { opacity: 1; transform: translateY(0); }
                                    }
                                    .upload-card-header {
                                        background: linear-gradient(135deg, #007bff, #0056b3);
                                        color: white;
                                        padding: 24px 32px;
                                        margin: 0;
                                        font-size: 1.5rem;
                                        font-weight: 600;
                                    }
                                    .question-item {
                                        margin-bottom: 22px;
                                        padding: 20px 18px 14px 18px;
                                        background: linear-gradient(135deg, #f8faff, #ffffff);
                                        border: 1.5px solid #e3e8f0;
                                        box-shadow: 0 4px 16px rgba(0,123,255,0.08);
                                        border-radius: 12px;
                                        transition: all 0.3s ease;
                                    }
                                    .question-item:hover {
                                        transform: translateY(-2px);
                                        box-shadow: 0 8px 24px rgba(0,123,255,0.15);
                                    }
                                    .upload-form {
                                        margin-top: 12px;
                                        display: flex;
                                        gap: 12px;
                                        align-items: center;
                                        flex-wrap: wrap;
                                    }
                                    .file-input {
                                        padding: 8px 12px;
                                        border: 2px solid #dee2e6;
                                        border-radius: 8px;
                                        background: #ffffff;
                                        transition: all 0.3s ease;
                                    }
                                    .file-input:focus {
                                        border-color: #007bff;
                                        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
                                    }
                                    .upload-btn {
                                        background: linear-gradient(135deg, #007bff, #0056b3);
                                        color: white;
                                        padding: 10px 20px;
                                        border: none;
                                        border-radius: 8px;
                                        font-weight: 600;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                        box-shadow: 0 2px 8px rgba(0,123,255,0.2);
                                    }
                                    .upload-btn:hover {
                                        background: linear-gradient(135deg, #0056b3, #004494);
                                        transform: translateY(-1px);
                                        box-shadow: 0 4px 12px rgba(0,123,255,0.3);
                                    }
                                    .file-list {
                                        margin-top: 12px;
                                        font-size: 14px;
                                        background: linear-gradient(135deg, #e3f2fd, #f8faff);
                                        padding: 12px 16px;
                                        border-radius: 8px;
                                        border-left: 4px solid #007bff;
                                    }
                                    </style>';
                                    echo '<div class="upload-container">';
                                    echo '<header class="upload-header">';
                                    echo '<h1 class="upload-title">PDPA Assessment</h1>';
                                    echo '<nav class="upload-nav">';
                                    echo '<a class="upload-nav-btn" href="?">หน้าแรก</a>';
                                    echo '<a class="upload-nav-btn" href="?a=history">ประวัติ</a>';
                                    echo '<a class="upload-nav-btn" href="?a=dashboard">แดชบอร์ด</a>';
                                    echo '<a class="upload-nav-btn" href="?a=reviewer_documents">งานรีวิว</a>';
                                    echo '<a class="upload-nav-btn" href="?a=notifications">การแจ้งเตือน</a>';
                                    echo '</nav>';
                                    echo '</header>';
                                    echo '<section class="upload-card">';
                                    echo '<div style="background: linear-gradient(135deg, #007bff, #0056b3); padding: 24px 32px; margin: 0; display: flex; justify-content: space-between; align-items: center;">';
                                    echo '<h2 style="color: white; font-size: 1.5rem; font-weight: 600; margin: 0;">แนบเอกสารหลักฐาน: '.htmlspecialchars($catName).'</h2>';
                                    echo '<a href="?a=questions" style="background: rgba(255,255,255,0.2); color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background=\'rgba(255,255,255,0.3)\'" onmouseout="this.style.background=\'rgba(255,255,255,0.2)\'">← ย้อนกลับ</a>';
                                    echo '</div>';
                                    echo '<div style="padding: 32px;">';
                                                if (empty($questions)) {
                                                    echo '<div style="color:#c00;text-align:center;padding:24px;">ไม่พบข้อคำถามในหมวดนี้</div>';
                                                } else {
                                                    foreach ($questions as $q) {
                                                        $qid = (int)$q['id'];
                                                        echo '<div class="question-item">';
                                                        echo '<div style="font-size:1.08em;font-weight:600;margin-bottom:8px;color:#222">['.htmlspecialchars($q['code']).'] '.htmlspecialchars($q['text']).'</div>';
                                                        echo '<form method="post" enctype="multipart/form-data" action="?a=upload_doc_submit" class="upload-form">';
                                                        echo '<input type="hidden" name="cid" value="'.(int)$cid.'">';
                                                        echo '<input type="hidden" name="question_id" value="'.$qid.'">';
                                                        echo '<input type="file" name="doc" required class="file-input"> ';
                                                        echo '<button type="submit" class="upload-btn">แนบไฟล์</button>';
                                                        echo '</form>';
                                                        // แสดงไฟล์ที่แนบแล้ว
                                                        if (!empty($docsByQ[$qid])) {
                                                            echo '<div class="file-list">';
                                                            echo '<b>📎 ไฟล์ที่แนบแล้ว:</b><br>';
                                                            foreach ($docsByQ[$qid] as $d) {
                                                                echo '<a href="uploads/'.htmlspecialchars($d['stored_name']).'" target="_blank" style="color:#1976d2;text-decoration:none;margin-right:16px;display:inline-block;margin-top:4px;">📄 '.htmlspecialchars($d['original_name']).'</a>';
                                                            }
                                                            echo '</div>';
                                                        }
                                                        echo '</div>';
                                                    }
                                                }
                                                echo '</div></section>';
                                                echo '</div>';
                                    break;
        case 'upload_doc_submit':
            require_login();
            $assessment_id = $_SESSION['assessment_id'] ?? null; 
            $cid = (int)($_POST['cid'] ?? 0);
            $question_id = (int)($_POST['question_id'] ?? 0);
            $pdo = db();
            if (!$assessment_id || $cid<=0) { header('Location: ?'); exit; }
            if (!isset($_FILES['doc']) || $_FILES['doc']['error'] !== UPLOAD_ERR_OK) { 
                echo 'อัปโหลดไฟล์ไม่สำเร็จ'; exit; 
            }
            $dir = __DIR__ . '/uploads'; 
            if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
            $orig = $_FILES['doc']['name']; 
            $tmp = $_FILES['doc']['tmp_name'];
            $stored = uniqid('doc_') . '_' . preg_replace('/[^a-zA-Z0-9_\.\-]/','_', $orig);
            $assess = $pdo->prepare('SELECT * FROM assessments WHERE id = ? AND deleted_at IS NULL');
            if (!move_uploaded_file($tmp, $dir . '/' . $stored)) {
                echo 'ไม่สามารถบันทึกไฟล์ได้'; exit;
            }
            
            // Insert document with question_id
            $stmt = db()->prepare('INSERT INTO documents (assessment_id, category_id, question_id, original_name, stored_name, mime, size) VALUES (?,?,?,?,?,?,?)');
            $stmt->execute([
                $assessment_id, 
                $cid, 
                $question_id > 0 ? $question_id : null, 
                $orig, 
                $stored, 
                $_FILES['doc']['type'] ?? null, 
                (int)($_FILES['doc']['size'] ?? 0)
            ]);
            
            // แจ้งเตือนแอดมินทางอีเมล
            $cat = db()->prepare('SELECT name FROM categories WHERE id=?');
            $cat->execute([$cid]);
            $catName = $cat->fetchColumn();
            
            $question_info = '';
            if ($question_id > 0) {
                $q = db()->prepare('SELECT text FROM questions WHERE id=?');
                $q->execute([$question_id]);
                $question_text = $q->fetchColumn();
                if ($question_text) {
                    $question_info = "\nข้อคำถาม: " . substr($question_text, 0, 100) . (strlen($question_text) > 100 ? '...' : '');
                }
            }
            
            notify_admin_email(
                'มีการแนบเอกสารใหม่ในระบบ PDPA',
                "หมวด: $catName$question_info\nไฟล์: $orig\nเวลา: ".date('Y-m-d H:i:s')
            );
            
            header('Location: ?a=upload_doc&cid=' . $cid); exit;
            
        case 'download_doc':
            require_login();
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) { header('Location: ?'); exit; }
            
            $stmt = db()->prepare('SELECT * FROM documents WHERE id = ?');
            $stmt->execute([$id]);
            $doc = $stmt->fetch();
            
            if (!$doc) { echo 'ไม่พบเอกสาร'; exit; }
            
            $file_path = __DIR__ . '/uploads/' . $doc['stored_name'];
            if (!file_exists($file_path)) { echo 'ไม่พบไฟล์'; exit; }
            
            header('Content-Type: ' . ($doc['mime'] ?? 'application/octet-stream'));
            header('Content-Disposition: attachment; filename="' . $doc['original_name'] . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
            
        case 'delete_doc':
            require_login();
            $id = (int)($_GET['id'] ?? 0);
            $cid = (int)($_GET['cid'] ?? 0);
            if ($id <= 0) { header('Location: ?'); exit; }
            
            $stmt = db()->prepare('SELECT * FROM documents WHERE id = ?');
            $stmt->execute([$id]);
            $doc = $stmt->fetch();
            
            if ($doc) {
                // Delete file from filesystem
                $file_path = __DIR__ . '/uploads/' . $doc['stored_name'];
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
                
                // Delete from database
                $delete_stmt = db()->prepare('DELETE FROM documents WHERE id = ?');
                $delete_stmt->execute([$id]);
            }
            
            header('Location: ?a=upload_doc&cid=' . $cid); exit;
            
        case 'doc_review':
            // Allow admin and reviewer to open review page
            $me = $_SESSION['user'] ?? null;
            if (!$me || !in_array($me['role'] ?? '', ['admin','reviewer'], true)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_GET['id'] ?? 0);
            $stmt = db()->prepare('SELECT d.*, c.name AS category_name FROM documents d JOIN categories c ON c.id = d.category_id WHERE d.id = ?');
            $stmt->execute([$id]);
            $doc = $stmt->fetch();
            view('admin/doc_review', ['doc'=>$doc]);
            break;
        case 'doc_review_save':
            // Allow admin and reviewer to submit review
            $me = $_SESSION['user'] ?? null;
            if (!$me || !in_array($me['role'] ?? '', ['admin','reviewer'], true)) { header('Location: ?a=admin_login'); exit; }
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'PENDING';
            $notes = trim($_POST['notes'] ?? '');
            // Use workflow helper to advance/record steps
            update_document_status($id, $status, $me['id'] ?? null, $notes);
            
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
                
                // เพิ่มการแจ้งเตือนในระบบ (แนบ document_id + event_type)
                if ($row['user_id']) {
                    $notif_msg = "เอกสาร '{$row['original_name']}' ในหมวด '{$row['category_name']}' ได้รับการตรวจสอบแล้ว สถานะ: $status_th";
                    if ($notes) $notif_msg .= " หมายเหตุ: $notes";
                    add_notification((int)$row['user_id'], $notif_msg, $id, 'doc_reviewed');
                }
                
                // Log การกระทำ
                add_log($me['id'] ?? null, 'document_review', "Reviewed document ID $id, status: $status");
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
            $authed = false;
            if ($hash) {
                if (password_verify($pwd, $hash)) { $authed = true; }
            } else {
                $adminPass = getenv('ADMIN_PASS') ?: 'admin1234';
                if ($pwd === $adminPass) { $authed = true; }
            }
            if ($authed) {
                $_SESSION['is_admin'] = true;
                // Also set a user session so header/menu and pages recognize admin role
                try {
                    $pdo = db();
                    $adminRow = $pdo->query("SELECT id, username, email, role FROM users WHERE role='admin' ORDER BY id ASC LIMIT 1")->fetch();
                    if ($adminRow) {
                        $_SESSION['user'] = [
                            'id' => (int)$adminRow['id'],
                            'username' => $adminRow['username'] ?: 'admin',
                            'email' => $adminRow['email'] ?? '',
                            'role' => 'admin',
                            'allowed_pages' => $adminRow['allowed_pages'] ?? null,
                        ];
                    } else {
                        // Fallback virtual admin user
                        $_SESSION['user'] = [ 'id' => 0, 'username' => 'admin', 'email' => '', 'role' => 'admin' ];
                    }
                } catch (Throwable $e) {
                    // On any DB error, still create a virtual admin user
                    $_SESSION['user'] = [ 'id' => 0, 'username' => 'admin', 'email' => '', 'role' => 'admin' ];
                }
                header('Location: ?a=admin');
                exit;
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
            $role = $_POST['role'] ?? 'evaluator';
            if (!in_array($role, ['evaluator','reviewer','admin'], true)) { $role = 'evaluator'; }
            assign_role($_SESSION['user']['id'] ?? null, $id, $role);
            flash('users','อัพเดทสิทธิ์ผู้ใช้แล้ว และบันทึกประวัติสำเร็จ');
            header('Location: ?a=admin_users');
            exit;
        case 'admin_user_role_history':
            if (!($_SESSION['is_admin'] ?? false)) { header('Location: ?a=admin_login'); exit; }
            $uid = (int)($_GET['id'] ?? 0);
            $pdo = db();
            $u = $pdo->prepare('SELECT id,username,email,role,created_at FROM users WHERE id=?');
            $u->execute([$uid]);
            $userInfo = $u->fetch();
            $rows = $pdo->prepare('SELECT ra.*, ua.username AS assigned_by_name FROM role_assignments ra LEFT JOIN users ua ON ua.id=ra.assigned_by WHERE ra.user_id=? ORDER BY ra.assigned_at DESC');
            $rows->execute([$uid]);
            $history = $rows->fetchAll();
            include __DIR__.'/views/admin/role_history.php';
            exit;

        // Reviewer document inbox
        case 'reviewer_documents':
            $me = $_SESSION['user'] ?? null;
            if (!$me || !in_array($me['role'] ?? '', ['admin','reviewer'], true)) { header('Location: ?a=login'); exit; }
            $uid = (int)$me['id'];
            $pdo = db();
            
            // แยก logic ตาม role
            if ($me['role'] === 'admin') {
                // Admin เห็นทุกเอกสารที่ผู้ใช้ส่งมาประเมิน (ทั้งที่ assign แล้วและยังไม่ assign)
                $stmt = $pdo->prepare("SELECT d.*, c.name AS category_name, a.organization_name, a.contact_email FROM documents d JOIN categories c ON c.id=d.category_id JOIN assessments a ON a.id=d.assessment_id WHERE d.status = 'PENDING' ORDER BY d.uploaded_at DESC, d.id DESC");
                $stmt->execute();
            } else {
                // Reviewer: แสดง (A) เอกสารที่ assign ให้ผู้ใช้ทุกสถานะ + (B) เอกสารที่ยังไม่มีผู้รับงานและยัง PENDING
                $stmt = $pdo->prepare("SELECT d.*, c.name AS category_name, a.organization_name, a.contact_email
                    FROM documents d
                    JOIN categories c ON c.id=d.category_id
                    JOIN assessments a ON a.id=d.assessment_id
                    WHERE (
                        JSON_VALID(d.reviewers) AND JSON_CONTAINS(d.reviewers, CAST(? AS JSON))
                    )
                    OR (
                        (d.reviewers IS NULL OR d.reviewers = '' OR d.reviewers = '[]') AND d.status='PENDING'
                    )
                    ORDER BY d.uploaded_at DESC, d.id DESC");
                $stmt->execute([ (string)$uid ]);
            }
            
            $docs = $stmt->fetchAll();
            // Fetch all reviewers (for assignment dropdown)
            $revStmt = $pdo->query("SELECT id, username FROM users WHERE role='reviewer' ORDER BY username ASC");
            $allReviewers = $revStmt->fetchAll();
            include __DIR__.'/views/reviewer/documents.php';
            exit;
        case 'assign_reviewer':
            // Allow admin and reviewer to assign reviewer(s) to a document
            $me = $_SESSION['user'] ?? null;
            if (!$me || !in_array($me['role'] ?? '', ['admin','reviewer'], true)) { header('Location: ?a=login'); exit; }
            if (!form_token_check($_POST['form_token'] ?? '')) { http_response_code(400); exit('bad request'); }
            $docId = (int)($_POST['doc_id'] ?? 0);
            $revId = (int)($_POST['reviewer_id'] ?? 0);
            if ($docId <= 0 || $revId <= 0) { header('Location: ?a=reviewer_documents'); exit; }
            $pdo = db();
            // Lock and update reviewers list
            $st = $pdo->prepare('SELECT reviewers, current_reviewer_idx, original_name FROM documents WHERE id=? FOR UPDATE');
            $pdo->beginTransaction();
            try {
                $st->execute([$docId]);
                $row = $st->fetch();
                if (!$row) { $pdo->rollBack(); header('Location: ?a=reviewer_documents'); exit; }
                $list = [];
                if (!empty($row['reviewers'])) {
                    $tmp = json_decode((string)$row['reviewers'], true);
                    if (is_array($tmp)) { $list = array_values(array_map('intval', $tmp)); }
                }
                $already = $list;
                if (!in_array($revId, $list, true)) { $list[] = $revId; }
                $upd = $pdo->prepare('UPDATE documents SET reviewers=?, current_reviewer_idx=IF(current_reviewer_idx IS NULL, 0, current_reviewer_idx) WHERE id=?');
                $upd->execute([json_encode($list), $docId]);
                // แจ้งเตือน reviewer ที่ถูก assign ใหม่
                if (!in_array($revId, $already, true)) {
                    // ดึงชื่อ reviewer
                    $docName = $row['original_name'] ?? '';
                    add_notification($revId, "คุณได้รับมอบหมายให้ตรวจเอกสาร #$docId '$docName' กรุณาเข้าระบบเพื่อรับงาน", $docId, 'doc_assigned');
                }
                add_log($me['id'] ?? null, 'assign_reviewer', "Assign reviewer #{$revId} to document #{$docId}");
                
                // Mark related notifications as read เมื่อ reviewer รับงานแล้ว
                $markRead = $pdo->prepare("UPDATE notifications SET is_read=1 WHERE user_id=? AND message LIKE ? AND is_read=0");
                $markRead->execute([$revId, "%เอกสาร #$docId%"]);
                
                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack(); throw $e;
            }
            header('Location: ?a=reviewer_documents'); exit;
        case 'assign_reviewer_remove':
            // Remove a reviewer from a document queue
            $me = $_SESSION['user'] ?? null;
            if (!$me || !in_array($me['role'] ?? '', ['admin','reviewer'], true)) { header('Location: ?a=login'); exit; }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('method not allowed'); }
            if (!form_token_check($_POST['form_token'] ?? '')) { http_response_code(400); exit('bad request'); }
            $docId = (int)($_POST['doc_id'] ?? 0);
            $revId = (int)($_POST['reviewer_id'] ?? 0);
            if ($docId <= 0 || $revId <= 0) { header('Location: ?a=reviewer_documents'); exit; }
            $pdo = db();
            $st = $pdo->prepare('SELECT reviewers, current_reviewer_idx FROM documents WHERE id=? FOR UPDATE');
            $pdo->beginTransaction();
            try {
                $st->execute([$docId]);
                $row = $st->fetch();
                if (!$row) { $pdo->rollBack(); header('Location: ?a=reviewer_documents'); exit; }
                $list = [];
                if (!empty($row['reviewers'])) {
                    $tmp = json_decode((string)$row['reviewers'], true);
                    if (is_array($tmp)) { $list = array_values(array_map('intval', $tmp)); }
                }
                $newList = [];
                foreach ($list as $id) { if ((int)$id !== $revId) $newList[] = (int)$id; }
                $newIdx = (int)($row['current_reviewer_idx'] ?? 0);
                // Adjust current index if needed
                if ($newIdx >= count($newList)) { $newIdx = max(0, count($newList) - 1); }
                $upd = $pdo->prepare('UPDATE documents SET reviewers=?, current_reviewer_idx=? WHERE id=?');
                $upd->execute([json_encode($newList), $newIdx, $docId]);
                add_log($me['id'] ?? null, 'remove_reviewer', "Remove reviewer #{$revId} from document #{$docId}");
                
                // Mark related notifications as read เมื่อ reviewer ไม่รับงาน
                $markRead = $pdo->prepare("UPDATE notifications SET is_read=1 WHERE user_id=? AND message LIKE ? AND is_read=0");
                $markRead->execute([$revId, "%เอกสาร #$docId%"]);
                
                $pdo->commit();
            } catch (Throwable $e) { $pdo->rollBack(); throw $e; }
            header('Location: ?a=reviewer_documents'); exit;
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

        // --- My documents (user submissions) ---
        case 'my_documents':
            require_login('?a=login');
            $me = $_SESSION['user'] ?? null;
            $uid = (int)($me['id'] ?? 0);
            $pdo = db();
            // Find assessments created by this user, then their documents
            $st = $pdo->prepare('SELECT d.*, c.name AS category_name FROM documents d JOIN assessments a ON a.id=d.assessment_id JOIN categories c ON c.id=d.category_id WHERE a.user_id = ? ORDER BY d.uploaded_at DESC, d.id DESC');
            $st->execute([$uid]);
            $docs = $st->fetchAll();
            view('my_documents', ['docs' => $docs]);
            break;

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
                        // แจ้งเตือนเจ้าของเอกสาร (โค้ดส่วนนี้เป็น dev-only ฟอร์มอย่างง่าย ไม่รวมข้อมูลเอกสาร)
                        add_notification((int)$doc['user_id'], "เอกสารของคุณถูกรีวิว: $status", (int)$doc['id'], 'doc_reviewed');
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
            if (!$assessment_id) { echo 'ไม่พบข้อมูล'; exit;
            }
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
            echo "<table border='1' cellpadding='4' cellspacing='0' style='font-family:thsarabun,sans-serif;font-size:16pt;'>";
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

            // Always use mPDF with Sarabun font for Thai
            if (file_exists(__DIR__.'/vendor/autoload.php')) {
                require_once __DIR__.'/vendor/autoload.php';
                if (class_exists('\\Mpdf\\Mpdf')) {
                    try {
                        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
                        $fontDirs = $defaultConfig['fontDir'];
                        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
                        $fontData = $defaultFontConfig['fontdata'];
                        $mpdf = new \Mpdf\Mpdf([
                            'tempDir' => __DIR__.'/tmp',
                            'fontDir' => array_merge($fontDirs, [__DIR__ . '/assets/fonts']),
                            'fontdata' => $fontData + [
                                'thsarabun' => [
                                    'R' => 'THSarabunNew.ttf',
                                    'B' => 'THSarabunNew-Bold.ttf',
                                    'I' => 'THSarabunNew-Italic.ttf',
                                    'BI' => 'THSarabunNew-BoldItalic.ttf',
                                ],
                            ],
                            'default_font' => 'thsarabun',
                        ]);
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
            echo '<style>body{font-family:THSarabunNew,thsarabun,sans-serif;margin:20px;}table{border-collapse:collapse;width:100%;font-size:16pt;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background-color:#f2f2f2;}.print-instruction{background:#ffffcc;padding:10px;margin:10px 0;border:1px solid #ffeb3b;}@media print{.print-instruction{display:none;}}</style>';
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
        // --- Assessment detail view ---
        case 'assessment_detail':
            require_login('?a=assessment_detail');
            if (!user_can_access('assessment_detail')) { echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; exit; }
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) { header('Location: ?a=history'); exit; }
            $pdo = db();
            $user = $_SESSION['user'] ?? null;
            $isAdmin = ($user['role'] ?? '') === 'admin' || (!empty($_SESSION['is_admin']));
            $stmt = $pdo->prepare('SELECT * FROM assessments WHERE id = ? AND deleted_at IS NULL');
            $stmt->execute([$id]);
            $assessment = $stmt->fetch();
            if (!$assessment || (!$isAdmin && $assessment['user_id'] != ($user['id'] ?? 0))) {
                echo 'ไม่พบข้อมูลหรือไม่มีสิทธิ์เข้าถึง'; exit;
            }
            $answers = $pdo->prepare('SELECT q.code, q.text, q.category, q.weight, a.answer_value, a.notes FROM questions q LEFT JOIN answers a ON a.question_id = q.id AND a.assessment_id = ? ORDER BY q.id');
            $answers->execute([$id]);
            view('assessment_detail', [
                'assessment' => $assessment,
                'answers' => $answers->fetchAll(),
            ]);
            break;
        case 'history':
            $user = $_SESSION['user'] ?? null;
            $isAdmin = (($user['role'] ?? '') === 'admin') || (!empty($_SESSION['is_admin']));
            $isReviewer = ($user['role'] ?? '') === 'reviewer';
            $rows = [];
            if ($user) {
                $pdo = db();
                if ($isAdmin) {
                    $rows = $pdo->query("SELECT a.*, u.username FROM assessments a LEFT JOIN users u ON a.user_id = u.id WHERE a.deleted_at IS NULL ORDER BY a.started_at DESC, a.id DESC")->fetchAll();
                } elseif ($isReviewer) {
                    // Reviewer sees all assessments for review (exclude deleted)
                    $rows = $pdo->query("SELECT a.*, u.username FROM assessments a LEFT JOIN users u ON a.user_id = u.id WHERE a.deleted_at IS NULL ORDER BY a.started_at DESC, a.id DESC")->fetchAll();
                } else {
                    $uid = $user['id'] ?? 0;
                    $stmt = $pdo->prepare('SELECT * FROM assessments WHERE user_id = ? AND deleted_at IS NULL ORDER BY started_at DESC, id DESC');
                    $stmt->execute([$uid]);
                    $rows = $stmt->fetchAll();
                }
            } else if ($isAdmin) {
                // Admin without user session (e.g., admin_login path)
                $pdo = db();
                $rows = $pdo->query("SELECT a.*, u.username FROM assessments a LEFT JOIN users u ON a.user_id = u.id WHERE a.deleted_at IS NULL ORDER BY a.started_at DESC, a.id DESC")->fetchAll();
            }
            view('history', ['rows' => $rows, 'isAdmin' => $isAdmin, 'user' => $user]);
            break;

        // --- Admin: delete an assessment (and its files) ---
        case 'admin_assessment_delete':
            $me = $_SESSION['user'] ?? null;
            $isAdmin = (!empty($_SESSION['is_admin'])) || (($me['role'] ?? '') === 'admin');
            if (!$isAdmin) { http_response_code(403); exit('forbidden'); }
            if (!form_token_check($_POST['form_token'] ?? '')) { http_response_code(400); exit('bad request'); }
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) { header('Location: ?a=history'); exit; }
            $pdo = db();
            try {
                // Soft delete assessment (keep related data for audit)
                $pdo->prepare('UPDATE assessments SET deleted_at = NOW() WHERE id = ?')->execute([$id]);
                add_log($me['id'] ?? null, 'assessment_delete', "Soft delete assessment #{$id}");
            } catch (Throwable $e) {
                // Optionally set a flash message; for now just rethrow
                throw $e;
            }
            header('Location: ?a=history');
            exit;

        // --- User notifications ---
        case 'notifications':
            require_login('?a=notifications');
            if (!user_can_access('notifications')) { echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; exit; }
            $pdo = db();
            $user = $_SESSION['user'] ?? null;
            $uid = $user['id'] ?? 0;
            $rows = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC, id DESC');
            $rows->execute([$uid]);
            $notifications = $rows->fetchAll();
            view('notifications', ['notifications' => $notifications]);
            break;
        case 'notifications_mark_all_read':
            require_login('?a=notifications');
            $me = $_SESSION['user'] ?? null;
            $uid = (int)($me['id'] ?? 0);
            if ($uid > 0) {
                db()->prepare('UPDATE notifications SET is_read=1 WHERE user_id=?')->execute([$uid]);
            }
            header('Location: ?a=notifications');
            exit;
            
        // --- Compare assessment ---
        case 'compare_assessment':
            require_login('?a=compare_assessment');
            if (!user_can_access('compare_assessment')) { echo 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้'; exit; }
            $id = (int)($_GET['id'] ?? 0);
            if (!$id) { header('Location: ?a=history'); exit; }
            $pdo = db();
            $user = $_SESSION['user'] ?? null;
            $isAdmin = ($user['role'] ?? '') === 'admin' || (!empty($_SESSION['is_admin']));
            $stmt = $pdo->prepare('SELECT * FROM assessments WHERE id = ? AND deleted_at IS NULL');
            $stmt->execute([$id]);
            $assessment = $stmt->fetch();
            if (!$assessment || (!$isAdmin && $assessment['user_id'] != ($user['id'] ?? 0))) {
                echo 'ไม่พบข้อมูลหรือไม่มีสิทธิ์เข้าถึง'; exit;
            }
            $answers = $pdo->prepare('SELECT q.code, q.text, q.category, q.weight, a.answer_value, a.notes FROM questions q LEFT JOIN answers a ON a.question_id = q.id AND a.assessment_id = ? ORDER BY q.id');
            $answers->execute([$id]);
            $current_answers = $answers->fetchAll();
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
