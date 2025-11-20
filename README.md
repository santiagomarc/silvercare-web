# 🏥 SilverCare Web Application

> **Laravel web version of our Flutter SilverCare app - Web Development Final Project**

A health management platform designed to help elderly users track medications, monitor health vitals, manage appointments, and connect with caregivers.

---

## 📋 Project Overview

**Original App:** Flutter mobile application (SilverCare)  
**Web Version:** Laravel 11 + PostgreSQL + Tailwind CSS  
**Timeline:** 4 weeks (November 18 - December 15, 2025)  
**Team:** 4 developers

### Core Features

✅ **Medication Management**
- Track medication schedules (recurring and one-time)
- Dose completion logging with late/missed detection
- Email reminders for upcoming doses

✅ **Health Monitoring**
- Record vitals: heart rate, blood pressure, blood sugar, temperature, weight
- Track mood and wellness
- Visualize trends with Chart.js
- Export reports to PDF

✅ **Google Fit Integration**
- OAuth authentication
- Sync steps, calories, sleep data
- Display alongside manual health entries

✅ **Caregiver Dashboard**
- Link caregivers to elderly users (one-to-one relationship)
- Monitor all assigned elderly users' medications and health alerts
- Activity feed and notifications

✅ **Daily Management**
- Calendar for appointments
- Daily checklists for tasks
- Notification history

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | Laravel 11 (PHP 8.2+) |
| **Frontend** | Blade Templates + Tailwind CSS |
| **Database** | PostgreSQL 17 |
| **Authentication** | Laravel Breeze |
| **OAuth** | Google (Socialite) |
| **Charts** | Chart.js |
| **PDF Export** | DomPDF |
| **Real-time** | Laravel Reverb |

---

## 🚀 Quick Start

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- PostgreSQL 17
- Git

### Installation

```bash
# Clone repository
git clone https://github.com/santiagomarc/silvercare-web.git
cd silvercare-web

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_PASSWORD=your_postgres_password

# Create database
psql -U postgres -c "CREATE DATABASE silvercare_db;"

# Run migrations
php artisan migrate

# Start development servers
npm run dev          # Terminal 1
php artisan serve    # Terminal 2
```

**Access:** http://localhost:8000

📖 **Detailed setup instructions:** See [`SETUP_INSTRUCTIONS.md`](SETUP_INSTRUCTIONS.md)

---

## 📁 Project Structure

```
silvercare_web/
├── app/
│   ├── Http/Controllers/      # Route handlers
│   ├── Models/               # 8 Eloquent models with relationships
│   │   ├── User.php
│   │   ├── UserProfile.php   # Elderly/Caregiver profiles
│   │   ├── Medication.php    # Medication schedules
│   │   ├── MedicationLog.php # Dose tracking
│   │   ├── HealthMetric.php  # All health vitals
│   │   ├── CalendarEvent.php
│   │   ├── Checklist.php
│   │   ├── Notification.php
│   │   └── GoogleFitToken.php # Encrypted OAuth tokens
│   └── Services/             # Business logic (to be created)
├── database/
│   └── migrations/           # 9 migrations (13 tables total)
├── resources/
│   ├── views/               # Blade templates
│   └── js/                  # Frontend assets
├── routes/
│   └── web.php              # Application routes
└── .env                     # Local config (NOT in git)
```

---

## 🗃️ Database Schema

### Main Tables

1. **users** - Authentication (Laravel Breeze)
2. **user_profiles** - Extended user data (user_type: elderly/caregiver)
3. **medications** - Medication schedules with JSON scheduling
4. **medication_logs** - Dose completion records
5. **health_metrics** - Unified vitals storage (9 types)
6. **calendar_events** - Appointments
7. **checklists** - Daily tasks
8. **caregiver_elderly** - Many-to-many pivot table
9. **notifications** - Activity feed with severity levels
10. **google_fit_tokens** - Encrypted OAuth tokens

---

## 📄 License

This project is for educational purposes (Web Development Final Project).

---

**Last Updated:** November 2025  
