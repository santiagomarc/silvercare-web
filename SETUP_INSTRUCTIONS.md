# SilverCare Web Application - Setup Instructions

## 📋 Project Overview
This is a Laravel 11 web application converted from our Flutter SilverCare mobile app for our Web Development final project. It uses Laravel Breeze for authentication, PostgreSQL for the database, and includes Google Fit integration.

**Tech Stack:**
- Laravel 11 (PHP 8.2+)
- PostgreSQL 17
- Tailwind CSS
- Blade Templates
- Laravel Breeze (Authentication)
- Google OAuth (via Socialite)

---

## 🚀 Prerequisites

Before you begin, make sure you have these installed on your machine:

### 1. PHP 8.2 or higher
**Check if installed:**
```bash
php -v
```

**Download:** https://windows.php.net/download/ (choose Thread Safe version)

**Setup for Windows:**
- Extract to `C:\php`
- Add `C:\php` to your System PATH
- Copy `php.ini-development` to `php.ini`
- Enable these extensions in `php.ini` (remove the `;` before them):
  ```
  extension=pdo_pgsql
  extension=pgsql
  extension=mbstring
  extension=openssl
  extension=curl
  extension=fileinfo
  ```

### 2. Composer (PHP Package Manager)
**Check if installed:**
```bash
composer -V
```

**Download:** https://getcomposer.org/download/

### 3. Node.js and npm
**Check if installed:**
```bash
node -v
npm -v
```

**Download:** https://nodejs.org/ (LTS version)

### 4. PostgreSQL 17
**Check if installed:**
```bash
psql --version
```

**Download:** https://www.postgresql.org/download/windows/

**Important during installation:**
- Remember your postgres user password (you'll need this!)
- Default port: 5432
- Add PostgreSQL bin folder to PATH: `C:\Program Files\PostgreSQL\17\bin`

### 5. Git
**Check if installed:**
```bash
git --version
```

**Download:** https://git-scm.com/download/win

---



### Step 1: Create Repository on GitHub
1. Go to https://github.com/new
2. **Repository name:** `silvercare-web`
3. **Description:** "Laravel web version of SilverCare app - Web Development Final Project"
4. **Visibility:** Private (recommended for now)
5. **DO NOT** add README, .gitignore, or license (we already have them)
6. Click **"Create repository"**

### Step 2: Initialize and Push
```bash
# Navigate to your project folder
cd silvercare_web

# Initialize git (if not already done)
git init

# Add all files
git add .

# Create first commit
git commit -m "Initial commit: Laravel setup with PostgreSQL models and migrations"

# Rename branch to main
git branch -M main

# Link to your GitHub repo (replace YOUR_USERNAME with your actual GitHub username)
git remote add origin https://github.com/YOUR_USERNAME/silvercare-web.git

# Push to GitHub
git push -u origin main
```

## 📥 Installation Steps (For Teammates)

### Step 1: Clone the Repository
```bash
# Navigate to where you want the project
cd C:\Users\YourName\Desktop\code

# Clone the repository (get URL from team lead)
git clone https://github.com/santiagomarc/silvercare-web.git
cd silvercare-web
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

**If you get errors:**
- Make sure PHP extensions are enabled in `php.ini`
- Run `composer diagnose` to check for issues

### Step 3: Install Node.js Dependencies
```bash
npm install
```

### Step 4: Create Your Environment File
```bash
cp .env.example .env
```

### Step 5: Generate Application Key
```bash
php artisan key:generate
```

### Step 6: Configure Your Database

**Create the database:**
```bash
# Open PostgreSQL command line
psql -U postgres

# Inside psql, run:
CREATE DATABASE silvercare_db;

# Exit psql
\q
```

**Update your `.env` file:**
Open `.env` in your code editor and update these lines:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=silvercare_db
DB_USERNAME=postgres
DB_PASSWORD=your_postgres_password_here
```

**⚠️ IMPORTANT:** Replace `your_postgres_password_here` with the password you set during PostgreSQL installation.

### Step 7: Configure Google OAuth (Ask team lead for credentials)

In your `.env` file, the Google credentials are already included from `.env.example`:
```env
GOOGLE_CLIENT_ID=1025474256493-qq8plfr7t9csl0drg60b77dds1d73fr3.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Note:** These credentials are shared for our student project. 
### Step 8: Run Database Migrations
```bash
php artisan migrate
```

**You should see:** ✅ All 13 tables created successfully

**If you get errors:**
- Check your PostgreSQL password in `.env`
- Make sure `silvercare_db` database exists
- Verify PostgreSQL service is running: Services → postgresql-x64-17

### Step 9: Build Frontend Assets
```bash
npm run dev
```

Keep this terminal running! It watches for file changes.

### Step 10: Start the Development Server
**Open a NEW terminal** and run:
```bash
php artisan serve
```

**Access the app:** http://localhost:8000


---

## 🛠️ Common Issues & Solutions

### Issue: "could not find driver" error
**Solution:** Enable `pdo_pgsql` and `pgsql` extensions in `php.ini`

### Issue: PostgreSQL password authentication failed
**Solution:** 
- Double-check password in `.env` matches your PostgreSQL password
- Try connecting manually: `psql -U postgres -d silvercare_db`

### Issue: "npm run dev" not working
**Solution:**
- Delete `node_modules` folder
- Run `npm install` again
- Run `npm run dev`

### Issue: Port 8000 already in use
**Solution:**
```bash
php artisan serve --port=8001
```

### Issue: Migration errors "table already exists"
**Solution:**
```bash
php artisan migrate:fresh
```
⚠️ WARNING: This deletes all data!

