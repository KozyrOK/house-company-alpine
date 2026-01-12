<p align="center">
  <strong>🇬🇧 English</strong> |
  <a href="./README.ru.md">🇺🇦 Русский</a>
</p>

---

# Housing Company

**Housing Company** is a Laravel-based PET project.

The project focuses on practicing the development of a full-featured backend application with a role-based model, REST API, access policies, and a lightweight frontend built with Alpine.js.  
Theme: a web application for housing associations (OSMD) and their members.

## Project Goals

- Practice Laravel application architecture
- Implement a complex role model (multi-company, multi-role)
- Separate Web and API layers
- Use Sanctum for API authentication
- Apply Laravel Policies and SOLID principles
- Prepare the project for future scalability

## Technology Stack

**Backend**
- PHP 8.x
- Laravel 12
- Laravel Breeze
- Laravel Sanctum

**Frontend**
- Blade
- Alpine.js
- Vite

**Database**
- MySQL
- Eloquent ORM
- Migrations

## Domain Model

- User
- Company
- Post
- CompanyUser (pivot table with roles)

## User Roles

Role hierarchy:
1. superadmin
2. admin
3. company_head
4. user

Access control is implemented using Laravel Policies with company context awareness.

## Current Status

Implemented:
- Core architecture
- Authentication
- Companies, users, posts
- Role-based access model
- Web + API layers

Planned:
- Post moderation
- Admin panel
- Forum
- Chat-bot
- LLM integration (Laravel Prism)

## Installation

```bash
git clone https://github.com/KozyrOK/house-company-alpine.git
cd house-company-alpine

cp .env.example .env
./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
