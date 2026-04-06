<p align="center">
  <strong>🇬🇧 English</strong> |
  <a href="./README.ru.md">🇺🇦 Русский</a>   
</p>

---

# Housing Company

**Housing Company** is a Laravel-based PET project focused on building a web application for homeowners associations and their members.

The project is aimed at practicing the development of a full-featured backend application with a role-based access model, REST API, access policies, and a lightweight frontend using Blade and Alpine.js.

## Project Goals

- Practice Laravel application architecture
- Implement a complex role model (multi-company, multi-role)
- Separate Web and API layers
- Use Sanctum for authentication and secure API access
- Apply Policies, middleware, and a service layer
- Prepare the project for future feature expansion
- Explore Laravel AI capabilities in a practical use case

## Tech Stack

**Backend**
- Laravel 13
- Laravel Breeze
- Laravel Sanctum
- Laravel AI SDK

**Frontend**
- Blade
- Alpine.js
- Vite

**Database**
- MySQL
- Eloquent ORM
- Laravel Migrations / Seeders

**Infrastructure / Dev Environment**
- Docker
- Laravel Sail

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

Access control is implemented using Laravel Policies.

## Current Status

At the current stage, the project includes:

- basic application architecture
- authentication and initial seeders
- companies, users, and posts entities
- role-based access model
- separation of Web and API layers
- basic frontend using Blade / Alpine.js
- interface localization
- light and dark theme support

## Planned Features

- AI chat inside the application powered by Laravel AI SDK
- AI assistant for users related to the application domain

## 🚀 Project Deployment

The project is fully containerized and runs via Docker.  
Local installation of PHP, Composer, Node.js, and MySQL is **not required**.

### Requirements

### To deploy the project, you need:
* Docker
* Docker Compose
* Git

### 1. Clone the repository

**Clone the repository:**
```bash
git clone https://github.com/KozyrOK/house-company-alpine.git
```
**Navigate to the project directory:**
```bash
cd house-company-alpine
```

### 2. Environment setup

**Create an environment file based on the example (edit .env if needed — ports, database access, etc.):**
```bash
cp .env.example .env
```

### 3. Build and start containers
```bash
docker compose up -d --build
```
**After execution, all project services will be started in the background.**

### 4. Install dependencies
```bash
docker compose exec laravel.test composer install
docker compose exec laravel.test npm install
```

### 5. Application initialization

**Generate application key:**
```bash
docker compose exec laravel.test php artisan key:generate
```
**Run database migrations:**
```bash
docker compose exec laravel.test php artisan migrate
```

### 6. Optional database seeding

**Seed the database with the minimal initial data set (production-safe):**
```bash
docker compose exec laravel.test php artisan db:seed
```
**Seed the database with extended test data for local development:**
```bash
docker compose exec laravel.test php artisan db:seed --class=DatabaseSeederTest
```

### 7. Frontend setup

**For development mode (Vite dev server):**
```bash
docker compose exec laravel.test npm run dev
```

**For production build:**
```bash
docker compose exec laravel.test npm run build
```

### 📌 Result

After completing all steps, the application will be available at: **http://localhost:8080**

## Useful commands

**Stop containers:**
```bash
docker compose down
```
**Remove containers with volumes:**
```bash
docker compose down -v
```
**View logs:**
```bash
docker compose logs -f
```
**Access the application container:**
```bash
docker compose exec laravel.test sh
```

### ⚙️ Notes:
* All commands are executed inside Docker containers.
* The project does not depend on the local development environment.
* For the first run, steps (1–7) are required.

### For subsequent runs, it is enough to execute:
```bash
docker compose up -d
```
