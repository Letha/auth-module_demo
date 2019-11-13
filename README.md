## Описание
Форма регистрации/авторизации с отображением профиля.</br>
Стек технологий: PHP 7, MySQL 8, JavaScript (ES6), jQuery.</br>
Язык: английский, русский.</br>
Запуск с применением Docker.</br></br>
При регистрации можно отправить изображение, которое будет отображено в профиле.</br></br>
Прописаны юнит-тесты (PHPUnit) для методов классов, за исключением методов, 
работающих с базой данных или осуществляющих выход из программы.</br>
## Запуск
Запустить можно через Docker командой `docker-compose up`. 
Если использовать `docker-compose up -d`, может потребоваться подождать для того, 
чтобы модуль базы данных завершил свою активацию.</br></br>
Чтобы запустить тесты, нужно использовать PHPUnit-флаг `--stderr`, 
так как тестируемые методы могут работать с заголовками.</br>
## Структура базы данных
![изображение недоступно](https://github.com/Letha/auth-module_demo/blob/develop/schemes/db_Main.png)
## Структура директорий
В корневой директории:
- Файл для Docker-compose.
- Директория с билдами для Docker.
- Директория с дампом базы данных.
- Директория со схемой базы данных.
- Директория приложения (app).</br>

В директории app:
- index.php, autoload.php, файлы Composer.
- Директория front-end - файлы для фронт-енд.
- Директория src - остальной бэкэнд приложения.
- Директория с тестами.
- Директория storage - для хранения файлов, полученных от клиента.</br>

В директории app/config - конфигурационные параметры приложения.
