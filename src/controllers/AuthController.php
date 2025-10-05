<?php
/**
 * Authentication Controller
 * Handles user login, logout, and registration
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $pdo = getDBConnection();
        $this->userModel = new User($pdo);
    }
    
    /**
     * Login user
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                return ['success' => false, 'message' => 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน'];
            }
            
            $user = $this->userModel->authenticate($username, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                
                return ['success' => true, 'redirect' => 'dashboard.php'];
            } else {
                return ['success' => false, 'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'];
            }
        }
    }
    
    /**
     * Register new user
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $email = $_POST['email'] ?? '';
            $fullName = $_POST['full_name'] ?? '';
            $organization = $_POST['organization'] ?? '';
            
            // Validation
            if (empty($username) || empty($password) || empty($email) || empty($fullName)) {
                return ['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน'];
            }
            
            if (strlen($password) < 6) {
                return ['success' => false, 'message' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร'];
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'รูปแบบอีเมลไม่ถูกต้อง'];
            }
            
            $userId = $this->userModel->create($username, $password, $email, $fullName, $organization);
            
            if ($userId) {
                return ['success' => true, 'message' => 'ลงทะเบียนสำเร็จ กรุณาเข้าสู่ระบบ'];
            } else {
                return ['success' => false, 'message' => 'ไม่สามารถลงทะเบียนได้ ชื่อผู้ใช้หรืออีเมลอาจถูกใช้งานแล้ว'];
            }
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
    
    /**
     * Require login
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: index.php');
            exit;
        }
    }
    
    /**
     * Require admin
     */
    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: dashboard.php');
            exit;
        }
    }
}
