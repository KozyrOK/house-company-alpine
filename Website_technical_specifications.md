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

\- главная страница должна быть с авторизацией, системой восстановления пароля и регистрацией (Laravel Breeze).

\- удаление любой сущности должно сопровождаться окном подтверждения действия (удаление любой сущности являеться мягким, т.е. не окончательным).

\- серверный фильтр (server-side filtering) с UI-панелью при отобращении отдельных сущностей приложения для их фильтрации, поиск по имени сущности.

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

Иерархия ролей (от с самыми большими правами до самых маленьких прав): superadmin (один user на все приложение), admin, company\_head, user.
Каждый user может иметь отношение к одной или многим компаниям. В каждой компании user может иметь разные роли (кроми роли superadmin).

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

 В хедере доступен topbar со ссылками на страницы в зависимости от role:

*Superadmin* 

/admin – Отображение трех боксов-ссылок на страницы с перечнем ВСЕХ Companies, Users and Posts соответсвенно (/admin/users, admin/companies, admin/posts)
/dashboard – личный кабинет (профиль, настройки пользователя). Отображение данных пользователя (show). Кнопки Edit (update). 
/chat – чат-бот.
/forum – форум.
/info – общая информация о PET-проекте.

*Admin* (если у User есть хоть одна Company, где у него роль - admin. Если у User  )

/admin – Отображение трех боксов-ссылок на страницы Companies (где user имеет роль admin), Users (в отношении Users в Companies, где user имеет роль admin) and Posts (в отношении Posts в Companies, где user имеет роль admin) соответсвенно (/admin/users, admin/companies, admin/posts)
/main - отображения перечня Companies, Users and Posts к которой имеет отношение авторизованный User (имеет роли user, company_head).
/chat – чат-бот.
/forum – форум.
/info – общая информация о PET-проекте.

Company_head, user*

/main - отображения перечня Companies, Users and Posts к которой имеет отношение авторизованный User (имеет роли user, company_head).
/chat – чат-бот.
/forum – форум.
/info – общая информация о PET-проекте.

**Правила отображения /main в зависимости от User (его ролей, участия в Companies):**

- User - участник только одной Company с ролью user -> переадресация на список Posts в этой компании с правами простого User.
- User - участник только одной Company с ролью company_user -> отображение двух боксов-ссылок на страницы Users (перечень user в этой Company) and Posts (перечень Posts в этой Company).
- User - участник нескольких Company -> отображение трех боксов-ссылок на страницы Companies, Users и Posts (где User имеет роли company_head, user).

**Описание отображение списка любой сущности для Superadmin (отображение элементов для других ролей зависит от наличии прав доступа для функционала, которые зависит от конкретного элемента)** 

1 ряд (ориентация по центру): Заголовок сущности
2 ряд: (ориентация по бокам) Кнопка Back, Create New Company (User, Post)
3 ряд: Filter (на весь ряд) - для отображения только части сущностей по определенным критериям
4 ряд (): поиск по названию (Name)
5 ряд (и далее): таблица с сущностями:
Companies: ряды - заголовок и данные Company, строки - #, Name, City, Actions (кнопка Detail) 
Users: ряды - заголовок и данные User, строки - #, Name (full name), Email, Status,	Actions (кнопка Detail).
Posts: ряды - заголовок и данные Posts, строки - #,	Title, Company,	Author, Status,	Date, Actions (кнопка Detail).

**Описание отображения любой сущности для Superadmin после нажатия кнопки Detail в списке**

!! У Users с другими ролями отображение элементов интерфейса зависит от прав доступа (к конкретному ресурсу и действий в отношении этого ресурса).

Companies:

1 ряд (ориентация по центру): Заголовок сущности
2 ряд (ориентация по бокам, лого по центру): Кнопка Back to list, Company Logo, Admin Menu (Main menu)
3 ряд и далее (таблица): поля и значения Company (ID, Name, Address, City, Description)
последний ряд (ориентация по бокам): Кнопка Edit Company, Кнопка Delete Company.

Users:

1 ряд (ориентация по центру): Заголовок сущности
2 ряд (ориентация по бокам, userpic): Кнопка Back to list, Userpic, Admin Menu (Main menu)
3 ряд и далее (таблица): поля и значения Company (ID, Name (full name), Email, Phone, Status)
последний ряд (ориентация по бокам): Кнопка Edit User, Кнопка Delete User.

Posts:

1 ряд (ориентация по центру): Заголовок сущности
2 ряд (ориентация по бокам, userpic): Кнопка Back to list, Userpic (автора поста), Admin Menu (Main menu)
3 ряд и далее (таблица): поля и значения Company (ID, Title, Company, Status, Content)
последний ряд (ориентация по бокам): Кнопка Post, Кнопка Delete Post.

**Описание Filter для каждой сущности (Filter - компонент)**

Filter имеет практически одинаковую реализацию, но отличаеться в зависимости от прав доступа и критериев фильтрации:

Companies: City (выпадающий список уже существующих городов)
Users: Company, Status (выпадающий список). 
Posts: Company, Status (выпадающий список).

Поверх картинки хедера \- вверху справа для всех авторизированных Users:

* `/log out` – страница выхода из авторизации  
* `locale` – локаль (переключатель языка)   
* `dark mode switch` – переключатель темы (без отдельного маршрута).
