<?php
// views/admin_login.php
?><h2>เข้าสู่ระบบผู้ดูแล</h2>
<form method="post">
  <input type="text" name="username" placeholder="Username" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button type="submit">เข้าสู่ระบบ</button>
</form>
<div style="margin-top:1em;">
  <a href="/sso.php?provider=google"><button type="button">Sign in with Google</button></a>
  <a href="/sso.php?provider=microsoft"><button type="button">Sign in with Microsoft</button></a>
</div>
