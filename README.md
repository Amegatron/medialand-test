Тестовое задание для MediaLand.

###Установка###
Склонировать проект:
```
git clone https://github.com/Amegatron/medialand-test.git
```
#####Docker Compose#####
```
docker-compose up --build 
```
Веб-сервис будет доступен по адресу `http://localhost:8080`

#####Ручная настройка#####
Если Docker недоступен, требуется вручную настроить веб-сервер.
В случае NGINX'а добавить конфиг `nginx/conf.d/uuids.conf` к конфигам NGINX'а, заменив в нем следующее:
1. В строке `listen 80 default_server;` убрать `default_server` и по желанию заменить порт на более удобный.
2. Путь в строке `root /var/www/public;` заменить на фактический путь, по которому лежит директория `public` проекта.
3. В строке `fastcgi_pass ...;` заменить `counters-app:9000` в соотв-ии с локальной конфигурацией PHP-FPM.  