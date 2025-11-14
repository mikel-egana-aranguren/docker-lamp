import requests
url="http://localhost:81/modify_user"

#creamos una lista con posibles valores para el parámetro user
param=["1","2", "2345678", "admin", "root", "administrator", "a", "b"]

for p in param: #vamos a intentar con cada valor de la lista
    response=requests.get(url, params={'user': p}, allow_redirects=False)
    print(f"Probando con user={p} ")
    if response.status_code==200:
        print(f"Posible usuario válido encontrado: {p}", "Status: ", response.status_code)
    if response.status_code==301 or response.status_code==302:
        print(f"Redirección detectada con user={p} - Posible usuario válido")
    else:
        print(f"user={p} no es un usuario válido", "Status: ", response.status_code)
    print("-"*50)