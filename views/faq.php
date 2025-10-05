<?php
// FAQ/คู่มือออนไลน์ (markdown หรือ static)
echo '<h2>FAQ / คู่มือการใช้งาน</h2>';
echo '<ul>';
echo '<li>Q: วิธีเริ่มต้นใช้งานระบบ?<br>A: ดูที่หัวข้อ "วิธีเริ่มต้นใช้งาน (Docker)" ใน README.md</li>';
echo '<li>Q: วิธี Export/Import ข้อมูล?<br>A: ไปที่เมนู Export/Import ใน admin</li>';
echo '<li>Q: ติดต่อ support ได้อย่างไร?<br>A: ส่งอีเมลไปที่ admin</li>';
echo '</ul>';

<section class="card" style="max-width:700px;margin:32px auto;">
  <h2>FAQ & คู่มือการใช้งานระบบประเมิน PDPA</h2>
  <h3>1. วิธีนำเข้าแบบประเมิน D1/D2/D3 จาก Excel</h3>
  <ul>
    <li>เตรียมไฟล์ Excel ให้มีคอลัมน์ <code>ลำดับที่, รายการ (Objective), ที่มา (Requirement), ส่วนประกอบ, หลักฐาน (Evident), กลุ่ม</code></li>
    <li>ใช้สคริปต์ <code>convert_clean_tab_to_ready_xlsx.py</code> เพื่อแปลงเป็นไฟล์ที่ระบบนำเข้าได้</li>
    <li>ไปที่เมนู Admin &gt; นำเข้า XLSX แล้วอัปโหลดไฟล์</li>
  </ul>
  <h3>2. วิธี Export ผลประเมินเป็น Excel</h3>
  <ul>
    <li>ไปที่หน้าสรุปผล (Result/Dashboard)</li>
    <li>คลิกปุ่ม <b>Export Excel</b> เพื่อดาวน์โหลดไฟล์</li>
  </ul>
  <h3>3. ดูประวัติการประเมินย้อนหลัง</h3>
  <ul>
    <li>ไปที่เมนู <b>ประวัติการประเมิน</b> ในแถบเมนูหลัก</li>
    <li>เลือกดูรายละเอียดแต่ละรอบ หรือเปรียบเทียบคะแนนย้อนหลัง</li>
  </ul>
  <h3>4. การขอสิทธิ์/เปลี่ยนบทบาทผู้ใช้</h3>
  <ul>
    <li>ติดต่อแอดมินระบบเพื่อขอเปลี่ยนบทบาท (evaluator, reviewer, admin)</li>
    <li>แอดมินสามารถกำหนดสิทธิ์ละเอียดใน Permission Matrix</li>
  </ul>
  <h3>5. การใช้งาน REST API</h3>
  <ul>
    <li>ดูเอกสาร API ที่ <code>/api/docs</code> (ถ้ามี)</li>
    <li>ตัวอย่าง: <code>GET /api/assessments</code> ดึงรายการผลประเมิน</li>
    <li>ต้องขอ API key หรือ Token จากแอดมินก่อนใช้งาน</li>
  </ul>
  <h3>6. ลืมรหัสผ่าน/รีเซ็ต</h3>
  <ul>
    <li>คลิก <b>ลืมรหัสผ่าน</b> ที่หน้าเข้าสู่ระบบ หรือแจ้งแอดมิน</li>
  </ul>
  <h3>7. ติดต่อ/แจ้งปัญหา</h3>
  <ul>
    <li>แจ้งปัญหาผ่านอีเมลแอดมิน หรือระบบแจ้งเตือนในแอป</li>
  </ul>
</section>
