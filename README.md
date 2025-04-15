# 💬 Laravel WireChat Integration

A real-time chat application built using **Laravel** and **WireChat** (Livewire based).  
Includes role-based access, email verification, and group chat permissions.

---

## 🚀 Features

- ✅ User authentication (Laravel Breeze)
- ✅ Email verification
- ✅ One-on-one chat
- ✅ Group chat
- ✅ Role-based access (Admin, User)
- ✅ Chat & group creation permissions

---

## 📦 Requirements

- PHP >= 8.2
- Laravel >= 12
- MySQL 
- livewire & npm 
- Composer

---

## 🛠️ Installation

```bash
git clone https://github.com/Vishal386-code/project-attendance.git
cd project-attendance

composer install
npm install && npm run dev

cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan db:seed --class=UserSeed

php artisan serve
