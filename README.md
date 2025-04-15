# 💬 Laravel WireChat Integration

A real-time chat application built using **Laravel** and **WireChat** (Livewire based).  
Includes role-based access, email verification, and group chat permissions.

---

## 🚀 Features

- ✅ User authentication (Laravel Breeze / Fortify)
- ✅ Email verification
- ✅ One-on-one chat
- ✅ Group chat
- ✅ Role-based access (Admin, Agent, User)
- ✅ Chat & group creation permissions

---

## 📦 Requirements

- PHP >= 8.2
- Laravel >= 12
- MySQL / PostgreSQL / SQLite
- livewire & npm (for frontend assets)
- Composer

---

## 🛠️ Installation

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo

composer install
npm install && npm run dev

cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan db:seed --class=UserSeed

php artisan serve
