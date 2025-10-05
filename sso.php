<?php
// sso.php (Google/Microsoft OAuth2 SSO - โครงสร้างเบื้องต้น)
// หมายเหตุ: ต้องลงทะเบียน OAuth2 credentials และติดตั้งไลบรารีเสริม (เช่น league/oauth2-client)

// ตัวอย่างโค้ดโครงสร้าง (ยังไม่สมบูรณ์)

if (isset($_GET['provider'])) {
    $provider = $_GET['provider'];
    if ($provider === 'google') {
        // Redirect ไป Google OAuth2
        // ...
        echo 'Google SSO ยังไม่เปิดใช้งานจริง (demo)';
    } elseif ($provider === 'microsoft') {
        // Redirect ไป Microsoft OAuth2
        // ...
        echo 'Microsoft SSO ยังไม่เปิดใช้งานจริง (demo)';
    }
    exit;
}

// ปุ่ม SSO บนหน้า login
?>
<a href="sso.php?provider=google"><button>Sign in with Google</button></a>
<a href="sso.php?provider=microsoft"><button>Sign in with Microsoft</button></a>
