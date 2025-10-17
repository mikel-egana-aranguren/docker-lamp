# Integrantes
Mbarek Galloul Ezzakraoui,
Alicia Maizkurrena,
Adrián Vinagre,
Maira Gabriela Herbas Jaldin y
Ana Victoria Cernatescu

# Docker LAMP
Linux + Apache + MariaDB (MySQL) + PHP 7.2 on Docker Compose. Mod_rewrite enabled by default.

## Instrucciones

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

Para pararlo y borrar contenedor y tal
```bash
$ docker-compose down -v

```

Feel free to make pull requests and help to improve this.

If you are looking for phpMyAdmin, take a look at [this](https://github.com/celsocelante/docker-lamp/issues/2).
