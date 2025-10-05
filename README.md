<<<<<<< HEAD
# pdpa-assessment-system
=======
# PDPA Self Assessment System for CII# ระบบประเมิน PDPA (PHP + MySQL)



## 📋 ภาพรวมระบบเว็บแอปประเมิน PDPA น้ำหนักเบา ไม่ใช้เฟรมเวิร์ก ใช้ PHP (PDO), MySQL และ Docker สำหรับเริ่มต้นใช้งานได้รวดเร็ว



ระบบประเมินตนเอง PDPA (Personal Data Protection Act) สำหรับหน่วยงานโครงสร้างพื้นฐานสำคัญ (Critical Information Infrastructure) เป็นเว็บแอปพลิเคชันที่ช่วยให้องค์กรสามารถประเมินความสอดคล้องกับ พรบ.คุ้มครองข้อมูลส่วนบุคคล และ พรบ.ไซเบอร์ได้อย่างครอบคลุม---



### 🎯 วัตถุประสงค์## System Overview (English)

- ตรวจสอบการปฏิบัติตามกฎหมายที่เกี่ยวข้องA web-based assessment platform for PDPA and CII Self-Assessment (D1, D2, D3) with admin management, autosave, and import/export features. Built with PHP, MySQL, Docker, and vanilla JS.

- ประเมินความสอดคล้องกับ พรบ.ไซเบอร์

- ระบุเอกสารและหลักฐานที่ยังขาดหายไป### Features

- สร้างรายงานสำหรับ Audit Compliance CheckList- PDPA and CII (D1, D2, D3) questionnaire with scoring and notes

- Category-based tab UI for questions

## 🚀 การติดตั้งและใช้งาน- Autosave for answers and notes

- Admin panel for managing questions, categories, and users

### ความต้องการของระบบ- Import/export questions via CSV/XLSX (supports bulk import from Excel)

- Docker & Docker Compose- Dockerized for easy deployment and live-reload during development

- Git (สำหรับดาวน์โหลดโค้ด)- User authentication and role management
- **ระบบให้คะแนน 3 ระดับ** (1=ไม่มี, 2=มีบางส่วน, 3=มีครบถ้วน)

- **คำนวณคะแนนแบบถ่วงน้ำหนัก** ตามความสำคัญของหมวด   หรือระบุไฟล์โดยตรง (Windows PowerShell):

- **แสดงผลระดับความเสี่ยง** (เขียว/เหลือง/แดง)

- **บันทึกอัตโนมัติ** (Auto-save) ขณะตอบคำถาม   docker compose -f d:\www\PDPA\docker-compose.yml up -d --build web

- **อัปโหลดไฟล์หลักฐาน** แยกตามหมวดหมู่

- **ระบบรีวิวเอกสาร** โดยผู้ดูแล---
- **แจ้งเตือนผลการรีวิว** ทางอีเมลและในระบบ## Admin Panel

- Login as admin (default: admin / admin1234)

### รายงานและการส่งออก- Manage questions, categories, and import/export data

- **Dashboard แบบกราฟิก** ด้วย Chart.js- Use “นำเข้า XLSX” to bulk import questions from Excel (see below)

- **ส่งออก Excel** (.xlsx) ด้วย PhpSpreadsheet

- **ส่งออก PDF** ด้วย mPDF## Importing D1, D2, D3 from Excel

- **รายงานสรุป** ตามหมวดหมู่- Prepare an Excel file with a sheet (e.g. "Clean") containing columns:

- **เปรียบเทียบผลย้อนหลัง**  - `ลำดับที่`, `รายการ (Objective)`, `ที่มา (Requirement)`, `ส่วนประกอบ`, `หลักฐาน (Evident)`, `กลุ่ม`

- Use the provided Python script (`convert_clean_tab_to_ready_xlsx.py`) to convert to the required format:

### ความปลอดภัย  - `category_code`, `category_name`, `category_weight`, `category_description`, `question_code`, `question_text`, `question_weight`

- **ระบบ Login/Logout** ที่ปลอดภัย- Upload the resulting file via the admin panel (นำเข้า XLSX)

- **จัดการสิทธิ์** (Admin/User)

- **Password Hashing** ด้วย bcrypt## Database
## Troubleshooting


- Developed by your team, 2025

## 🗂️ โครงสร้างไฟล์- Uses PhpSpreadsheet for Excel import

PDPA/

├── docker-compose.yml     # Docker configuration### 1. ประวัติการประเมินย้อนหลัง (Assessment History)
├── views/                # Template files- ปุ่มย้อนดูรายละเอียดแต่ละรอบ


│   ├── questions.php- สรุปคะแนน, หมวด, หมายเหตุ, กราฟ, ข้อมูลผู้ประเมิน

│   ├── dashboard.php- ใช้ไลบรารีเช่น PhpSpreadsheet (Excel) และ mPDF หรือ Dompdf (PDF)

│   ├── history.php

│   └── admin/            # Admin templates### 3. REST API สำหรับเชื่อมต่อภายนอก

├── assets/               # CSS, JS, images- Endpoint สำหรับดึง/ส่งข้อมูลผลประเมิน (เช่น /api/assessments, /api/results)

├── uploads/              # Uploaded documents- Auth ด้วย API key หรือ JWT

└── tmp/                  # Temporary files- ตัวอย่าง: GET /api/assessments?user_id=...  POST /api/assessments

```- Document API ด้วย Swagger หรือ Markdown



## 🔧 การกำหนดค่า### 4. Permission Matrix (สิทธิ์ละเอียด)

- กำหนดสิทธิ์แต่ละ role (admin, reviewer, evaluator)

### ตัวแปรสภาพแวดล้อม- reviewer เห็นเฉพาะบางหมวด/บาง assessment

```bash- UI สำหรับ admin กำหนดสิทธิ์ (checkbox matrix)

MYSQL_ROOT_PASSWORD=rootpass- ตรวจสอบสิทธิ์ก่อนแสดง/แก้ไขข้อมูล

MYSQL_DATABASE=pdpa_db

MYSQL_USER=pdpa_user### 5. FAQ/คู่มือออนไลน์ (Static Help/Docs)

MYSQL_PASSWORD=pdpa_pass- เพิ่มหน้า static หรือ markdown สำหรับคู่มือ, FAQ, วิธีใช้งาน, คำถามพบบ่อย

ADMIN_PASS=admin1234- อัปเดตง่าย (แก้ markdown หรือ HTML ตรง ๆ)

```- ลิงก์จากเมนูหลัก/หน้า login



### การเปลี่ยนรหัสผ่านผู้ดูแล---

1. เข้าสู่ระบบผู้ดูแล

2. ไปที่ "การตั้งค่า"## Implementation Notes

3. ใส่รหัสผ่านใหม่และบันทึก- ฟีเจอร์เหล่านี้สามารถเพิ่มทีละส่วนโดยไม่กระทบระบบหลัก

- แนะนำให้เริ่มจาก Assessment History และ Export ก่อน (มีผลกับผู้ใช้มากสุด)
- FAQ/คู่มือออนไลน์ ช่วยลดภาระ support และ onboarding

ระบบรองรับการส่งอีเมลแจ้งเตือนในกรณี:

- มีการประเมินใหม่---

- มีการแนบเอกสาร

- ผลการรีวิวเอกสาร## Example API Endpoint (ตัวอย่าง)

- `GET /api/assessments` — รายการประเมินทั้งหมด (ตามสิทธิ์)

> **หมายเหตุ**: ในเวอร์ชันปัจจุบันเป็น Demo การส่งอีเมล- `GET /api/assessment/{id}` — รายละเอียดผลประเมิน

- `POST /api/assessment` — สร้างผลประเมินใหม่

## 🐛 การแก้ไขปัญหา- `GET /api/categories` — รายการหมวด



### ปัญหาที่พบบ่อย---



1. **ไม่สามารถเข้าถึงได้**## Example Permission Matrix Table

   ```bash| Role      | ดูผลรวม | ดูรายละเอียด | แก้ไข | ลบ | Export | เห็นหมวด D1 | เห็นหมวด D2 | เห็นหมวด D3 |

   docker compose down|-----------|---------|---------------|-------|-----|--------|-------------|-------------|-------------|

   docker compose up -d --build| admin     | ✓       | ✓             | ✓     | ✓   | ✓      | ✓           | ✓           | ✓           |

   ```| reviewer  | ✓       | ✓             |       |     | ✓      | ✓           | ✓           | (เลือกได้)  |

| evaluator | ✓       |               |       |     |        | ✓           |             |             |
   ```bash---

   docker exec -it pdpa_mysql mysql -u root -p

   # ตรวจสอบ database pdpa_db## Example FAQ/Help (Markdown)

   ```- `docs/faq.md` หรือ `views/faq.php`

- ตัวอย่างหัวข้อ: วิธีนำเข้า Excel, วิธี export, วิธีดูประวัติ, วิธี reset password ฯลฯ

3. **ไฟล์ Permission**

   ```bash---

   docker exec pdpa_web chmod -R 777 /var/www/html/uploads

   docker exec pdpa_web chmod -R 777 /var/www/html/tmp## REST API Usage

   ```

- Endpoint: `/api.php?path=assessments` — รายการผลประเมินทั้งหมด (หรือกรอง user_id)

4. **Reset ข้อมูล**- Endpoint: `/api.php?path=assessment&id=...` — รายละเอียดผลประเมินแต่ละรายการ

   ```bash- (Demo: read-only, ยังไม่เปิด auth จริง)

   docker compose down -v  # ลบ volumes

   docker compose up -d --buildตัวอย่าง:

   ```- `GET http://localhost:8080/api.php?path=assessments`

- `GET http://localhost:8080/api.php?path=assessments&user_id=1`

ระบบมี REST API สำหรับการเชื่อมต่อภายนอก:

- `GET /api.php?action=assessments` - รายการการประเมิน
- `GET /api.php?action=assessment&id=X` - ข้อมูลการประเมิน

## 📝 ข้อมูลสำคัญที่ผู้ใช้ควรทราบ
### ก่อนเริ่มใช้งาน
1. **เตรียมเอกสาร** - รวบรวมเอกสารหลักฐานที่เกี่ยวข้องกับ PDPA
2. **กำหนดผู้รับผิดชอบ** - แต่งตั้งผู้ประเมินและผู้รีวิว
3. **อ่านวัตถุประสงค์** - ทำความเข้าใจเป้าหมายของการประเมิน

### ระหว่างการใช้งาน
1. **ตอบตามความเป็นจริง** - ประเมินตามสถานการณ์จริงขององค์กร
2. **แนบเอกสารประกอบ** - อัปโหลดหลักฐานที่เกี่ยวข้อง
3. **บันทึกหมายเหตุ** - อธิบายรายละเอียดเพิ่มเติมในแต่ละข้อ

### หลังการประเมิน
1. **ตรวจสอบผล** - ดูคะแนนและระดับความเสี่ยง
2. **วางแผนปรับปรุง** - ใช้ผลการประเมินเพื่อปรับปรุงองค์กร
3. **ติดตามความคืบหน้า** - ประเมินซ้ำเป็นระยะ

## 👥 การสนับสนุน

สำหรับคำถาม ปัญหา หรือข้อเสนอแนะ:
- ใช้ระบบ Feedback ในแอปพลิเคชัน
- สร้าง Issue ใน GitHub Repository
- ติดต่อทีมพัฒนา

## 📄 License

โปรเจกต์นี้เป็น Open Source สามารถนำไปใช้และปรับปรุงได้ตามต้องการ

---

**หมายเหตุ**: ระบบนี้พัฒนาขึ้นเพื่อช่วยในการประเมิน PDPA Compliance และไม่ควรใช้แทนการปรึกษาผู้เชี่ยวชาญด้านกฎหมายโดยตรง
>>>>>>> 52559a3 (Initial commit: PDPA Self Assessment System for CII)
