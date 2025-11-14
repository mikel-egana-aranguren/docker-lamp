import requests
from bs4 import BeautifulSoup
import time

# Configuración
BASE_URL = "http://localhost:81"
LOGIN_URL = f"{BASE_URL}/login.php"
REGISTER_URL = f"{BASE_URL}/register"
MODIFY_ITEM_URL = f"{BASE_URL}/modify_item"
MODIFY_USER_URL = f"{BASE_URL}/modify_user"
ADD_ITEM_URL = f"{BASE_URL}/add_item"

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

def test_login_sql_injection():
    """Prueba inyección SQL en el formulario de login (error, boolean y time-based)"""
    print("\n[*] Probando inyección SQL en LOGIN...")
    session = requests.Session()

    # Respuesta base con usuario inexistente
    baseline_data = {
        'nombre_usuario': "usuario_inexistente",
        'contrasena': "password_inexistente",
        'login_submit': 'Iniciar sesión'
    }
    baseline = session.post(LOGIN_URL, data=baseline_data).text

    # ---- Error-based: crea un error en la base de datos para que este se haga visible (si lo es) en la página ----
    error_payloads = ["'", "' OR 1=1--", "')--", "' UNION SELECT NULL--"]

    print("\n--- ERROR-BASED TESTS ---")
    for payload in error_payloads:
        data = {
            'nombre_usuario': payload,
            'contrasena': payload,
            'login_submit': 'Iniciar sesión'
        }

        r = session.post(LOGIN_URL, data=data)

        sql_errors = ["syntax", "mysql", "mysqli", "ORA-", "ODBC", "SQLSTATE"]

        if any(err.lower() in r.text.lower() for err in sql_errors):
            print(f"[!] VULNERABLE (error-based) - {payload}")
        else:
            print(f"[+] Protegido - {payload}")

    # ---- Boolean-based: verifica si la respuesta cambia según la condición booleana ----
    print("\n--- BOOLEAN-BASED TESTS ---")
    true_payload = "' OR '1'='1--"
    false_payload = "' OR '1'='0--"

    r_true = session.post(LOGIN_URL, data={'nombre_usuario': true_payload, 'contrasena': true_payload}).text
    r_false = session.post(LOGIN_URL, data={'nombre_usuario': false_payload, 'contrasena': false_payload}).text

    if r_true != r_false:
        print("[!] VULNERABLE (boolean-based detected)")
    else:
        print("[+] Protegido contra boolean-based")

    # ---- Time-based: verifica si la respuesta tarda más debido a una función de retardo en la consulta SQL ----
    print("\n--- TIME-BASED TESTS ---")
    time_payload = "' OR SLEEP(3)--"

    t1 = time.time()
    session.post(LOGIN_URL, data={'nombre_usuario': time_payload, 'contrasena': time_payload})
    t2 = time.time()

    if t2 - t1 > 2.8:
        print("[!] VULNERABLE (time-based)")
    else:
        print("[+] Protegido contra time-based")


def test_register_sql_injection():
    """Prueba inyección SQL en el formulario de registro (error, boolean y time-based)"""
    print("\n[*] Probando inyección SQL en REGISTER...")
    session = requests.Session()

    csrf_token = obtener_csrf_token(session, REGISTER_URL)

    # Baseline: registro con datos válidos
    baseline_data = {
        'csrf_token': csrf_token,
        'nombre': "Test",
        'apellidos': "Normal",
        'dni': "00000000A",
        'telefono': "111111111",
        'fecha_nacimiento': "2000-01-01",
        'email': "baseline@test.com",
        'nombre_usuario': "baselineUser",
        'contrasena': "123456",
        'register_submit': 'Registrar'
    }
    baseline_resp = session.post(REGISTER_URL, data=baseline_data).text

    # ---- Error-based ----
    print("\n--- ERROR-BASED TESTS ---")
    error_payloads = ["'", "' OR 1=1--", "')--", "' UNION SELECT NULL--"]

    for i, payload in enumerate(error_payloads):
        data = baseline_data.copy()
        data['dni'] = f"1234567{i}A"
        data['email'] = f"error{i}@test.com"
        data['nombre'] = payload

        r = session.post(REGISTER_URL, data=data)

        sql_errors = ["syntax", "mysql", "mysqli", "ORA-", "ODBC", "SQLSTATE"]

        if any(err.lower() in r.text.lower() for err in sql_errors):
            print(f"[!] VULNERABLE (error-based) - {payload}")
        else:
            print(f"[+] Protegido - {payload}")

    # ---- Boolean-based ----
    print("\n--- BOOLEAN-BASED TESTS ---")
    data_true = baseline_data.copy()
    data_true['nombre'] = "' OR '1'='1"
    data_true['email'] = "booltrue@test.com"
    data_true['dni'] = "99999991A"

    data_false = baseline_data.copy()
    data_false['nombre'] = "' OR '1'='0"
    data_false['email'] = "boolfalse@test.com"
    data_false['dni'] = "99999992A"

    r_true = session.post(REGISTER_URL, data=data_true).text
    r_false = session.post(REGISTER_URL, data=data_false).text

    if r_true != r_false:
        print("[!] POSIBLE VULNERABILIDAD (boolean-based)")
    else:
        print("[+] Protegido contra boolean-based")

    # ---- Time-based ----
    print("\n--- TIME-BASED TESTS ---")
    data_time = baseline_data.copy()
    data_time['nombre'] = "' OR SLEEP(3)--"
    data_time['email'] = "time@test.com"
    data_time['dni'] = "99999993A"

    t1 = time.time()
    session.post(REGISTER_URL, data=data_time)
    t2 = time.time()

    if t2 - t1 > 2.8:
        print("[!] VULNERABLE (time-based)")
    else:
        print("[+] Protegido contra time-based")


def test_modify_item_sql_injection():
    """Prueba inyección SQL en modify_item tanto en GET como POST"""
    print("\n[*] Probando inyección SQL en MODIFY_ITEM...")
    session = requests.Session()

    # ---- LOGIN ----
    login_data = {
        'nombre_usuario': 'Juan',
        'contrasena': '123',
        'login_submit': 'Iniciar sesión'
    }
    try:
        session.post(LOGIN_URL, data=login_data, allow_redirects=True)
    except:
        print("[!] No se pudo iniciar sesión. Revisa credenciales.")
        return

    # URL base
    url_base = f"{BASE_URL}/modify_item?item="

    # Obtener respuesta normal para comparar
    try:
        baseline = session.get(url_base + "1").text
    except:
        print("[!] No se pudo obtener la respuesta base.")
        return

    # ---- PAYLOADS GET ----
    get_payloads_error_based = [
        "1'",
        "1' OR '1'='1",
        "1' OR 1=1--",
        "1' UNION SELECT NULL,NULL--",
    ]

    get_payloads_boolean = [
        "1' AND 1=1--",
        "1' AND 1=0--",
    ]

    get_payloads_time = [
        "1' AND SLEEP(3)--",
    ]

    print("\n--- TEST GET ERROR-BASED ---")
    for payload in get_payloads_error_based:
        url = url_base + payload
        r = session.get(url)

        if r.status_code != 200:
            print(f"[!] VULNERABLE (status code distinto) - {payload}")
            continue

        if len(r.text) != len(baseline):
            print(f"[!] VULNERABLE (respuesta distinta) - {payload}")
            continue

        print(f"[+] Protegido - {payload}")

    print("\n--- TEST GET BOOLEAN-BASED ---")
    r_true = session.get(url_base + get_payloads_boolean[0]).text
    r_false = session.get(url_base + get_payloads_boolean[1]).text

    if r_true != r_false:
        print("[!] VULNERABLE - Comportamiento boolean-based detectado")
    else:
        print("[+] Protegido contra boolean-based")

    print("\n--- TEST GET TIME-BASED ---")
    for payload in get_payloads_time:
        t1 = time.time()
        session.get(url_base + payload)
        t2 = time.time()

        if t2 - t1 > 2.5:
            print(f"[!] VULNERABLE (time-based) - {payload}")
        else:
            print(f"[+] Protegido - {payload}")

    # ---- AHORA TEST POST ----
    print("\n--- TEST POST (FORMULARIO) ---")

    csrf = obtener_csrf_token(session, url_base + "1")

    post_payloads = [
        "' OR '1'='1",
        "' OR 1=1--",
        "1' UNION SELECT NULL,NULL--"
    ]

    for payload in post_payloads:
        data = {
            'csrf_token': csrf or '',
            'titulo': payload,
            'genero': 'Acción',
            'plataforma': 'PC',
            'fecha_lanzamiento': '2024-01-01',
            'precio': '59.99',
            'modify_submit': 'Modificar'
        }

        r = session.post(url_base + "1", data=data, allow_redirects=True)

        sql_indicators = [
            "SQL syntax", "mysql", "mysqli", "ORA-", "ODBC",
            "you have an error in your sql syntax"
        ]

        if any(e.lower() in r.text.lower() for e in sql_indicators):
            print(f"[!] VULNERABLE (error-based POST) - {payload}")
            continue

        if payload in r.text:
            print(f"[!] VULNERABLE (payload reflejado) - {payload}")
            continue

        print(f"[+] Protegido - {payload}")


def test_modify_user_sql_injection():
    """Prueba inyección SQL en modificar usuario (POST, boolean-based, error-based y time-based)"""
    print("\n[*] Probando inyección SQL en MODIFY_USER...")
    session = requests.Session()

    # ---- LOGIN ----
    login_data = {
        'nombre_usuario': 'Juan',
        'contrasena': '123',
        'login_submit': 'Iniciar sesión'
    }
    try:
        session.post(LOGIN_URL, data=login_data, allow_redirects=True)
    except:
        print("[!] No se pudo iniciar sesión. Revisa credenciales.")
        return

    # ---- OBTENER CSRF ----
    url = f"{MODIFY_USER_URL}?user=1"
    csrf = obtener_csrf_token(session, url)

    # ---- Obtener respuesta baseline ----
    baseline_data = {
        'csrf_token': csrf,
        'nombre': "Baseline",
        'apellidos': 'TestModify',
        'dni': "98765430B",
        'telefono': '987654321',
        'fecha_nacimiento': '1995-05-15',
        'email': 'modifybaseline@test.com',
        'nombre_usuario': 'baselineUserModify',
        'contrasena': 'newpass123',
        'modify_submit': 'Modificar'
    }
    baseline_response = session.post(url, data=baseline_data).text

    # ---- ERROR-BASED ----
    print("\n--- ERROR-BASED TESTS ---")
    error_payloads = ["'", "' OR 1=1--", "')--", "' UNION SELECT NULL--"]

    for i, payload in enumerate(error_payloads):
        data = baseline_data.copy()
        data['dni'] = f"9876543{i}B"
        data['email'] = f"modify{i}@test.com"
        data['nombre'] = payload

        r = session.post(url, data=data)

        error_signals = ["syntax", "mysql", "mysqli", "SQLSTATE", "ORA-", "ODBC"]

        if any(err.lower() in r.text.lower() for err in error_signals):
            print(f"[!] VULNERABLE (error-based) - {payload}")
        else:
            print(f"[+] Protegido - {payload}")

    # ---- BOOLEAN-BASED ----
    print("\n--- BOOLEAN-BASED TESTS ---")
    true_data = baseline_data.copy()
    false_data = baseline_data.copy()

    true_data['nombre'] = "' OR '1'='1"
    true_data['email'] = "booltrue@t.com"
    true_data['dni'] = "11111111A"

    false_data['nombre'] = "' OR '1'='0"
    false_data['email'] = "boolfalse@t.com"
    false_data['dni'] = "11111112A"

    r_true = session.post(url, data=true_data).text
    r_false = session.post(url, data=false_data).text

    if r_true != r_false:
        print("[!] VULNERABLE (boolean-based differences detected)")
    else:
        print("[+] Protegido contra boolean-based")

    # ---- TIME-BASED ----
    print("\n--- TIME-BASED TESTS ---")
    time_data = baseline_data.copy()
    time_data['nombre'] = "' OR SLEEP(3)--"
    time_data['email'] = "timesql@test.com"
    time_data['dni'] = "11111113A"

    t1 = time.time()
    session.post(url, data=time_data)
    t2 = time.time()

    if t2 - t1 > 2.8:
        print("[!] VULNERABLE (time-based)")
    else:
        print("[+] Protegido contra time-based")


def test_add_item_sql_injection():
    """Prueba inyección SQL en añadir videojuego (POST error, boolean y time-based)"""
    print("\n[*] Probando inyección SQL en ADD_ITEM...")
    session = requests.Session()

    # ---- LOGIN ----
    login_data = {
        'nombre_usuario': 'Juan',
        'contrasena': '123',
        'login_submit': 'Iniciar sesión'
    }
    try:
        session.post(LOGIN_URL, data=login_data, allow_redirects=True)
    except:
        print("[!] No se pudo iniciar sesión.")
        return

    # ---- OBTENER CSRF ----
    csrf = obtener_csrf_token(session, ADD_ITEM_URL)

    # ---- BASELINE ----
    baseline_data = {
        'csrf_token': csrf,
        'titulo': "Juego Normal",
        'genero': 'Acción',
        'plataforma': 'PC',
        'fecha_lanzamiento': '2024-01-01',
        'precio': '59.99',
        'add_submit': 'Añadir'
    }
    baseline = session.post(ADD_ITEM_URL, data=baseline_data).text

    # ---- ERROR-BASED ----
    print("\n--- ERROR-BASED TESTS ---")
    error_payloads = ["'", "' OR 1=1--", "')--", "' UNION SELECT NULL--"]

    for payload in error_payloads:
        data = baseline_data.copy()
        data['titulo'] = payload

        r = session.post(ADD_ITEM_URL, data=data)

        error_signals = ["syntax", "mysql", "mysqli", "SQLSTATE", "ORA-", "ODBC"]

        if any(err.lower() in r.text.lower() for err in error_signals):
            print(f"[!] VULNERABLE (error-based) - {payload}")
        else:
            print(f"[+] Protegido - {payload}")

    # ---- BOOLEAN-BASED ----
    print("\n--- BOOLEAN-BASED TESTS ---")
    true_data = baseline_data.copy()
    false_data = baseline_data.copy()

    true_data['titulo'] = "' OR '1'='1"
    false_data['titulo'] = "' OR '1'='0"

    r_true = session.post(ADD_ITEM_URL, data=true_data).text
    r_false = session.post(ADD_ITEM_URL, data=false_data).text

    if r_true != r_false:
        print("[!] VULNERABLE (boolean-based differences detected)")
    else:
        print("[+] Protegido contra boolean-based")

    # ---- TIME-BASED ----
    print("\n--- TIME-BASED TESTS ---")
    time_data = baseline_data.copy()
    time_data['titulo'] = "' OR SLEEP(3)--"

    t1 = time.time()
    session.post(ADD_ITEM_URL, data=time_data)
    t2 = time.time()

    if t2 - t1 > 2.8:
        print("[!] VULNERABLE (time-based)")
    else:
        print("[+] Protegido contra time-based")


def main():
    print("=" * 60)
    print("PRUEBA DE SEGURIDAD - INYECCIÓN SQL")
    print("=" * 60)
    print("=" * 60)
    
    # Ejecutar pruebas
    test_login_sql_injection()
    test_register_sql_injection()
    test_modify_item_sql_injection()
    test_modify_user_sql_injection()
    test_add_item_sql_injection()

    
    print("\n" + "=" * 60)
    print("PRUEBAS COMPLETADAS")
    print("=" * 60)

if __name__ == "__main__":
    main()