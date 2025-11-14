import requests
from bs4 import BeautifulSoup
import time
import uuid

def obtener_csrf_token(session, url):
    """Obtiene el token CSRF de un formulario"""
    try:
        response = session.get(url)
        soup = BeautifulSoup(response.text, 'html.parser')
        csrf_input = soup.find('input', {'name': 'csrf_token'})
        if csrf_input:
            return csrf_input.get('value')
        return None
    except Exception as e:
        print(f"Error obteniendo CSRF token: {e}")
        return None

def test_modify_item_sql_injection():
    """Prueba inyección SQL en modify_item y verifica si el objeto aparece en items"""
    print("\n[*] Probando inyección SQL en MODIFY_ITEM...")
    session = requests.Session()

    # ---- LOGIN ----
    login_data = {
        'nombre_usuario': 'Juan',
        'contrasena': '123',
        'login_submit': 'Iniciar sesión'
    }
    try:
        session.post("http://localhost:81/login.php", data=login_data, allow_redirects=True)
    except:
        print("[!] No se pudo iniciar sesión. Revisa credenciales.")
        return

    # ---- OBTENER TOKEN CSRF ----
    for i in range(11):
        csrf = obtener_csrf_token(session, "http://localhost:81/modify_item?item=" + str(i))

        # ---- CREAR PAYLOADS POST ----
        post_payloads = [
            "' OR '1'='1",
            "' OR 1=1--",
            "1' UNION SELECT NULL,NULL--"
        ]

        for payload in post_payloads:
            # Título único aleatorio
            titulo_random = f"{payload[:5]}_{uuid.uuid4().hex[:6]}"

            data = {
                'csrf_token': csrf or '',
                'titulo': titulo_random,
                'genero': 'Acción',
                'plataforma': 'PC',
                'fecha_lanzamiento': '2024-01-01',
                'precio': '59.99',
                'modify_submit': 'Modificar'
            }

            r = session.post("http://localhost:81/modify_item?item=" + str(i), data=data, allow_redirects=True)

            # Verificar si la respuesta contiene indicios de SQL Injection
            sql_indicators = ["SQL syntax", "mysql", "mysqli", "ORA-", "ODBC", "you have an error in your sql syntax"]
            if any(e.lower() in r.text.lower() for e in sql_indicators):
                print(f"[!] VULNERABLE (error-based POST) - {payload}")
                continue

            # Ahora comprobamos si aparece en el listado
            try:
                items_page = session.get("http://localhost:81/items").text
                if titulo_random in items_page:
                    print(f"[!] POSIBLE VULNERABILIDAD - Payload reflejado y item creado: {payload}")
                else:
                    print(f"[+] Protegido - Payload bloqueado o item no creado: {payload}")
            except Exception as e:
                print(f"[!] Error al comprobar listado: {e}")


def main():
    print("=" * 60)
    print("PRUEBA DE SEGURIDAD - INYECCIÓN SQL")
    print("=" * 60)
    print("=" * 60)
    
    # Ejecutar prueba
    test_modify_item_sql_injection()
    
    print("\n" + "=" * 60)
    print("PRUEBAS COMPLETADAS")
    print("=" * 60)

if __name__ == "__main__":
    main()