#include <WiFi.h>
#include <HTTPClient.h>
#include <PulseSensorPlayground.h>

// --- Konfigurasi WiFi ---
char ssid[] = "BERUANG REBUS";
char pass[] = "nothinglefttosay1";
// Pastikan IP ini sesuai dengan IP Laptop saat demo nanti
const char* serverName = "http://192.168.43.231/Project/kirim_data.php"; 

// --- Pin Mapping ---
const int PulseWire = 36; // Pin Sensor
const int LED_PIN = 18;   // LED
const int BUZZER_PIN = 19; // Buzzer
int Threshold = 550;      // Sensitivitas

PulseSensorPlayground pulseSensor;

// Variabel Timer (Pengganti BlynkTimer)
unsigned long previousMillis = 0;
const long interval = 5000; // Kirim data setiap 5000ms (5 detik)

// Variabel Data
int myBPM = 0;
String myRPE = "-";          
String statusKegiatan = "-"; 

// Deklarasi Fungsi
void sendData();
void tentukanStatus(int bpm);
void bunyi(int kali, int durasi);

void setup() {
  Serial.begin(115200);
  Serial.println("\nSistem Dimulai - Mode Mandiri (Tanpa Blynk)");

  pinMode(LED_PIN, OUTPUT);
  pinMode(BUZZER_PIN, OUTPUT);

  // 1. Koneksi WiFi
  WiFi.begin(ssid, pass);
  Serial.print("Menghubungkan ke WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Terhubung!");
  Serial.print("IP Address ESP32: ");
  Serial.println(WiFi.localIP());

  // 2. Setup Pulse Sensor
  pulseSensor.analogInput(PulseWire);
  pulseSensor.setThreshold(Threshold);
  
  if (pulseSensor.begin()) {
    Serial.println("Pulse Sensor Siap!");
  }
}

void loop() {
  // Tidak ada Blynk.run() lagi!
  
  // 1. BACA SENSOR (Real-time)
  int currentBPM = pulseSensor.getBeatsPerMinute();

  if (pulseSensor.sawStartOfBeat()) {
    myBPM = currentBPM;
    tentukanStatus(myBPM); 
    
    // Tampilkan di Serial Monitor
    Serial.print("♥ Detak! BPM: "); 
    Serial.print(myBPM);
    Serial.print(" | RPE: "); 
    Serial.print(myRPE);
    Serial.print(" | Status: "); 
    Serial.println(statusKegiatan);

    // Logika Buzzer & LED
    if (myBPM >= 90 && myBPM < 110) {
      bunyi(1, 100); 
    } 
    else if (myBPM >= 110 && myBPM <= 135) {
      bunyi(2, 80);
    }
    else if (myBPM > 135) {
      bunyi(1, 200);
    } 
    else {
      digitalWrite(LED_PIN, HIGH);
      delay(20);
      digitalWrite(LED_PIN, LOW);
    }
  }

  // 2. KIRIM DATA (Setiap 5 Detik)
  // Menggunakan millis() agar tidak blocking (sensor tetap jalan)
  unsigned long currentMillis = millis();
  if (currentMillis - previousMillis >= interval) {
    previousMillis = currentMillis;
    sendData(); // Panggil fungsi kirim
  }
}

void tentukanStatus(int bpm) {
  if (bpm < 40) { 
    myRPE = "-";
    statusKegiatan = "Standby/No Signal";
  }
  else if (bpm < 90) {
    myRPE = "1-3";
    statusKegiatan = "Ringan";
  } 
  else if (bpm >= 90 && bpm < 110) {
    myRPE = "4-5";
    statusKegiatan = "Sedang";
  }
  else if (bpm >= 110 && bpm <= 135) {
    myRPE = "6-7"; 
    statusKegiatan = "Berat";
  }
  else {
    myRPE = "8-9"; 
    statusKegiatan = "Maksimal";
  }
}

void bunyi(int kali, int durasi) {
  for (int i = 0; i < kali; i++) {
    digitalWrite(LED_PIN, HIGH);
    digitalWrite(BUZZER_PIN, HIGH);
    delay(durasi);
    digitalWrite(LED_PIN, LOW);
    digitalWrite(BUZZER_PIN, LOW);
    if (kali > 1) delay(durasi); 
  }
}

void sendData() {
  if (WiFi.status() == WL_CONNECTED && myBPM > 40) {
      HTTPClient http;
      http.begin(serverName);
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      
      String statusKirim = statusKegiatan;
      statusKirim.replace(" ", "+"); 

      // Sesuai dengan database 'tb_jantung' kolom 'keterangan'
      String httpRequestData = "bpm=" + String(myBPM) + 
                               "&rpe=" + myRPE + 
                               "&keterangan=" + statusKirim;
      
      int httpResponseCode = http.POST(httpRequestData);
      
      if (httpResponseCode > 0) {
         Serial.print(">> Upload Sukses! Response: ");
         Serial.println(httpResponseCode);
      } else {
         Serial.print(">> Gagal Upload. Error: ");
         Serial.println(httpResponseCode);
      }
      http.end();
  } else {
      Serial.println(">> WiFi Terputus atau BPM 0, Data tidak dikirim.");
  }
}
