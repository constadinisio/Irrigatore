
<h1 align="center">
  <br>
  <a href="http://www.amitmerchant.com/electron-markdownify"><img src="https://github.com/constadinisio/Irrigatore---Sistema-de-Riego/blob/main/Front%20End%20-%20Irrigatore/Irrigatore%20(1).jpg" alt="Markdownify" width="200"></a>
  <br>
  Irrigatore
  <br>
</h1>

<h4 align="center">Un sistema de Riego Automático usando diferentes métodos físicos hasta digitales llamado<a href="https://github.com/constadinisio/Irrigatore---Sistema-de-Riego" target="_blank"> Irrigatore</a>.</h4>

<p align="center">
  <a href="#-información">📖 Información</a> •
  <a href="#-configuración">⚙ Configuración</a> •
  <a href="#-creditos">👥 Creditos</a> •
  <a href="#-lenguajes-utilizados">👨‍💻 Lenguajes Utilizados</a> •
  <a href="#-licencia">📋 Licencia</a>
</p>

## 📖 Información

Nos complace presentar Irrigatore, un proyecto innovador y único desarrollado por un grupo de estudiantes como parte de su proyecto final en conjunto a las materias "Proyecto Integrador I" y "Dispositivos Electrónicos Programables". Irrigatore se idea a partir y con la misión de facilitar y optimizar el riego de plantas y huertas, proporcionando una solución automatizada que libera a las personas de la constante preocupación de regar sus plantas.

Este sistema de riego automático está diseñado para detectar la necesidad de agua de las plantas y activarse de manera inteligente, asegurando un uso eficiente del recurso y cuidando la salud de las plantas.

## ⚙ Configuración

<details open>
<summary>
Pre-Requisitos
</summary> <br />
Para poder ejecutar nuestro proyecto tendrás que tener los siguientes programas:

###

* Programas a Utilizar:
  - XAMPP
  - Visual Studio Code
  - Arduino
* Hardware a Utilizar:
  - DHT11  			_(x1)_
  - YL69   			_(x1)_
  - Placa de Desarrollo ESP8266	_(x1)_
  - Cable Dupont		_(x11)_
  - Bomba de agua para fuente	_(x1)_
  - Relé de un canal		_(x1)_
</details>

<details open>
<summary>
Ejecutando la Aplicación
</summary> <br />

### - **¿Qué cambios hacer para que el Arduino funcione?**
  * Dentro de la carpeta "Back End - CPP", encontraremos un archivo "irrigatore_backend.ino" haremos los siguientes cambios:
    - Línea 19 y 20: Configuración WiFi <br>
			```char ssid[] = "ssid_name";        // Tu ssid de WiFi```<br>
			```char pass[] = "ssid_password";    // Tu contraseña de WiFi```
    - Linea 23 a 26: Configuración MySQL <br>
			```char mysql_user[] = "mysql_user";         // Tu usuario de MySQL```<br>
			```char mysql_password[] = "mysql_pw";  // Tu contraseña de MySQL```<br>
			```IPAddress server_ip(ipv4_network); //  IPv4 Address```<br>
			```char database[] = "db_name"; // Nombre de la base de datos```<br>
    - Linea 50: Database MySQL: <br>
			```if (conn.connect(server_ip, port, mysql_user, mysql_password)) { // "port" por el puerto que tengamos configurado en nuestra base de datos MySQL.```<br>
    - Linea 54: Database MySQL: <br>
			```cursor.execute("USE db_name"); // "db_name" por el nombre de la base de datos que estemos usando.```

### - **¿Qué cambios hacer para que el MySQL funcione?**
  * Dentro del programa XAMPP, más específicamente en "my.ini":
    - Descomentamos y reemplazamos por el siguiente texto:
			```* bind-address=0.0.0.0```
  * Dentro del phpMyAdmin
      - Usuarios:
        - Iremos a la sección "User Accounts" y vamos a crear un usuario con contraseña dandole todos los permisos que sean posibles, además de ponerle como target al "Host Name" como "Any Host" (Lo usaremos en el código Arduino reemplazando "mysql_user" y "mysql_pw").
      - Importar Tabla:
        - Como último paso crearemos una base de datos (Con el mismo nombre con el que estamos reemplazando en el Arduino como "db_name" e importaremos la plantilla que está subida al repositorio de GitHub.
       
### **¿Qué cambios hacer para que la Webpage funcione?**
  * Dentro del directorio encontraremos diferentes archivos, empezaremos por el "conexion.php":
   - Lo que reemplazaremoos va a ser lo siguiente:<br>
     - File: **conexion.php**
       	- Línea 2:
	   ```$servername = "ip_mysql";```<br>
			> Ingresaremos la ip que tenemos en especifica para ingresar al panel MySQL.
         - Línea 3:
           ```$username = "mysql_user";```<br>
			> Ingresaremos el usuario que creamos en el MySQL especificamente para la conexión de nuestra placa.
         - Línea 4:
     	   ```$password = "mysql_pw";```<br>
			> Vamos a setear la password con la contraseña que definimos al crear nuestro usuario.
         - Línea 5:
     	   ```$dbname = "db_name";```<br>
			> Pondremos el nombre de la base de datos que creamos anteriormente en el MySQL.
     - File: **dashboard.php**
		- Línea 106:
		  ```$conn = new mysqli('ip_mysql', 'mysql_user', 'mysql_pw', 'db_name');```<br>
			> Reemplazaremos esas variables con la información que cambiamos en el "conexion.php"
     - File: **obtener_datos.php**
		- Línea 2:
		  ```$conn = new mysqli('ip_mysql', 'mysql_user', 'mysql_pw', 'db_name');```
			> Reemplazaremos esas variables con la información que cambiamos en el "conexion.php"
     - File: **ping.php**
		- Línea 2:
		  -$ip = 'ip_esp';<br>
    		> IP de la placa de desarrollo que estemos usando.

</details>

## 👥 Creditos

El proyecto da creditos a las siguientes personas:
 - ✨ Constantino Di Nisio - ***Programación - HTML y Arduino***<br>
 - ✨ Marcos Baez - ***Ayudante en Código e Ideador de Software***<br>
 - ✨ Lionel Mazza - ***Ideador del Proyecto***<br>
 - ✨ Santino Bustamante - ***Documentador***<br>
 - ✨ Laureano Bernat - ***Documentador***<br>

## 👨‍💻 Lenguajes Utilizados

|Base de Datos: | [![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com/) [![Amazon AWS](https://img.shields.io/badge/Amazon_AWS-FF9900?style=for-the-badge&logo=amazonaws&logoColor=white)](https://aws.amazon.com/) [![Amazon RDS](https://img.shields.io/badge/Amazon%20RDS-527FFF?style=for-the-badge&logo=amazon-rds&logoColor=white)](https://aws.amazon.com/)|
|-:|:-|
|**Lenguajes**: | [![C++](https://img.shields.io/badge/C%2B%2B-00599C?style=for-the-badge&logo=c%2B%2B&logoColor=white)](https://isocpp.org/) [![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://html.spec.whatwg.org/multipage/) [![JavaSript](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)]([https://html.spec.whatwg.org/multipage/](https://developer.mozilla.org/es/docs/Web/JavaScript)) [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)|
|**Plataforma de Desarrollo**: | [![Arduino IDE](https://img.shields.io/badge/Arduino-00979D?style=for-the-badge&logo=Arduino&logoColor=white)](https://arduino.cc) [![XAMPP](https://img.shields.io/badge/Xampp-F37623?style=for-the-badge&logo=xampp&logoColor=white)](https://www.apachefriends.org/es/index.html) [![Visual Studio Code](https://img.shields.io/badge/Visual_Studio_Code-0078D4?style=for-the-badge&logo=visual%20studio%20code&logoColor=white)](https://code.visualstudio.com/) |
|**Front End**:|[![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=Apache&logoColor=white)](https://httpd.apache.org/)|

## License

MIT License

