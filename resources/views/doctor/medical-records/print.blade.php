<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Record - {{ $medicalRecord->patient->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .clinic-info {
            margin-bottom: 10px;
        }
        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .patient-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            flex: 1;
        }
        .info-title {
            font-weight: bold;
            color: #2c5aa0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .medical-content {
            margin-top: 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f5f5f5;
            padding: 10px;
            font-weight: bold;
            border-left: 4px solid #2c5aa0;
            margin-bottom: 10px;
        }
        .content-box {
            border: 1px solid #ddd;
            padding: 15px;
            min-height: 100px;
            background-color: #fafafa;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            🖨️ Print
        </button>
        <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ❌ Close
        </button>
    </div>

    <div class="header">
        <div class="clinic-info">
            <div class="clinic-name">KLINIK SEHAT BERSAMA</div>
            <div>Jl. Kesehatan No. 123, Yogyakarta</div>
            <div>Telp: (0274) 123456 | Email: info@kliniksehat.com</div>
        </div>
    </div>

    <div class="patient-info">
        <div class="info-section">
            <div class="info-title">INFORMASI PASIEN</div>
            <div class="info-item">
                <span class="label">Nama:</span>
                {{ $medicalRecord->patient->name }}
            </div>
            <div class="info-item">
                <span class="label">Umur:</span>
                {{ \Carbon\Carbon::parse($medicalRecord->patient->birth_date)->age }} tahun
            </div>
            <div class="info-item">
                <span class="label">Jenis Kelamin:</span>
                {{ $medicalRecord->patient->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
            </div>
            <div class="info-item">
                <span class="label">Telepon:</span>
                {{ $medicalRecord->patient->phone ?? '-' }}
            </div>
            <div class="info-item">
                <span class="label">Alamat:</span>
                {{ $medicalRecord->patient->address ?? '-' }}
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-title">INFORMASI KUNJUNGAN</div>
            <div class="info-item">
                <span class="label">Tanggal:</span>
                {{ $medicalRecord->visit_date->format('d F Y') }}
            </div>
            <div class="info-item">
                <span class="label">Dokter:</span>
                {{ $medicalRecord->doctor->name }}
            </div>
            <div class="info-item">
                <span class="label">Spesialisasi:</span>
                {{ $medicalRecord->doctor->specialization ?? '-' }}
            </div>
            <div class="info-item">
                <span class="label">No. Record:</span>
                #{{ str_pad($medicalRecord->id, 4, '0', STR_PAD_LEFT) }}
            </div>
        </div>
    </div>

    <div class="medical-content">
        <div class="section">
            <div class="section-title">GEJALA/KELUHAN</div>
            <div class="content-box">
                {{ $medicalRecord->symptoms ?? '-' }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">DIAGNOSIS</div>
            <div class="content-box">
                {{ $medicalRecord->diagnosis }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">PENGOBATAN & TINDAKAN</div>
            <div class="content-box">
                {{ $medicalRecord->treatment }}
            </div>
        </div>

        @if($medicalRecord->prescription)
        <div class="section">
            <div class="section-title">RESEP OBAT</div>
            <div class="content-box">
                {!! nl2br(e($medicalRecord->prescription)) !!}
            </div>
        </div>
        @endif

        @if($medicalRecord->notes)
        <div class="section">
            <div class="section-title">CATATAN TAMBAHAN</div>
            <div class="content-box">
                {!! nl2br(e($medicalRecord->notes)) !!}
            </div>
        </div>
        @endif
    </div>

    <div class="footer">
        <div class="signature">
            <div>Pasien/Keluarga</div>
            <div class="signature-line">
                (...................................)
            </div>
        </div>
        
        <div class="signature">
            <div>Dokter Pemeriksa</div>
            <div class="signature-line">
                {{ $medicalRecord->doctor->name }}
            </div>
            <div style="margin-top: 5px; font-size: 12px;">
                {{ $medicalRecord->doctor->specialization ?? '' }}
            </div>
        </div>
    </div>

    <script>
        // Auto print ketika halaman dimuat (opsional)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // }
    </script>
</body>
</html>
