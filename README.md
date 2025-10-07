# PDPA Self Assessment System

ระบบประเมินตนเอง PDPA สำหรับหน่วยงานโครงสร้างพื้นฐานสำคัญ (CII)
รองรับการประเมิน, แนบเอกสาร, ตรวจสอบหลายขั้นตอน, แจ้งเตือน, และจัดการสิทธิ์แบบครบวงจร

## ฟีเจอร์หลัก
- แบบประเมิน Self-Assessment พร้อมคำนวณคะแนนและระดับความเสี่ยง
- แนบไฟล์หลักฐานแยกแต่ละข้อ
- Reviewer Workflow: มอบหมาย, รับงาน, ตรวจสอบ, แจ้งผล
- Admin Dashboard: จัดการผู้ใช้, เอกสาร, สิทธิ์, ส่งออก/นำเข้า
- ระบบแจ้งเตือน (Notification) พร้อม Action ในกล่องแจ้งเตือน
- Audit Trail & Timeline
- ส่งออก Excel/PDF

## การติดตั้ง
1. ติดตั้ง Docker และ Git
2. Clone repo:
   ```bash
   git clone https://github.com/thering999/pdpa-assessment-system.git
   cd pdpa-assessment-system
   ```
3. สั่งรัน:
   ```bash
   docker compose up -d --build
   ```
4. เปิดใช้งานที่ `http://localhost:8080`

## ตัวอย่างบัญชีผู้ใช้
- **Admin**: admin / admin1234
- **Reviewer**: reviewer1 / password123
- **Evaluator**: evaluator1 / password123

## Flow การใช้งาน
- Evaluator: ทำแบบประเมิน, แนบไฟล์, ดูผล/เปรียบเทียบ
- Reviewer: รับงานจากแจ้งเตือนหรือหน้า Reviewer, ตรวจสอบ, อนุมัติ/ไม่อนุมัติ, ใส่ความเห็น
- Admin: มอบหมาย reviewer, จัดการผู้ใช้, ดูสถิติ, ส่งออก/นำเข้า

## อัปเดตล่าสุด (ต.ค. 2025)
- Reviewer Inbox แบบอีเมล: รับงาน/ไม่รับงานจากแจ้งเตือน
- Action ในแจ้งเตือนทำงานจริง (doc_id, reviewer_id, form_token)
- Admin เห็นเอกสารทุกสถานะ, Reviewer เห็นเฉพาะคิวตัวเอง
- ปรับ UI/UX: สีสถานะ, ตาราง, ปุ่ม, กล่องแจ้งเตือน, sidebar

## วิธีอัปโหลด/Deploy
1. แก้ไขโค้ดตามต้องการ
2. git add . && git commit -m "update"
3. git push origin main
4. ระบบ production ใช้ Docker Compose เช่นเดียวกับ dev

## โครงสร้างไฟล์
```
PDPA/
├── views/
│   ├── admin/          # หน้าจอสำหรับ Admin
│   ├── reviewer/       # หน้าจอสำหรับ Reviewer
│   ├── history.php     # ประวัติการประเมิน
│   ├── dashboard.php   # แดชบอร์ด
│   └── notifications.php # การแจ้งเตือน
├── assets/             # CSS, JS, Images
├── db.php             # ฟังก์ชันฐานข้อมูล
├── index.php          # Controller หลัก
└── docker-compose.yml # การตั้งค่า Docker
```

## ติดต่อทีมพัฒนา
**พัฒนาโดย**: ทีม PDPA Development
**อัปเดตล่าสุด**: ตุลาคม 2025
