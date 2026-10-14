# Jajal Medical Case Management System

> **Software Developer Intern Assignment Project**  
> Built with **Laravel 12, PHP 8.2+, MySQL, Laravel Blade, and Laravel Sanctum**.

---

## 🔑 Demo Credentials

| Role | Email | Password | Permissions |
|---|---|---|---|
| **Admin** | `admin@jajal.com` | `password` | Full CRUD: View, create, edit, update status, delete cases |
| **Viewer** | `viewer@jajal.com` | `password` | Read-only: View dashboard, browse cases, view case details |

*Note: All patient records use de-identified synthetic dummy data (e.g., `PT-71042`). No real Protected Health Information (PHI) is stored.*

---

## 🚀 Quick Setup Instructions

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Migrate and seed database
php artisan migrate:fresh --seed

# 4. Start local development server
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🧪 Automated Testing

```bash
php artisan test
```

Verifies:
- Valid and invalid login authentication
- Guest route & API protection
- Admin case creation
- Case number uniqueness rejection
- Viewer policy restriction (HTTP 403)
- Sanctum API bearer token permissions

---

## 🐙 Push to GitHub

```bash
git init
git add .
git commit -m "feat: complete Jajal Medical Case Management System"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/jajal-medical.git
git push -u origin main
```
