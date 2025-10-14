
# Docker LAMP
Linux + Apache + MariaDB (MySQL) + PHP 7.2 on Docker Compose. Mod_rewrite enabled by default.

## Instructions

Asegúrate de que no tengas la bd:
```bash
$ sudo rm -r mysql
```
Asegúrate de no tener otro contenedor con el mismo nombre:
```bash
$ docker rm -f web
```
Ahora, construye la imagen y levanta entorno (cd al proyecto!!):
```bash
$ docker build -t web .
```
```bash
$ docker-compose up -d
```

Para pararlo:
```bash
$ docker-compose stop
```

Feel free to make pull requests and help to improve this.

If you are looking for phpMyAdmin, take a look at [this](https://github.com/celsocelante/docker-lamp/issues/2).
