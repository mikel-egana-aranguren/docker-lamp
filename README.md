## Instrucciones
Para buildear
Posicionarse dentro del diractorio del .yml y escribir el comando
```bash
$ bash build
```


Para encender el server:
```bash
$ docker-compose up
```
y visitar localhost:81
Prueba a iniciar sesion con usuario: Juan y contraseña: 123

Para apagarlo:
```bash
$ docker-compose down
```

Para resetearlo y borrar los datos de la base de datos:
```bash
$ docker-compose down
$ sudo rm -rf mysql
```

