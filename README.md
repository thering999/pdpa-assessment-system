# PDPA Self Assessment System for CII
# ระบบประเมิน PDPA (PHP + MySQL)

## 📋 ภาพรวมระบบ
ระบบประเมินตนเอง PDPA สำหรับหน่วยงานโครงสร้างพื้นฐานสำคัญ (CII) ใช้ PHP (PDO), MySQL, Docker ไม่ใช้เฟรมเวิร์ก ติดตั้งง่าย ใช้งานเร็ว

---

## 🛠️ ขั้นตอนการติดตั้ง
1. ติดตั้ง Docker และ Git
2. ดาวน์โหลดโปรเจกต์
   ```bash
   git clone https://github.com/thering999/pdpa-assessment-system.git
   cd pdpa-assessment-system
   ```
3. สั่งรันระบบ
   ```bash
   docker compose up -d --build
   ```
4. เปิดเว็บเบราว์เซอร์ไปที่ `http://localhost:8080`

---

## 🚪 ขั้นตอนการเข้าใช้งาน
1. เปิดหน้าเว็บ `http://localhost:8080`
2. เข้าสู่ระบบด้วยบัญชีตัวอย่าง (หรือสมัครใหม่)
   - Admin: admin / admin1234
   - Reviewer: reviewer1 / password123
   - Evaluator: evaluator1 / password123
3. เลือกเมนูตามสิทธิ์ (ประเมิน, ตรวจสอบ, จัดการ)

---

## 📝 ขั้นตอนการทำงาน
### สำหรับ Evaluator
1. เริ่มทำแบบประเมิน (หน้าแรก)
2. ตอบคำถามแต่ละข้อ
3. แนบไฟล์หลักฐาน
4. บันทึกหมายเหตุ/รายละเอียด
5. ดูผลคะแนนและระดับความเสี่ยง
6. ดาวน์โหลดรายงาน Excel/PDF

### สำหรับ Reviewer
1. รับงานจากแจ้งเตือนหรือหน้า Reviewer
2. ตรวจสอบเอกสาร/หลักฐาน
3. อนุมัติ/ไม่อนุมัติ/ให้ความเห็น
4. ติดตามสถานะงานในคิว

### สำหรับ Admin
1. มอบหมาย reviewer ให้เอกสาร
2. จัดการผู้ใช้/สิทธิ์
3. ดูสถิติ/แดชบอร์ด
4. ส่งออก/นำเข้าข้อมูล

---

## 🐛 ขั้นตอนการแก้ปัญหา
1. **Database ไม่เชื่อมต่อ**
   - ตรวจสอบว่า Docker container ของ MySQL และ web ทำงานอยู่
   - ตรวจสอบไฟล์ .env และ environment variables
2. **เข้าใช้งานไม่ได้/Permission Denied**
   - ตรวจสอบสิทธิ์ผู้ใช้ (Admin/Reviewer/Evaluator)
   - ล็อกอินใหม่หรือรีเฟรชหน้า
3. **Import/Export ไม่สำเร็จ**
   - ตรวจสอบรูปแบบไฟล์ CSV/XLSX
   - ดู error log ใน Docker (`docker compose logs web`)
4. **ระบบไม่แสดงผล/bug**
   - รีสตาร์ท container (`docker compose restart`)
   - ตรวจสอบ log และแจ้งทีมพัฒนา

---

## ติดต่อทีมพัฒนา
**พัฒนาโดย**: ทีม PDPA Development
**อัปเดตล่าสุด**: ตุลาคม 2025

---

## 🧑‍💻 ตัวอย่างการใช้งานจริง
### ตัวอย่างการนำเข้าไฟล์ Excel/CVS
1. ไปที่เมนู Admin > นำเข้า XLSX หรือ CSV
2. เลือกไฟล์ที่มีหัวคอลัมน์ตามตัวอย่าง (ดูไฟล์ตัวอย่างในโฟลเดอร์โปรเจกต์)
3. กด "อัปโหลดและนำเข้า" ระบบจะแสดงผลลัพธ์

### ตัวอย่างการ Export ผลประเมิน
1. ไปที่หน้าสรุปผล (Result/Dashboard)
2. กดปุ่ม "Export Excel" หรือ "Export PDF" เพื่อดาวน์โหลด

### ตัวอย่างการเปลี่ยนสิทธิ์ผู้ใช้
1. ไปที่ Admin > จัดการผู้ใช้
2. กด "อัพเดท" เพื่อเปลี่ยนบทบาท (evaluator, reviewer, admin)

---

## ❓ FAQ คำถามที่พบบ่อย
**Q: วิธีเริ่มต้นใช้งานระบบ?**  
A: ดูหัวข้อ "ขั้นตอนการติดตั้ง" ด้านบน

**Q: วิธีนำเข้า/ส่งออกข้อมูล?**  
A: ใช้เมนู Admin > นำเข้า/ส่งออก (รองรับ CSV/XLSX/Excel/PDF)

**Q: ลืมรหัสผ่าน/รีเซ็ต?**  
A: กด "ลืมรหัสผ่าน" ที่หน้า Login หรือแจ้งแอดมิน

**Q: ติดต่อ support ได้อย่างไร?**  
A: ดูข้อมูลติดต่อใน README หรือหน้าแรกของระบบ

**Q: วิธีดู audit log?**  
A: เฉพาะ admin: ไปที่ Admin > Audit Log

**Q: การใช้งาน REST API?**  
A: ดูเอกสารที่ /api/docs (ถ้ามี) หรือสอบถามแอดมิน

---

## 📁 โครงสร้างไฟล์โปรเจกต์
```
PDPA/
├── docker-compose.yml
├── Dockerfile
├── composer.json
├── config.php
├── pdpa_system.sql
├── assets/
│   ├── style.css, app.js, logo_moph.png
├── uploads/           # ไฟล์ที่ผู้ใช้แนบ
├── tmp/               # ไฟล์ชั่วคราว/รายงาน
├── views/             # ไฟล์ PHP สำหรับแต่ละหน้า (admin, reviewer, auth, ...)
│   ├── admin/
│   ├── reviewer/
│   ├── auth/
├── index.php          # Entry point
└── ...
```

---

## 🔒 Security Tips
- เปลี่ยนรหัสผ่าน admin หลังติดตั้ง
- จำกัดสิทธิ์ผู้ใช้ตามบทบาท (Permission Matrix)
- ตรวจสอบ audit log เป็นประจำ
- อัปเดต Docker/Composer dependencies สม่ำเสมอ
- ไม่ควรเปิด public uploads โดยตรง

---

## 📝 Changelog
- 2025-10-07: เพิ่มคู่มือ, ตัวอย่าง, FAQ, Security, โครงสร้างไฟล์
- 2025-09-30: เพิ่มระบบ reviewer queue, notification, import/export
- 2025-09-15: ปรับ UI/UX, dark mode, audit log, autosave
- 2025-09-01: สร้างระบบประเมิน PDPA (PHP/MySQL/Docker)

---

## 📄 License
This project is licensed under the MIT License. See LICENSE file for details.
