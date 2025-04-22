# Blog API

Современный RESTful API для блог-платформы, построенный на Laravel с использованием передовых практик и технологий.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)
![Redis](https://img.shields.io/badge/Redis-latest-red.svg)
![JWT](https://img.shields.io/badge/JWT-Auth-green.svg)

## 🚀 Возможности

- 🔐 JWT аутентификация
- 📝 CRUD операции для постов
- 🏷️ Категории и теги для постов
- 🔍 Фильтрация и поиск постов
- 📊 Пагинация результатов
- 💾 Кеширование с использованием Redis
- 🔄 Автоматическая инвалидация кеша
- ✅ Полное покрытие тестами

## 📋 Требования

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Redis >= 6.0
- Laravel 10.x

## 🛠 Установка

1. Клонируйте репозиторий:
```bash
git clone https://github.com/tomoe1337/BlogAPI
cd BlogAPI
```

2. Установите зависимости:
```bash
composer install
```

3. Настройте окружение:
```bash
cp .env.example .env
php artisan key:generate
```

4. Настройте подключение к базе данных в `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_api
DB_USERNAME=root
DB_PASSWORD=
```

5. Настройте Redis в `.env`:
```
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

6. Выполните миграции:
```bash
php artisan migrate
```

7. Запустите сервер:
```bash
php artisan serve
```

## 📚 API Документация

### Аутентификация

#### Регистрация
```http
POST /api/auth/registration
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

#### Вход
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password"
}
```

### Посты

#### Получение списка постов
```http
GET /api/posts
Authorization: Bearer {token}
```

#### Создание поста
```http
POST /api/posts
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Заголовок поста",
    "content": "Содержание поста",
    "category": {
        "id": 1
    },
    "tags": [
        {"id": 1},
        {"id": 2}
    ]
}
```

#### Обновление поста
```http
PUT /api/posts/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Новый заголовок",
    "content": "Новое содержание",
    "category": {
        "id": 2
    },
    "tags": [
        {"id": 3},
        {"id": 4}
    ]
}
```

#### Удаление поста
```http
DELETE /api/posts/{id}
Authorization: Bearer {token}
```

## 🧪 Тестирование

Проект имеет полное покрытие тестами. Для запуска тестов используйте:

```bash
php artisan test
```

### Типы тестов:

- Unit тесты для сервисов и репозиториев
- Feature тесты для API эндпоинтов
- Интеграционные тесты для кеширования
- Тесты аутентификации

## 🏗 Архитектура

Проект следует принципам чистой архитектуры и использует следующие паттерны:

- Repository Pattern для работы с данными
- Service Layer для бизнес-логики
- DTO для передачи данных
- Interface Segregation для зависимостей
- Cache Abstraction для гибкого кеширования

## 📈 Кеширование

Проект использует Redis для кеширования:

- Кеширование списков постов
- Кеширование отдельных постов
- Автоматическая инвалидация при изменениях
- Поддержка тегированного кеширования
- Настраиваемое время жизни кеша

## 🔒 Безопасность

- JWT для безопасной аутентификации
- Валидация всех входящих данных
- Защита от CSRF атак
- Rate Limiting для API endpoints
- Санитизация данных

## 🤝 Вклад в проект

Мы приветствуем ваш вклад в проект! Пожалуйста:

1. Форкните репозиторий
2. Создайте ветку для вашей функциональности
3. Зафиксируйте изменения
4. Отправьте пулл-реквест

## 📝 Лицензия

Этот проект лицензирован под MIT License - подробности в файле [LICENSE.md](LICENSE.md)

## 👥 Авторы

- **Ваше Имя** - *Начальная работа* - [YourGithub](https://github.com/yourgithub)

## 📬 Контакты

Если у вас есть вопросы или предложения:

- Email: your.email@example.com
- Telegram: @your_telegram
- GitHub: [your-username](https://github.com/your-username)

Для доступа к 3,4,5 методам необходимо получить токен    
Документация 
<a href =https://documenter.getpostman.com/view/40913826/2sAYQfEVAt >Postman</a>
1. POST
Registration
```
http://localhost:8000/api/auth/registration
```
<pre>
Регистрация пользователя
PARAMS
name
email
password
</pre>


2. POST    	
login	
```
http://localhost:8000/api/auth/login
```
<pre>
Необходим для получения jwt токена
PARAMS   
email	    
password  
</pre>


3. GET
Posts
```
http://localhost:8000/api/posts
```
<pre>
Получение списка всех постов
</pre>

4. GET
ShowPost
```
http://localhost:8000/api/posts/{post_id}
```
<pre>
Получить конкретный пост
Пример:
    http://localhost:8000/api/posts/123
</pre>


5. POST	
CreatePost	
```
http://localhost:8000/api/posts
```
<pre>
4 обязательных параметра

title - Заголовог поста, текст

content - содержание поста, текст

tags - Теги, список масивов, содержащих либо id(номер тега), либо title(название нового тега). Допускается одновременно использование id и title для переименования существующего тега

category - Категории ,массив содержащий либо id(номер категории), либо title(название новой категории). Допускается одновременно использование id и title для переименования существующей категории аналогично тегам
</pre>
Пример body
```
{
    "title" : "New post api",
    "content" : "Some content",
    "tags" : [
        {"id" : 1},
        {"id" : 2}
    ],
    "category" : {
        "id" : 1
    }
}
```
