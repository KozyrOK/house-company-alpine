# Technical Specification for Web Application “Housing Company”

---

## 1. Project Name and Description

The “Housing Company” project is a PET project and represents a web application designed for associations of co-owners (Users) of apartment buildings (condominiums). Each association (Company entity) has its own dedicated section for publishing posts (Posts).
The main goal of the project is to practice the full lifecycle of web application development. The implementation should follow modern development principles.

---

## 2. Technology Stack

### Environment: \- Laravel Sail, Ubuntu
### Backend: \- Laravel 13
### Frontend: \- Blade, Alpane.js, Vite.
### Database: \- MySql
### VCS: \- GitHub
### IDE: \- PHPStorm
### Other: Laravel 13 AI SDK, Laravel Breeze, Sanctum.

The application must be developed and run in containers.
The developer’s local environment (PHP, Node.js, Composer, MySQL) must not be required to run the project.
It is allowed to use Laravel Sail as a base container solution or a custom Docker configuration.

---

## 3\. Feature List (Epics):

\- AI-powered chat (Laravel 13 AI SDK).

\- The application must have a public page (`/info`) available for unauthorized and authorized users.

\- Main page must include: authentication, password reset, registration (Laravel Breeze).

\- Server-side filtering with UI panel: filtering entities, search by entity name.

\- Social authentication (Google, X, Facebook) via Laravel Socialite.

\- Multi-language support: English, Russian, Ukrainian.

\- Dark mode: auto-detection for guests, toggle in header, persisted per user.

\- Approval workflow for Posts: posts created by `user` role must have `pending` status, approval required by `company_head`, `admin`, or `superadmin`.

\- Any delete action must include confirmation. All deletions are soft deletes.

---

## 4\. Entities (Models / CRUD Resources)

### 1\. User

A User can belong to one or multiple Companies and can have different roles in each:
- admin
- company_head
- user

A User with role `superadmin` belongs to all Companies.

### 2\. Company

Within a Company, Users can create Posts.

### 3\. Post

A Post is an informational message within a Company (text + optional image).

### 4\. company\_user (pivot table)

User:
- can belong to multiple Companies,
- can have different roles per Company,
- except `superadmin` (global role).

---

**Database Schema:**

**\- users table:** 

* “id”;   
* 'first\_name';   
* 'second\_name';   
* 'email' (unique);   
* 'password' (nullable);    
* 'google\_id' (nullable);   
* 'facebook\_id' (nullable);    
* 'x\_id' (nullable);    
* 'avatar\_path' (nullable);  
* 'phone' (nullable);
* foreignId('deleted_by').

**\- companies table:** 

* ‘id’;    
* 'name';  
* 'address';  
*  'city';   
* 'description' (nullable);
* foreignId('deleted_by').

**\- posts table:** 

* 'id';   
* 'company\_id' (foreignId 'companies', constrained('companies'));     
* 'user\_id'  (foreignId 'user');   
* 'title';   
* 'content';   
* 'image\_path' (nullable);   
* 'status' (enum\['draft', 'future', 'pending', 'publish', 'trash'\]), default('draft');  
* foreignId('created\_by')-\>constrained('users');   
* foreignId('updated\_by')-\>constrained('users');   
* foreignId('deleted\_by')-\>constrained('users').

**\- company_user table:** 

* 'user\_id' (constrained()-\>onDelete('cascade');   
* foreignId ('company\_id')-\>constrained()-\>onDelete('cascade');   
* enum('role', \['superadmin', 'admin', 'company\_head', 'user'\]);   
* unique(\['user\_id', 'company\_id'\]).

---

## 5\. Roles and Access Control

**Role hierarchy (highest to lowest):** 
* superadmin (single user in system) 
* admin
* company\_head 
* user

A User can belong to one or multiple Companies and have different roles per Company.

---

## Resource tables with user roles and access rights:

### Resource: User

| Action | superadmin | admin | company_head | user|
|---|---|---|---|-----------------------------------------------------|
| index | allow | allow: own company | allow: own company | allow: own company|
| show | allow | allow: own company | allow: own company  | allow: own company|
| store | allow | allow: own company | allow: own company, requires approval by higher role| allow, own profile only, requires approval by admin |
| update | allow | allow: own company | allow: own company, requires approval by higher role | allow, own profile only, requires approval by admin |
| delete | allow | allow: own company | allow: own company, requires approval by higher role | allow, own profile only, requires approval by admin |

### Resource: Company

| Action | superadmin | admin | company_head | user|
|--------|---|-------|--------------|-----|
| index  | allow | allow: own company | allow: own company | allow: own company |
| store  | allow | deny  | deny | deny |
| show | allow | allow: own company | allow: own company | allow: own company |
| update | allow | allow: own company | deny | deny |
| delete | allow | deny  | deny | deny |

### Resource: Post

| Action | superadmin | admin | company_head  | user |
|--------|---|--------------------|------------------------------------------------------|--------------------|
| index | allow | allow: own company | allow: own company                                   | allow: own company |
| store | allow | allow: own company | allow: own post on own company                       | allow: own post on own company, requires approval by higher role |
| show | allow | allow: own company | allow: own company                                   | allow: own company |
| update | allow | allow: own company | allow: own company, requires approval by higher role | allow: own post on own company, requires approval by higher role |
| delete | allow | allow: own company | allow: own company, requires approval by higher role | allow: own post on own company, requires approval by higher role |
 
---

## 6\. Frontend

### Frontend is built using Alpine.js with a classic layout:
- header
- footer

#### Header includes:
- full-width image (pattern background),
- navigation menu below,
- top-left: company logo, company switcher,
- top-right controls: login/logout, user avatar, locale switcher, dark mode toggle

---

### Guest View Header

#### Top-left header:
- default company logo

#### Top-center header:
- `/info` — public project information page

#### Top-right controls:
- `/login`
- locale switch
- dark mode toggle

---

### Authenticated Users View Header

#### Top-left header:
- company logo (current company logo or default company logo)
- company switcher (drop-down list of companies in which the authorized user participates)

#### Top-right controls:
- `/logout`
- avatar
- locale switch
- dark mode toggle

#### Top-center header for superadmin

- `/admin` — full access to all Companies, Users, Posts
- `/dashboard` — profile management
- `/chat` — AI assistant
- `/info` — project info

#### Top-center header for admin

- `/admin` — access to related Companies, Users, Posts in own companies
- `/company` - access to current company
- `/chat`
- `/info`

#### Top-center header for company_head / user

- `/main` — access to related Users, Posts in current company
- `/company` - access to current company
- `/chat`
- `/info`

---

### Rules for `/main`

- If User belongs to one Company as `user` → redirect to Company Posts
- If User belongs to one Company as `company_head` → show Users + Posts
- If User belongs to multiple Companies → show Companies + Users + Posts

---

### Entity List View (Superadmin)

Structure:

1. Title (centered)
2. Actions row:
    - Back
    - Create
3. Filter panel
4. Search (by name)
5. Table

Tables:

- Companies: #, Name, City, Actions
- Users: #, Name, Email, Status, Actions
- Posts: #, Title, Company, Author, Status, Date, Actions

---

### Entity Detail View

Includes:
- Back button
- Entity data
- Edit/Delete actions

---

### Filters

- Companies → filter by City
- Users → filter by Company, Status
- Posts → filter by Company, Status

---

## 7. Deployment Requirements

### Core Requirements

- Application must run only via Docker
- Services:
    - PHP/Laravel
    - Web server
    - MySQL
    - Node/Vite

- No dependency on local environment

---

### Repository Requirements

- Dockerfile(s)
- docker-compose.yml
- .env.example
- README.md with full setup guide

---

### First Run

1. Clone repository
2. Copy `.env`
3. Build and run containers
4. Generate APP_KEY
5. Run migrations
6. Seed database (optional)
7. Run frontend build/dev server

---

### Environment Independence

Project must NOT require:

- local PHP
- Composer
- Node.js / npm
- MySQL

All commands must be executed via Docker.

---
