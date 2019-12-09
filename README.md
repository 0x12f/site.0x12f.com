## Шаблон для начала работы с платформой

#### Запуск контейнера из командной строки
```shell script
docker run --detach \
  --name 0x12f-platform \
  --publish 80:80 \
  --restart always \ 
  --volume $PWD/resource:/var/container/public/resource:ro \
  --volume $PWD/theme:/var/container/theme:ro \
  --volume $PWD/var:/var/container/var:rw \
  --volume $PWD/var/upload:/var/container/public/uploads:rw \
  0x12f/platform:latest
```

#### Запуск контейнера с помощью docker-compose.yml
Пример учитывает использование Traefik, вы можете настроить иначе.
```yaml
version: "3"

networks:
    web:
        external: true

services:
    platform:
        image:  0x12f/platform:latest
        volumes:
            - $PWD/resource:/var/container/public/resource:ro
            - $PWD/theme:/var/container/theme:ro
            - $PWD/var:/var/container/var:rw
            - $PWD/var/upload:/var/container/public/uploads:rw
        labels:
            - traefik.enable=true
            - traefik.port=80
            - traefik.backend=example.site.0x12f.com
            - traefik.frontend.rule=Host:example.site.0x12f.com
          # - traefik.frontend.redirect.entryPoint=https
            - traefik.docker.network=web
        networks:
            - web
```

#### Права на папки
```shell script
chmod -R 0777 resource
chmod -R 0777 theme
chmod -R 0777 var
```

#### Инициализация схемы базы данных
```shell script
docker-compose run platform vendor/bin/doctrine orm:schema-tool:create
```

##### Обновление схемы базы данных
```shell script
docker-compose run platform vendor/bin/doctrine orm:schema-tool:update --force
```

##### Права на файл базы данных
```shell script
chmod 0777 var/database.sqlite
```

#### Добавление пользователя с правами администратора
Логин: `admin`  
E-Mail: `admin@example.com`  
Пароль: `111222`

```shell script
docker-compose run platform vendor/bin/doctrine dbal:run-sql "INSERT INTO user_session (uuid) VALUES ('00000000-0000-0000-0000-000000000000');"
docker-compose run platform vendor/bin/doctrine dbal:run-sql "INSERT INTO user (uuid, username, email, password, status, level) VALUES ('00000000-0000-0000-0000-000000000000', 'admin', 'admin@example.com', '4b60602435c81eca6516601b68219c37f93de49c1192660aaa16066070e23b352fb0578b30cb588bb416b5138f03511a809f8b6610d20d90bf72d2a4d9e9548e06cd3eec8ed6', 'work', 'admin');"
```
