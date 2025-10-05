# PDPA Assessment System - Installation Guide
# คู่มือการติดตั้งระบบประเมิน PDPA

## Quick Start with Docker / เริ่มต้นใช้งานด้วย Docker

### 1. Prerequisites / ข้อกำหนดเบื้องต้น
- Docker installed (version 20.10 or higher)
- Docker Compose installed (version 2.0 or higher)
- Git

### 2. Clone Repository / โคลนโปรเจ็กต์
```bash
git clone https://github.com/thering999/pdpa-assessment-system.git
cd pdpa-assessment-system
```

### 3. Start the Application / เริ่มใช้งานแอปพลิเคชัน
```bash
docker compose up -d
```

This will:
- Build the PHP/Apache container
- Start MySQL 8.0 database
- Initialize the database with schema
- Start phpMyAdmin for database management

### 4. Access the Application / เข้าใช้งานระบบ

**Main Application:**
```
URL: http://localhost:8080
Default Username: admin
Default Password: admin123
```

**phpMyAdmin (Database Management):**
```
URL: http://localhost:8081
Username: pdpa_user
Password: pdpa_pass
```

### 5. Stop the Application / หยุดแอปพลิเคชัน
```bash
docker compose down
```

### 6. Stop and Remove All Data / หยุดและลบข้อมูลทั้งหมด
```bash
docker compose down -v
```

## Manual Installation (Without Docker) / การติดตั้งแบบปกติ

### 1. Server Requirements / ข้อกำหนดเซิร์ฟเวอร์
- PHP 8.1 or higher
- MySQL 8.0 or higher
- Apache or Nginx web server
- PHP Extensions:
  - pdo
  - pdo_mysql
  - mysqli

### 2. Installation Steps / ขั้นตอนการติดตั้ง

#### Step 1: Clone Repository
```bash
git clone https://github.com/thering999/pdpa-assessment-system.git
cd pdpa-assessment-system
```

#### Step 2: Create Database
```sql
CREATE DATABASE pdpa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'pdpa_user'@'localhost' IDENTIFIED BY 'pdpa_pass';
GRANT ALL PRIVILEGES ON pdpa_db.* TO 'pdpa_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Step 3: Import Database Schema
```bash
mysql -u pdpa_user -p pdpa_db < database/schema.sql
```

#### Step 4: Configure Database Connection
Edit `config/database.php` if needed (default works with Docker):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'pdpa_db');
define('DB_USER', 'pdpa_user');
define('DB_PASS', 'pdpa_pass');
```

#### Step 5: Configure Web Server

**For Apache:**
- Set document root to `/path/to/pdpa-assessment-system/public`
- Ensure mod_rewrite is enabled
- The `.htaccess` file is already configured

**For Nginx:**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/pdpa-assessment-system/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

#### Step 6: Set Permissions
```bash
chmod -R 755 /path/to/pdpa-assessment-system
chown -R www-data:www-data /path/to/pdpa-assessment-system
```

#### Step 7: Access Application
Open browser and navigate to your configured domain or:
```
http://localhost
```

## Default Credentials / ข้อมูลเข้าสู่ระบบเริ่มต้น

```
Username: admin
Password: admin123
```

**IMPORTANT:** Change the admin password after first login!

## Troubleshooting / การแก้ไขปัญหา

### Database Connection Error
1. Check database credentials in `config/database.php`
2. Ensure MySQL service is running
3. Verify database exists: `SHOW DATABASES;`
4. Check user permissions: `SHOW GRANTS FOR 'pdpa_user'@'localhost';`

### Permission Denied Errors
```bash
chmod -R 755 /path/to/pdpa-assessment-system
chown -R www-data:www-data /path/to/pdpa-assessment-system
```

### Docker Port Already in Use
If ports 8080 or 3306 are already in use, edit `docker-compose.yml`:
```yaml
services:
  web:
    ports:
      - "8888:80"  # Change 8080 to 8888
  db:
    ports:
      - "3307:3306"  # Change 3306 to 3307
```

### View Docker Logs
```bash
# View all logs
docker compose logs

# View specific service logs
docker compose logs web
docker compose logs db

# Follow logs in real-time
docker compose logs -f
```

## Development / การพัฒนา

### Project Structure
```
pdpa-assessment-system/
├── config/              # Configuration files
│   ├── config.php      # Application config
│   └── database.php    # Database config
├── database/            # Database files
│   └── schema.sql      # Database schema
├── public/              # Web root (publicly accessible)
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript files
│   ├── index.php       # Login/Register page
│   ├── dashboard.php   # User dashboard
│   ├── assessment.php  # Assessment form
│   ├── result.php      # Assessment results
│   └── admin.php       # Admin panel
├── src/                 # Application source code
│   ├── controllers/    # Controllers
│   ├── models/         # Database models
│   └── views/          # View templates (future)
├── docker-compose.yml   # Docker Compose config
├── Dockerfile           # Docker image config
└── README.md           # Documentation
```

## Security Notes / หมายเหตุด้านความปลอดภัย

1. **Change default passwords** immediately after installation
2. **Use HTTPS** in production (configure SSL certificate)
3. **Regular backups** of database
4. **Keep PHP and MySQL updated** to latest stable versions
5. **Review and customize** security headers in `.htaccess`
6. **Set proper file permissions** (755 for directories, 644 for files)

## Support / การสนับสนุน

For issues, questions, or contributions:
- Open an issue on GitHub
- Submit a pull request
- Contact repository maintainer

## License / ใบอนุญาต

MIT License - see LICENSE file for details
