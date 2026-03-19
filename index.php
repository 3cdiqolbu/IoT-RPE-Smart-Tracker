<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Monitor | Pro Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
            --primary: #3b82f6;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            padding-bottom: 50px;
            transition: background-color 0.3s;
        }

        /* Kelas Bahaya (Layar Berkedip Merah) */
        .danger-mode {
            background-color: #fee2e2 !important;
            animation: flashRed 1s infinite;
        }
        @keyframes flashRed { 0% { opacity: 1; } 50% { opacity: 0.8; } 100% { opacity: 1; } }

        /* Navbar */
        .navbar-custom {
            background: var(--card-bg);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        /* Kartu Statistik */
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
            transition: transform 0.2s, border-color 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }

        .stat-number { font-size: 38px; font-weight: 700; margin: 10px 0; }
        .stat-label { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; }
        
        .icon-box {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }

        /* Warna Status Dinamis */
        .status-ringan { color: var(--success); border-color: var(--success); }
        .status-sedang { color: var(--warning); border-color: var(--warning); }
        .status-berat  { color: var(--danger); border-color: var(--danger); }

        /* Grafik & Tabel */
        .content-box {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        
        .table-custom thead th {
            background-color: #f1f5f9;
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            border: none;
        }
    </style>
</head>
<body id="main-body">

    <audio id="alarm-sound" src="https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3"></audio>

    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-heart-pulse text-danger fa-xl"></i>
                <span class="fw-bold fs-4">MedMonitor Pro</span>
            </div>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="toggleMute()">
                <i id="mute-icon" class="fa-solid fa-volume-xmark"></i> Suara Alarm
            </button>
        </div>
    </nav>

    <div class="container">
        
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h4 class="fw-bold m-0">Dashboard Pasien</h4>
                <small class="text-muted">Update Real-time via HTTP Polling</small>
            </div>
            <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                <i class="fa-solid fa-circle fa-xs me-1"></i> LIVE
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card border-start border-4 border-danger">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-label">Detak Jantung</div>
                            <div class="stat-number text-danger" id="bpm-val">0</div>
                            <small class="text-muted">Beats Per Minute</small>
                        </div>
                        <div class="icon-box bg-danger bg-opacity-10 text-danger">
                            <i class="fa-solid fa-heart"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card border-start border-4 border-primary">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-label">Skala Usaha (RPE)</div>
                            <div class="stat-number text-primary" id="rpe-val">-</div>
                            <small class="text-muted">Intensitas Latihan</small>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-gauge-high"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card border-start border-4 border-success" id="card-status">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-label">Status Kondisi</div>
                            <div class="stat-number text-success" id="status-val">-</div>
                            <small class="text-muted">Analisa Otomatis</small>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success" id="icon-status">
                            <i class="fa-solid fa-person-running"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="content-box">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-area me-2"></i>Tren Detak Jantung</h5>
                    <div style="height: 350px; width: 100%;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="content-box h-100">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-list-ul me-2"></i>Riwayat Terkini</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-custom">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>BPM</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="history-table">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // --- 1. SETUP AWAL ---
        let isMuted = true; // Default Mute biar tidak kaget
        const alarm = document.getElementById('alarm-sound');
        const body = document.getElementById('main-body');
        
        function toggleMute() {
            isMuted = !isMuted;
            const btn = document.getElementById('mute-icon');
            if(isMuted) {
                btn.className = "fa-solid fa-volume-xmark";
                alert("Alarm Dimatikan");
            } else {
                btn.className = "fa-solid fa-volume-high";
                // Mainkan suara silent sebentar untuk memancing permission browser
                alarm.play().then(() => alarm.pause()); 
                alert("Alarm Diaktifkan! (Akan bunyi jika BPM > 130)");
            }
        }

        // --- 2. GRAFIK CHART.JS ---
        const ctx = document.getElementById('myChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(239, 68, 68, 0.5)');
        gradient.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'BPM',
                    data: [],
                    borderColor: '#ef4444',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0, 
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false }, 
                    y: { beginAtZero: true, max: 200 }
                },
                animation: false
            }
        });

        // --- 3. LOGIKA UPDATE DATA ---
        function updateDashboard() {
            fetch('baca_data.php')
                .then(response => response.json())
                .then(data => {
                    const bpm = parseInt(data.bpm);
                    const now = new Date().toLocaleTimeString('id-ID');

                    // A. Update Teks Utama
                    document.getElementById('bpm-val').innerText = bpm;
                    document.getElementById('rpe-val').innerText = data.rpe;
                    document.getElementById('status-val').innerText = data.keterangan;

                    // B. Fitur ALARM & VISUAL ALERT (Jantung > 130)
                    if (bpm > 130) {
                        body.classList.add('danger-mode'); // Layar kedip merah
                        if (!isMuted) {
                            alarm.play().catch(e => console.log(e)); // Bunyi beep
                        }
                    } else {
                        body.classList.remove('danger-mode');
                    }

                    // C. Fitur Dynamic Color (Ubah warna teks status)
                    const statusVal = document.getElementById('status-val');
                    const statusCard = document.getElementById('card-status');
                    
                    // Reset kelas warna
                    statusVal.classList.remove('text-success', 'text-warning', 'text-danger');
                    statusCard.classList.remove('border-success', 'border-warning', 'border-danger');

                    if (data.keterangan === "Ringan") {
                        statusVal.classList.add('text-success');
                        statusCard.classList.add('border-success');
                    } else if (data.keterangan === "Sedang") {
                        statusVal.classList.add('text-warning');
                        statusCard.classList.add('border-warning');
                    } else { // Berat / Maksimal
                        statusVal.classList.add('text-danger');
                        statusCard.classList.add('border-danger');
                    }

                    // D. Update Grafik
                    if(myChart.data.labels.length > 20) {
                        myChart.data.labels.shift();
                        myChart.data.datasets[0].data.shift();
                    }
                    myChart.data.labels.push(now);
                    myChart.data.datasets[0].data.push(bpm);
                    myChart.update();

                    // E. Fitur LOG TABLE (Menambah baris baru di atas)
                    addLogToTable(now, bpm, data.keterangan);
                })
                .catch(err => console.error(err));
        }

        // Fungsi Tambahan untuk Tabel
        function addLogToTable(time, bpm, status) {
            const tableBody = document.getElementById('history-table');
            
            // Cek data terakhir agar tidak duplikat di tabel (opsional)
            if (tableBody.rows.length > 0 && tableBody.rows[0].cells[0].innerText === time) {
                return; 
            }

            const row = tableBody.insertRow(0); // Masukkan di paling atas
            
            // Tentukan warna badge status
            let badgeClass = "bg-success";
            if(status === "Sedang") badgeClass = "bg-warning text-dark";
            if(status === "Berat" || status === "Maksimal") badgeClass = "bg-danger";

            row.innerHTML = `
                <td>${time}</td>
                <td class="fw-bold">${bpm}</td>
                <td><span class="badge ${badgeClass}">${status}</span></td>
            `;

            // Hapus baris jika lebih dari 7 agar tidak kepanjangan
            if(tableBody.rows.length > 7) {
                tableBody.deleteRow(7);
            }
        }

        setInterval(updateDashboard, 1500);
    </script>
</body>
</html>