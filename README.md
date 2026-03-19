# ⌚ Sistem Pendeteksi RPE (Rate of Perceived Exertion) Berbasis IoT
![IoT](https://img.shields.io/badge/IoT-Emerging_Tech-FF9900?style=for-the-badge)
![ESP32](https://img.shields.io/badge/ESP32-C++-00599C?style=for-the-badge&logo=c%2B%2B&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_&_MySQL-Backend-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-Dashboard-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

## 📌 Deskripsi Proyek
Proyek ini adalah purwarupa perangkat keras *Internet of Things* (IoT) yang dirancang untuk memonitor metrik kelelahan fisik atau **Rate of Perceived Exertion (RPE)** secara *real-time*. 

Pengukuran RPE secara manual sering kali bersifat subjektif dan rentan tidak akurat, yang berisiko menyebabkan cedera atau *overtraining* pada atlet dan kelelahan pada lansia. Sistem ini memecahkan masalah tersebut dengan mengekstrak data denyut jantung (BPM) secara objektif menggunakan sensor optik, memprosesnya di dalam mikrokontroler, dan memvisualisasikannya ke dalam *Web Dashboard* interaktif.

## 👨‍💻 Tim Pengembang (Kelompok 16)
* **Tricahyo Diqolbu** (NIM: 202310370311309) – *IoT Pipeline & Data Integration*
* **Akhmadani Bahaillah** (NIM: 202310370311342) – *Hardware Assembly* *(Mata Kuliah Piranti Cerdas 5D - Universitas Muhammadiyah Malang)*

## 🛠️ Arsitektur Sistem & Data Pipeline
Sistem ini dibangun dengan arsitektur *end-to-end* dari ranah fisik (*Hardware*) menuju analitik digital (*Software*):

### 1. Edge Node (Hardware)
Bertugas sebagai pengumpul data biologis di lapangan.
* **Mikrokontroler:** ESP32 DevKit C
* **Rangkaian Perangkat (Pin Mapping):**
  * **Pulse Sensor:** Pin `36` (Data), `3V3` (VCC), `GND` (GND)
  * **LED Indikator (Visual Alert):** Pin `18` (Positif), `GND` (Negatif)
  * **Buzzer (Audio Alert):** Pin `19` (Positif), `GND` (Negatif)

### 2. Back-End & Telemetri
* ESP32 mengirimkan data BPM dan klasifikasi RPE (Ringan, Sedang, Berat, Maksimal) setiap 5 detik melalui protokol HTTP POST ke *server* lokal.
* Skrip `kirim_data.php` menangkap data POST dan menyimpannya secara terstruktur ke dalam basis data **MySQL** (`tb_jantung` dalam `db_kesehatan`).

### 3. Front-End Web Dashboard
* Dibangun menggunakan **PHP, HTML, dan Bootstrap 5**.
* Menarik data *real-time* secara asinkron menggunakan AJAX Fetch API dari `baca_data.php`.
* Menyajikan visualisasi *Line Chart* pergerakan BPM menggunakan **Chart.js** dan *History Table* log aktivitas pengguna.

## 💡 Nilai Implementasi Bisnis (Use Case)
1. **Pencegahan Overtraining:** Modul peringatan dini (*Buzzer* & LED) akan menyala secara otomatis jika sistem mendeteksi lonjakan BPM yang masuk kategori RPE Maksimal, mencegah cedera fisik seketika.
2. **Kesehatan & Sport Tech:** Mengubah asumsi kelelahan subjektif menjadi deretan data objektif yang bisa dievaluasi oleh pelatih kebugaran atau dokter.
3. **Infrastruktur Terukur:** Skema pengiriman data HTTP POST terpusat memungkinkan integrasi lanjutan data sensor ini ke dalam ekosistem aplikasi analitik *mobile* pihak ketiga.

## 📷 Dokumentasi Visual & Arsitektur
![Poster Arsitektur RPE](SISTEM%20PENDETEKSI%20RPE%20TENAGA%20MANUSIA%20BERBASIS%20IoT%20-%20POSTER.jpg)
