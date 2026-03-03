# LIKIZOPRO - Enterprise Leave Management System

![LIKIZOPRO](https://via.placeholder.com/800x200/111D4A/3581B8?text=LIKIZOPRO)

A comprehensive Enterprise Leave Management System built with **CodeIgniter 4 (PHP)** for the backend, **MySQL** database, **Bootstrap 5** for the admin dashboard, and **Flutter** for the mobile application.

## 📋 Table of Contents

- [System Overview](#system-overview)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [Installation Guide](#installation-guide)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [API Documentation](#api-documentation)
- [Mobile App](#mobile-app)
- [Default Credentials](#default-credentials)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## 🌟 System Overview

LIKIZOPRO is a complete enterprise-grade leave management solution that digitizes the entire leave lifecycle:

```
Leave Request → Validation → Approval Workflow → Ledger Balance Updates → Reporting → Payroll Export
```

### User Roles

| Role | Description |
|------|-------------|
| Super Admin | Full system access |
| HR Manager | Complete HR and leave management |
| HR Officer | Leave management and reporting |
| Line Manager | Team approvals |
| Employee | Self-service leave requests |
| Payroll | Read-only leave data for payroll |
| Auditor | Read-only audit access |

---

## 🛠 Tech Stack

### Backend
- **Framework:** CodeIgniter 4 (PHP 8.1+)
- **Database:** MySQL 8.0+
- **Authentication:** JWT + Session-based
- **API:** RESTful JSON API

### Frontend (Web Admin)
- **UI Framework:** Bootstrap 5
- **Icons:** Bootstrap Icons
- **Fonts:** Plus Jakarta Sans
- **Theme:** Custom LIKIZOPRO brand

### Mobile App
- **Framework:** Flutter 3.x
- **State Management:** Provider
- **HTTP Client:** Dio
- **Storage:** SharedPreferences

---

## ✨ Features

### Core Features
- ✅ Leave request submission and tracking
- ✅ Multi-level approval workflow
- ✅ Ledger-based leave balance system
- ✅ Leave policy configuration by grade/location/contract
- ✅ Employee management
- ✅ Department and location management
- ✅ Work schedules and holidays
- ✅ Report builder with export (PDF/Excel/CSV)
- ✅ Audit logging for compliance

### Authentication & Security
- ✅ JWT-based API authentication
- ✅ Session-based web authentication
- ✅ Role-based access control (RBAC)
- ✅ CSRF protection
- ✅ Password hashing (bcrypt)

### Integration
- ✅ RESTful API for mobile app
- ✅ Consistent design system across web and mobile

---

## 📁 Project Structure

```
likizopro/
├── app/                          # CodeIgniter application
│   ├── Config/                   # Configuration files
│   ├── Controllers/               # MVC Controllers
│   │   ├── API/                  # REST API Controllers
│   │   └── *.php                 # Web Controllers
│   ├── Models/                   # Database Models
│   ├── Services/                 # Business Logic Services
│   ├── Filters/                 # Authentication Filters
│   └── Views/                    # HTML Views
│       ├── layouts/               # Layout templates
│       ├── auth/                 # Authentication views
│       ├── dashboard/             # Dashboard views
│       └── ...                   # Other module views
├── public/                       # Public assets
├── database/                      # Database files
│   ├── migrations/               # Database migrations
│   └── seeders/                 # Database seeders
└── flutter_app/                  # Flutter mobile app
    ├── lib/
    │   ├── api/                 # API integration
    │   ├── models/              # Data models
    │   ├── screens/              # App screens
    │   ├── services/             # App services
    │   └── utils/                # Utilities
    └── pubspec.yaml             # Flutter dependencies
```

---

## 🗄 Database Schema

### Core Tables

#### Authentication & RBAC
- `users` - User accounts
- `roles` - User roles
- `permissions` - System permissions
- `role_permissions` - Role-permission mapping

#### HR Structure
- `employees` - Employee records
- `departments` - Organizational departments
- `locations` - Office locations
- `job_grades` - Job grade levels
- `work_schedules` - Work schedule definitions
- `holidays` - Company holidays

#### Leave Management
- `leave_types` - Types of leave (Annual, Sick, etc.)
- `leave_policies` - Leave policy rules
- `leave_requests` - Leave applications
- `leave_request_days` - Daily breakdown
- `leave_transactions` - Ledger-based balance transactions
- `approval_actions` - Approval history
- `blackout_periods` - Blocked leave periods

#### Support Tables
- `attachments` - File uploads
- `delegations` - Approval delegations
- `audit_logs` - System audit trail
- `notifications` - User notifications
- `report_templates` - Saved report templates
- `export_runs` - Report export history

---

## 📦 Installation Guide

### Prerequisites

1. **Web Server:** Apache/Nginx with PHP 8.1+
2. **Database:** MySQL 8.0+
3. **Composer:** For PHP dependencies
4. **Flutter SDK:** For mobile app development

### Step 1: Clone the Project

```bash
cd /var/www/html
git clone https://github.com/your-repo/likizopro.git
cd likizopro
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Configure Environment

```bash
cp .env.example .env
```

Edit `.env` with your database credentials:

```env
database.default.hostname = localhost
database.default.username = root
database.default.password = your_password
database.default.database = likizopro
```

### Step 4: Create Database

```sql
CREATE DATABASE likizopro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 5: Run Migrations

```bash
php spark migrate
```

### Step 6: Seed Database

```bash
php spark db:seed DatabaseSeeder
```

### Step 7: Configure Web Server

#### Apache (.htaccess)
Ensure mod_rewrite is enabled and configure VirtualHost:

```apache
<VirtualHost *:80>
    ServerName likizopro.local
    DocumentRoot /var/www/html/likizopro/public
    
    <Directory /var/www/html/likizopro/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name likizopro.local;
    root /var/www/html/likizopro/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## ⚙ Configuration

### JWT Configuration

Edit `app/Config/Auth.php`:

```php
public $jwtKey = 'your_secure_jwt_key_change_in_production';
public $jwtAlgorithm = 'HS256';
public $jwtExpiry = 86400; // 24 hours in seconds
```

### Application Settings

Edit `app/Config/App.php`:

```php
public $baseURL = 'http://likizopro.local/';
public $appTimezone = 'Africa/Dar_es_Salaam';
```

---

## 🚀 Running the Application

### Start Development Server

```bash
php spark serve --host=0.0.0.0 --port=8080
```

Access the admin dashboard at: `http://localhost:8080`

### Mobile App Setup

```bash
cd flutter_app
flutter pub get
flutter run
```

---

## 📡 API Documentation

### Authentication

#### POST /api/auth/login
```bash
curl -X POST http://likizopro.local/api/auth/login \
  -d "email=admin@likizopro.co.tz" \
  -d "password=123456"
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhb...",
    "refresh_token": "eyJ0eXAiOiJKV1QiLCJhb...",
    "user": {
      "id": 1,
      "email": "admin@likizopro.co.tz",
      "role_id": 1,
      "employee_id": 1,
      "first_name": "John",
      "last_name": "Mwaisekwa"
    }
  }
}
```

#### POST /api/auth/refresh
```bash
curl -X POST http://likizopro.local/api/auth/refresh \
  -d "refresh_token=your_refresh_token"
```

### Leave Requests

#### GET /api/leave-requests
```bash
curl -X GET http://likizopro.local/api/leave-requests \
  -H "Authorization: Bearer YOUR_TOKEN"
```

#### POST /api/leave-requests
```bash
curl -X POST http://likizopro.local/api/leave-requests \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d "leave_type_id=1" \
  -d "start_date=2026-03-15" \
  -d "end_date=2026-03-20" \
  -d "reason=Annual leave for vacation"
```

### Leave Balances

#### GET /api/leave-balances
```bash
curl -X GET http://likizopro.local/api/leave-balances \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 📱 Mobile App

### Features
- Login with JWT authentication
- Dashboard with leave balances
- Apply for leave
- View leave request status
- Manager approvals
- Profile management

### Build for Production

```bash
cd flutter_app

# Android
flutter build apk --release

# iOS
flutter build ios --release
```

---

## 🔑 Default Credentials

| Field | Value |
|-------|-------|
| Email | admin@likizopro.co.tz |
| Password | 123456 |
| Role | Super Admin |

---

## 🔧 Troubleshooting

### Common Issues

#### 1. Database Connection Error
```
Unable to connect to the database
```
**Solution:** Check your `.env` database credentials and ensure MySQL is running.

#### 2. Migration Failed
```
Table 'users' already exists
```
**Solution:** Drop all tables and run migrations fresh:
```bash
php spark migrate:refresh
```

#### 3. JWT Token Expired
```
Invalid or expired token
```
**Solution:** Use the refresh token endpoint to get a new access token.

#### 4. Permission Denied
```
Access denied to this page
```
**Solution:** Check that your user role has the required permission.

#### 5. File Upload Issues
```
The upload path does not appear to be valid
```
**Solution:** Ensure the `writable/uploads` directory exists and has proper permissions:
```bash
mkdir -p writable/uploads
chmod -R 777 writable
```

### Cron Job for Accrual

Set up a monthly cron job for leave accrual:

```bash
# Run on 1st of each month at 2 AM
0 2 1 * * cd /var/www/html/likizopro && php spark leave:accrue
```

---

## 📄 License

Copyright © 2026 LIKIZOPRO. All rights reserved.

---

## 📞 Support

For issues and questions:
- Email: support@likizopro.co.tz
- Documentation: https://docs.likizopro.co.tz

---

**Built with ❤️ using CodeIgniter 4 + Bootstrap 5 + Flutter**
