<p align="center">
  <a href="./README.en.md">🇬🇧 English</a> |
  <strong>🇺🇦 Русский</strong>
</p>

---

# Housing Company

**Housing Company** — PET-проект на Laravel, посвящённый разработке веб-приложения для объединений совладельцев многоквартирных домов и их участников.

Проект ориентирован на практику построения полноценного backend-приложения с ролевой моделью, REST API, политиками доступа и лёгким frontend на Blade Alpine.js.

## Цели проекта

- Практика архитектуры Laravel-приложений
- Реализация сложной ролевой модели (multi-company, multi-role)
- Разделение Web и API слоёв
- Использование Sanctum для аутентификации и защищённого API
- Применение Policies, middleware и сервисного слоя
- Подготовка проекта к дальнейшему расширению функционала
- Освоение AI-возможностей Laravel в прикладном сценарии

## Технологический стек

**Backend**
- Laravel 13
- Laravel Breeze
- Laravel Sanctum
- Laravel AI SDK

**Frontend**
- Blade
- Alpine.js
- Vite

**База данных**
- MySQL
- Eloquent ORM
- Laravel Migrations / Seeders

**Infrastructure / Dev Environment**
- Docker
- Laravel Sail

## Доменная модель

- User
- Company
- Post
- CompanyUser (pivot-таблица с ролями)

## Роли пользователей

Иерархия ролей:
1. superadmin
2. admin
3. company_head
4. user

Контроль доступа реализован через Laravel Policies.

## Текущий статус:

На текущем этапе в проекте реализованы:

- базовая архитектура приложения
- аутентификация и базовые сидеры
- сущности компаний, пользователей и постов
- ролевая модель доступа
- разделение Web и API слоёв
- базовый frontend на Blade / Alpine.js
- локализация интерфейса
- поддержка светлой и тёмной темы

## Планируемый функционал:

- AI-чат внутри приложения на базе Laravel AI SDK
- AI-помощник пользователя по тематике приложения

## 🚀 Развертывание проекта:

Проект полностью контейнеризирован и запускается через Docker.
Локальная установка PHP, Composer, Node.js и MySQL **не требуется**.

### Для развертывания проекта, необходимы:
* Docker
* Docker Compose
* Git

### 1. Клонирование репозитория

***Клонируем репозиторий***
```bash
git clone https://github.com/KozyrOK/house-company-alpine.git
```
**Переходим в директорию проекта**
```bash
cd house-company-alpine
```

### 2. Настройка окружения

**Создайте файл окружения на основе примера (при необходимости отредактируйте .env (порты, доступ к БД и т.д.)):**
```bash
cp .env.example .env
```

### 3. Сборка и запуск контейнеров

```bash
docker compose up -d --build
```
**После выполнения команда запустит все сервисы проекта в фоновом режиме.**

### 4. Установка зависимостей

```bash
docker compose exec laravel.test composer install
docker compose exec laravel.test npm install
```

### 5. Инициализация приложения

**Сгенерировать ключ приложения:**
```bash
docker compose exec laravel.test php artisan key:generate
```

**Применить миграции базы данных:**
```bash
docker compose exec laravel.test php artisan migrate
```

### 6. Дополнительное заполнение базы данных

**Заполните базу данных минимальным начальным набором данных:**
```bash
docker compose exec laravel.test php artisan db:seed
```
**Заполните базу данных расширенными тестовыми данными для локальной разработки:**
```bash
docker compose exec laravel.test php artisan db:seed --class=DatabaseSeederTest
```

### 7. Запуск frontend

**Для режима разработки (Vite dev server):**
```bash
docker compose exec laravel.test npm run dev
```
**Для production-сборки:**
```bash
docker compose exec laravel.test npm run build
```

### 📌 Результат

После выполнения всех шагов приложение будет доступно по адресу: **http://localhost:8080**

## Полезные команды

**Остановка контейнеров:**
```bash
docker compose down
```
**Удаление контейнеров вместе с volumes:**
```bash
docker compose down -v
```
**Просмотр логов:**
```bash 
docker compose logs -f
```
**Вход в контейнер приложения:**
```bash
docker compose exec laravel.test sh
```

### ⚙️ Примечания:
* Все команды выполняются внутри Docker-контейнеров.
* Проект не зависит от локального окружения разработчика.
* При первом запуске последовательность шагов (1–7) обязательна.

### При последующих запусках достаточно выполнить только:
```bash
docker compose up -d
```
