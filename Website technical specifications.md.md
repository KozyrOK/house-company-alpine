**Техническое задание для веб\-приложения** 

**“Housing Company”**

**1\. Название проекта и краткое описание**

Проект «Housing Company» является PET-проектом и представляет собой веб\-приложение с функционалом для объединений сособственников (Users) многоквартирных домов (ОСМД), где для каждого объединения (сущность Companies) будет создана отдельная часть для размещения постов (Posts).

Основная цель проекта \- научиться разрабатывать на практике все основные этапы веб\-приложения. Разработка должна соответствовать всем современным  методикам разработки (SOLID и т.д.)

**2\. Стек технологий**

Среда \- Laravel Sail, Ubuntu 23.4

Бекэнд \- Laravel 12

Фронтенд \- Alpane.js

База данных \- MySql

VCS \- GitHub

IDE \- PHPStorm

Другое: авторизация, токены \- Laravel Breeze, Sanctum.

**3\. Список фич (epics) — крупные блоки функциональности:**

\- при входе в приложение для unauthorized and authorized users (в хедере  должна быть ссылка на страницу с информацией об этом приложении (что реализовано) \- /info;

\- внедрение админки (редактирование страниц с текстовой информацией, подключение новых языков, работа в админке как в приложении с явными ресурсами) для superadmin (главная роль среди пользователей);

\-  главная страница должна быть с авторизацией, системой восстановления пароля и регистрацией (Laravel Breeze). 

Опционально: авторизация через  соц. сети (Google, X, Facebook) через Laravel **Socialite**;

\- веб\-приложение должно поддерживать несколько языков (английский, русский, украинский);

\- создание форума (отдельный раздел в хедере, готовый пакет Laravel-Forum, Vanilla Forums или Flarum);

\- создание чат-бота, добавление Laravel Prism (упрощает работу с большими языковыми моделями (LLM), такими как ChatGPT от OpenAI, Claude от Anthropic или Ollama);

\- создание темной темы (распознавание темы для неавторизованных пользователей для отображения по умолчанию), тумблер в хедере для включения темы (запоминание для каждого пользователя положения тумблера);

\- должен быть функционал одобрения для публикации Post со стороны company\_head, admin и superadmin. Любой новый пост от роли user имеет статус \- 'pending', до его одобрения вышестоящими ролями.

**4\. Сущности (модели, реализация как ресурсы через CRUD)**

На начальном этапе вижу три сущности в приложении и одна пивот таблица:

1\. **User**

Предполагается, что User (сущность, пользователь приложения) может относиться к одной или нескольким company, где он может иметь разные роли ('admin', 'company\_head', 'user').  User с ролью superadmin относиться ко всем company.

**2\. Company**

**В рамках company, user к которой он относиться, может публиковать посты (сущность post).**

**3\. Post**

	**Информационное сообщение в рамках company (текст, картинка)** 

**4\. company\_user \-** пивот таблица для отображения отношений между User и Company. User может относиться к многим **Company и иметь в каждой Company разные роли, кроме** superadmin. 

**Таблицы миграции для БД:**

	**\- user (таблица в БД “users”), поля:** 

* “id”;   
* 'first\_name';   
* 'second\_name';   
* 'email' (unique);   
* 'password' (nullable);    
* 'google\_id' (nullable);   
* 'facebook\_id' (nullable);    
* 'x\_id' (nullable);    
* 'image\_path' (nullable);  
* 'phone' (nullable).

**\- company (таблица в БД 'companies'),поля:** 

* ‘id’;    
* 'name';  
* 'address';  
*  'city';   
* 'description' (nullable).

**\- post (таблица в БД 'posts'), поля:** 

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

**\- company\_user (таблица в БД** '**company\_user**'**), поля:** 

* 'user\_id' (constrained()-\>onDelete('cascade');   
* foreignId ('company\_id')-\>constrained()-\>onDelete('cascade');   
* enum('role', \['superadmin', 'admin', 'company\_head', 'user'\]);   
* unique(\['user\_id', 'company\_id'\]).

**5\. Список ролей (и примеры поведения)**

 Таблица ролей пользователей (user) с указанием прав доступа к API:

(таблица routes не исчерпывающая. Необходим дополнительный функционал (роуты) для обеспечения одобрения действий над ресурсами со стороны вышестоящих ролей user)

Иерархия ролей (от с самыми большими правами до самых маленьких прав): superadmin, admin, company\_head, user.

\- GET /api/users index()  
superadmin: Yes
admin: Yes, but in own company only 
company\_head: Yes, but in own company only. (users with user, and company_head role)
user: Yes, but in own company only. (users with user, and company_head role)

\- POST /api/users store()
superadmin: Yes
admin: Yes, but in own company only 
company\_head: Yes, but in own company only,need approval by higher role
user: yes by own self,during registration,need approval by company admin  

\- GET /api/users/{user} show()
superadmin: Yes
admin: Yes, but in own company only 
company\_head: Yes, but in own company only(users with user, and company_head role) 
user: Yes, but in own company only(users with user, and company_head role) 

\- PUT /api/users/{user} update()
superadmin: Yes
admin: Yes, but in own company only(users with user, and company_head role) 
company\_head: Yes, but in own company only need approval by higher role users 
user: yes, but only own user info, need approval by higher role users
  

\- PATCH /api/users/{user} update()
superadmin: Yes
admin: Yes, but in own company only (users with user, and company_head role)
company\_head: Yes, but in own company only,need approval by higher role users
user: yes, only own user info, need approval by higher role users  


\- DELETE /api/users/{user} destroy()
superadmin: Yes
admin: Yes, but in own company only 
company\_head: No
user: No
  

\- GET /api/companies index
superadmin: Yes
admin: Yes, but in own company only
company\_head: No
user: No
 

\- POST /api/companies store()
superadmin: Yes
admin: No
company\_head: No
user: No
 

\- GET /api/companies/{company} show()
superadmin: Yes
admin: Yes, but in own company only
company\_head: Yes, but in own company only
user: Yes, but in own company only
 

\- PUT /api/companies/{company} update()
superadmin: Yes
admin: Yes, but in own company only, need approval by higher role users 
company\_head: No
user: No
 

\- PATCH /api/companies/{company} update()
superadmin: Yes
admin: Yes, but in own company only,need approval by higher role users
company\_head: No
user: No
 

\- DELETE /api/companies/{company} destroy()
superadmin: Yes
admin: No
company\_head: No
user: No
  

\- GET /api/posts index()
superadmin: Yes
admin: Yes, but in own company only
company\_head: Yes, but in own company only
user: Yes, but in own company only
 

\- POST /api/posts store()
superadmin: Yes
admin: Yes, but in own company only
company\_head: Yes, but in own company only
user: Yes, but in own company only,need approval by higher role users
 

\- GET /api/posts/{post} show()
superadmin: Yes
admin: Yes, but in own company only
company\_head: Yes, but in own company only
user: Yes, but in own company only
 

\- PUT /api/posts/{post} update()
superadmin: Yes
admin: Yes, but in own company only
company\_head: Yes, but in own company only where author post is user or company_head roles.
user: Yes,but  own post only, need approval by higher role users
 

\- PATCH /api/posts/{post} update()
superadmin: Yes
admin: Yes, but in own company only
company\_head: Yes, but in own company only. 
user: Yes, but own post only,need approval by higher role users
 

\- DELETE /api/posts/{post} destroy()
superadmin: Yes
admin: Yes, but in own company only
company\_head: Yes, but in own company only where author post is user or company_head roles.
user: Yes, but own post only,need approval by higher role user
  

**6\. Фронтенд**

Описание фронтенда ([Alpine.js](http://Alpine.js)) вместе с web маршрутами:

Фронтенд имеет классический вид с хедером и футером.

В хедере есть картинка на всю ширину (повторяющийся паттерн \- как в ориентировочном макете, который загружен в проект)  
Ниже картинки хедера- пункты меню хедера (нижеуказанные кнопки меню).  
Поверх картинки хедера \- вверху справа (`/login (in/out)` , `locale,dark mode switch` )

**Стартовая страница (гость)**

В хедере доступен topbar со ссылкой на стартовую страницу гостя (`/info),` информация о PET-проекте.

Поверх картинки хедера \- вверху справа:

* `/log in` – страница для авторизации  
* `locale` – локаль (переключатель языка)   
* `dark mode switch` – переключатель темы (без отдельного маршрута).

**После авторизации (для всех пользователей)**

 В хедере доступен topbar со ссылками на страницы:

`1. /main`  (отсутствует у **superadmin**)– отображения перечня Companies, Users and Posts к которой имеет отношение авторизованный User (имеет хоть какие-то роли). 

Если User имеет отношение только к одной Company и не является админом, то при заходе на вкладку main , сразу отражается только посты в рамках Company).

`2. /admin` – админка (доступна только для ролей **admin** и **superadmin**).   
У **superadmin** это отображение всех Companies, Users and Posts.   
У **admin** это вкладка доступна только в отношении тех Companies, где они **admin**

Если User имеет разные роли в разных компаниях (admin, company_head, user), то должны быть доступны вкладки /admin и /main для соответсвующих ресурсов.

`3. /dashboard` – личный кабинет (профиль, настройки пользователя).

`4. /chat` – чат-бот.

`5. /forum` – форум.

`6. /info` – общая информация о PET-проекте.

Поверх картинки хедера \- вверху справа:

* `/log out` – страница выхода из авторизации  
* `locale` – локаль (переключатель языка)   
* `dark mode switch` – переключатель темы (без отдельного маршрута).

**Дополнительно для админки (superadmin)**

* `/admin/posts` – управление постами (редактирование/удаление/модерация) c фильтром по companies, users, data, с функционалом CRUD.

* `/admin/companies` – управление компаниями (c фильтром), с функционалом CRUD.

* `/admin/users` – управление пользователями (c фильтром ), с функционалом CRUD.
