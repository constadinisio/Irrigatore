#include <ESP8266WiFi.h>
#include <DHT.h>
#include <MySQL_Connection.h>
#include <MySQL_Cursor.h>

// Configuración PIN - DHT11
#define DHTPIN 3
#define DHTTYPE DHT11

DHT dht(DHTPIN, DHTTYPE);

// Configuración PIN - Relé
byte RELE_PIN = D4;

// Configuración - YL69
int YL69_PIN = A0;

// Configuración de la conexión WiFi
char ssid[] = "ssid_name";        // Tu ssid de WiFi
char pass[] = "ssid_password";    // Tu contraseña de WiFi  // Tu contraseña de WiFi

// Configuración de la conexión MySQL
char mysql_user[] = "mysql_user";         // Tu usuario de MySQL
char mysql_password[] = "mysql_pw";  // Tu contraseña de MySQL
IPAddress server_ip(ipv4_network); //  IPv4 Address: 
char database[] = "db_name"; // Nombre de la base de datos

WiFiClient client;
MySQL_Connection conn((Client *)&client);

void setup() {
  Serial.begin(9600);
  Serial.println("Iniciando...");
  dht.begin();

  pinMode(RELE_PIN, OUTPUT);

  // Conectar a WiFi
  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Conectando a WiFi...");
  }

    Serial.println("Conectado a WiFi");
    Serial.print("Dirección IP del ESP8266: ");
    Serial.println(WiFi.localIP());  // Muestra la IP local en el monitor serial

  // Conectar a MySQL
  if (conn.connect(server_ip, port, mysql_user, mysql_password)) {
    Serial.println("Conectado a MySQL");
    // Seleccionar la base de datos
    MySQL_Cursor cursor(&conn);
    cursor.execute("USE db_name"); // Cambiado
  } else {
    Serial.println("Error de conexión a MySQL");
    while(1) { delay(1); } // Detener el programa
  }
}

void loop() {
    int estado_rele = 0;

    // Leer la temperatura y la humedad del aire
    float temperatura = dht.readTemperature();
    float humedad_aire = dht.readHumidity();

    // Leer la humedad de la tierra desde el YL-69
    int humedad_tierra = analogRead(YL69_PIN);

    if (humedad_tierra < 819) {
      digitalWrite(RELE_PIN, HIGH);  // Relé activado
      estado_rele = 1;
      Serial.println("Relé activado: Humedad de la tierra baja");
      delay(300000); // Esperar 5 minutos
    } else {
      // Apagar el relé si la humedad es suficiente
      digitalWrite(RELE_PIN, LOW);  // Relé apagado
      estado_rele = 0;
      Serial.println("Relé desactivado: Humedad de la tierra suficiente");
    }

    // Preparar la consulta SQL
    MySQL_Cursor cursor(&conn);
    String query = "INSERT INTO ciclo (temperatura, humedad_aire, humedad_tierra, estado_rele, creacion) VALUES (" +
                   String(temperatura) + ", " +
                   String(humedad_aire) + ", " +
                   String(humedad_tierra) + ", " +
                   String(estado_rele) + ", " +
                   String(NOW()) + ")";
    
    // Ejecutar la consulta
    cursor.execute(query.c_str());

    // Esperar 30 segundos antes de enviar nuevamente
    delay(30000);
}