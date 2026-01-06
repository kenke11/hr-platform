# 🧑‍💼 HR Platform — Multi-tenant HR & Recruitment System

HR Platform is a **multi-tenant HR management and recruitment system** built with **Laravel 11**.  
It supports companies, employees, vacancies, candidates, attendance, vacations, and provides a **public API with Swagger documentation**.

This README explains **from scratch** how to run the project locally.

---

## 🚀 Tech Stack

- Laravel 11
- PHP 8.3+
- MySQL or SQLite
- Laravel Sanctum
- Spatie Roles & Permissions
- Swagger (OpenAPI 3)
- Blade + Tailwind CSS
- Vite

---

## 📁 Project Structure (short)

```
app/
 ├── Http/Controllers
 │   ├── Api
 │   ├── Auth
 │   └── Web
 ├── Models
 ├── Policies
 ├── Services
 ├── Swagger
database/
 ├── migrations
 ├── factories
 └── seeders
routes/
 ├── web.php
 └── api.php
```

## 📦 Features Overview

### 🏢 Companies
- Multi-tenant isolation
- CRUD operations
- Domain & slug support

### 👥 Employees
- Company-based employees
- Positions & hierarchy (manager → subordinates)
- CRUD with access control

### 💼 Vacancies
- Draft / Published
- Expiration date
- Company scoped
- Public visibility

### 🧑‍💻 Candidates
- Public vacancy applications
- CV upload
- Status pipeline:
    - `new`
    - `reviewed`
    - `shortlisted`
    - `rejected`

### ⏱ Attendance
- Daily check-in / check-out
- Absence marking
- Attendance history

### 🌴 Vacations
- Vacation requests
- Approve / Reject flow
- HR & Company Admin control

### 🌐 API
- Public API (vacancies & candidate apply)
- Swagger documentation
- File upload support

---

## ⚙️ Requirements

- PHP **8.3+**
- Composer
- Node.js **18+**
- npm or yarn
- MySQL or SQLite

---

## 🛠 Installation (From Scratch)

### 1️⃣ Clone the repository

```bash
git clone git@github.com:kenke11/hr-platform.git
cd hr-platform
```

### 2️⃣ Install backend dependencies

```bash
composer install
```

### 3️⃣ Install frontend dependencies

```bash
npm install
```

### 4️⃣ Environment configuration

```bash
cp .env.example .env
```

### 5️⃣ Generate application key

```bash
php artisan key:generate
```

### 6️⃣ Create database (SQLite only)

```bash
touch database/database.sqlite
```

### 7️⃣ Run migrations

```bash
php artisan migrate
```

### 8️⃣ Seed demo data

```bash
php artisan db:seed
```

#### This will create:

- demo companies
- admin / HR / company admin users
- employees & positions
- vacancies
- candidate applications
- attendance records

### 9️⃣ Storage symlink (CV files, uploads)

```bash
php artisan storage:link
```

### 🔟 Build frontend assets

```bash
npm run build
```

#### or for development:

```bash
npm run dev
```

### 1️⃣1️⃣ Start the server

```bash
php artisan serve
```

#### Open:

```bash
http://127.0.0.1:8000
```

---

### 👤 Demo Users (Seeder)

| Role              | Email            | Password   |
| ----------------- | ---------------- | ---------- |
| **Admin**         | `admin@app.com`  | `password` |
| **HR**            | `hr@app.com`     | `password` |
| **Company Admin** | `admin@demo.com` | `password` |
| **Employee** | ` user*@demo.com` | `password` |

> `user*@demo.com` — multiple demo employee accounts
> 
## 🔐 Roles & Permissions

- **Admin** – system-level access
- **HR** – system-level HR operations
- **Company Admin** – company-scoped management
- **Employee** – profile, attendance, vacations  

---

## 📌 Main Features

### Companies
- Create / edit / delete companies
- Multi-tenant isolation

### Employees
- Assign positions
- Manager → subordinate hierarchy
- CRUD operations with policies

### Vacancies
- Draft / published states
- Expiration dates
- Company scoped

### Candidates (Public API)
- Apply without authentication
- CV upload
- Status pipeline:
    - `new`
    - `reviewed`
    - `shortlisted`
    - `rejected`

### Attendance
- Daily check-in / check-out
- Absence marking (HR / Company Admin)
- Attendance history

### Vacations
- Vacation request flow
- Approve / reject
- HR & Company Admin approval

---

## 🌍 Public API

**Base URL**
```bash
/api/v1
```

**Public Endpoints**

```http
GET  /api/v1/public/vacancies
GET  /api/v1/public/vacancies/{id}
POST /api/v1/public/vacancies/{id}/apply
```

**POST /apply supports multipart/form-data CV upload supported**

## 📘 Swagger (API Documentation)

**Generate Swagger Docs**

```bash
php artisan l5-swagger:generate
```

**Swagger UI**

```http
http://127.0.0.1:8000/api/documentation
```

**How to Use Swagger**
- Open Swagger UI
- Select an endpoint
- Click Try it out
- Fill parameters / upload CV
- Click Execute
- See real API response
- Swagger is connected to the real backend — data is actually saved.
