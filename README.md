Для доступа к 3,4,5 методам необходимо получить токен    
Коллекция 
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
