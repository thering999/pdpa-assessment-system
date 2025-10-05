# PDPA Self Assessment System for CII
# ระบบประเมิน PDPA (PHP + MySQL)

## 📋 ภาพรวม

ระบบประเมินตนเอง PDPA (Personal Data Protection Act) สำหรับหน่วยงานโครงสร้างพื้นฐานสำคัญ (Critical Information Infrastructure) เป็นเว็บแอปพลิเคชันที่ช่วยให้องค์กรสามารถประเมินความสอดคล้องกับ พรบ.คุ้มครองข้อมูลส่วนบุคคล และ พรบ.ไซเบอร์ได้อย่างครอบคลุม

## Overview

A lightweight PDPA (Personal Data Protection Act) self-assessment web application for Critical Information Infrastructure (CII) organizations. Built with PHP (PDO), MySQL, and Docker for quick deployment without using frameworks.

## 🚀 Features / คุณสมบัติ

- ✅ Self-assessment questionnaire for PDPA compliance / แบบประเมินตนเองตาม พรบ.คุ้มครองข้อมูลส่วนบุคคล
- ✅ Cyber law compliance assessment / ประเมินความสอดคล้องตาม พรบ.ไซเบอร์
- ✅ User authentication and management / ระบบจัดการผู้ใช้และการยืนยันตัวตน
- ✅ Assessment results and reports / ผลการประเมินและรายงาน
- ✅ Docker support for easy deployment / รองรับ Docker เพื่อการติดตั้งง่าย
- ✅ No framework dependencies / ไม่ใช้เฟรมเวิร์ก น้ำหนักเบา

## 🛠️ Tech Stack / เทคโนโลยี

- **Backend**: PHP 8.1+ with PDO
- **Database**: MySQL 8.0
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Containerization**: Docker & Docker Compose
- **Web Server**: Apache

## 📦 Installation / การติดตั้ง

### Prerequisites / ข้อกำหนดเบื้องต้น

- Docker and Docker Compose installed
- Git

### Quick Start / เริ่มต้นใช้งานอย่างรวดเร็ว

1. Clone the repository / โคลนโปรเจ็กต์
```bash
git clone https://github.com/thering999/pdpa-assessment-system.git
cd pdpa-assessment-system
```

2. Start with Docker Compose / เริ่มใช้งานด้วย Docker Compose
```bash
docker-compose up -d
```

3. Access the application / เข้าใช้งานแอปพลิเคชัน
```
http://localhost:8080
```

4. Default login credentials / ข้อมูลเข้าสู่ระบบเริ่มต้น
```
Username: admin
Password: admin123
```

### Manual Installation (without Docker) / การติดตั้งแบบปกติ (ไม่ใช้ Docker)

1. Setup web server (Apache/Nginx) with PHP 8.1+
2. Create MySQL database
3. Import database schema from `database/schema.sql`
4. Configure database connection in `config/database.php`
5. Set web root to `/public` directory

## 📁 Project Structure / โครงสร้างโปรเจ็กต์

```
pdpa-assessment-system/
├── config/              # Configuration files / ไฟล์การตั้งค่า
├── database/            # Database schema and migrations / ฐานข้อมูลและสคีมา
├── public/              # Public web root / โฟลเดอร์หลักเว็บ
│   ├── css/            # Stylesheets / ไฟล์สไตล์
│   ├── js/             # JavaScript files / ไฟล์จาวาสคริปต์
│   └── index.php       # Main entry point / จุดเริ่มต้นหลัก
├── src/                 # Application source code / โค้ดแอปพลิเคชัน
│   ├── controllers/    # Controllers / คอนโทรลเลอร์
│   ├── models/         # Models / โมเดล
│   └── views/          # Views / วิว
├── docker-compose.yml   # Docker Compose configuration
├── Dockerfile           # Docker image configuration
└── README.md           # This file / ไฟล์นี้
```

## 📚 Usage / การใช้งาน

### For Administrators / สำหรับผู้ดูแลระบบ

1. Login with admin credentials / เข้าสู่ระบบด้วยข้อมูลผู้ดูแล
2. Manage users and organizations / จัดการผู้ใช้และองค์กร
3. View all assessment results / ดูผลการประเมินทั้งหมด
4. Generate reports / สร้างรายงาน

### For Users / สำหรับผู้ใช้ทั่วไป

1. Register and login / ลงทะเบียนและเข้าสู่ระบบ
2. Complete PDPA assessment questionnaire / ทำแบบประเมิน PDPA
3. Complete Cyber law assessment / ทำแบบประเมินกฎหมายไซเบอร์
4. View assessment results and recommendations / ดูผลการประเมินและคำแนะนำ

## 🔒 Security / ความปลอดภัย

- Password hashing with bcrypt / เข้ารหัสรหัสผ่านด้วย bcrypt
- SQL injection prevention with PDO prepared statements / ป้องกัน SQL injection ด้วย PDO
- XSS protection / ป้องกัน XSS
- Session management / การจัดการเซสชัน

## 🤝 Contributing / การมีส่วนร่วม

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License / ใบอนุญาต

This project is licensed under the MIT License.

## 📧 Contact / ติดต่อ

For questions or support, please open an issue on GitHub.

## 🙏 Acknowledgments / กิตติกรรมประกาศ

- PDPA guidelines from Thailand's Personal Data Protection Committee
- Cybersecurity Act B.E. 2562 (2019)
- Critical Information Infrastructure Protection Act