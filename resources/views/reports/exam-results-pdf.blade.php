<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Deneme Sınavları Değerlendirme Raporu</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #0f172a;
            line-height: 1.6;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        
        .header-container {
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e1b4b;
        }

        .header-subtitle {
            font-size: 12px;
            color: #475569;
            margin-top: 5px;
            font-weight: 500;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f8fafc;
            border: 1.5px solid #64748b;
            border-radius: 6px;
        }

        .info-table td {
            padding: 12px 15px;
            border-bottom: 1.5px solid #e2e8f0;
            font-size: 12px;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            color: #1e293b;
            width: 25%;
        }

        .info-value {
            color: #0f172a;
            font-weight: 500;
        }

        /* Stats Cards */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .stats-card {
            background-color: #f1f5f9;
            border: 1.5px solid #64748b;
            padding: 15px;
            text-align: center;
            width: 23%;
            border-radius: 6px;
        }

        .stats-number {
            font-size: 20px;
            font-weight: bold;
        }

        .stats-label {
            font-size: 10px;
            color: #334155;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
            letter-spacing: 0.5px;
        }

        .color-primary { color: #4f46e5; }
        .color-success { color: #059669; }
        .color-accent { color: #7c3aed; }
        .color-danger { color: #dc2626; }

        /* Field Stats Table */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 12px;
            border-left: 4px solid #4f46e5;
            padding-left: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .data-table th {
            background-color: #e2e8f0;
            border: 1.5px solid #64748b;
            padding: 10px 12px;
            text-align: left;
            font-weight: bold;
            color: #0f172a;
            font-size: 12px;
        }

        .data-table td {
            border: 1.5px solid #94a3b8;
            padding: 10px 12px;
            font-size: 12px;
            color: #0f172a;
        }

        /* Grouped Exam Block */
        .exam-block {
            margin-bottom: 25px;
            border: 1.5px solid #64748b;
            background-color: #ffffff;
            border-radius: 6px;
            page-break-inside: avoid;
        }

        .exam-header {
            background-color: #cbd5e1;
            padding: 10px 14px;
            font-weight: bold;
            border-bottom: 1.5px solid #64748b;
        }

        .exam-header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .exam-header-title {
            font-size: 13px;
            color: #0f172a;
        }

        .exam-header-meta {
            text-align: right;
            font-size: 11px;
            color: #1e293b;
            font-weight: bold;
        }

        .exam-details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .exam-details-table th {
            background-color: #f1f5f9;
            border-bottom: 1.5px solid #94a3b8;
            border-right: 1.5px solid #94a3b8;
            padding: 8px 14px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e293b;
        }

        .exam-details-table td {
            border-bottom: 1.5px solid #e2e8f0;
            border-right: 1.5px solid #94a3b8;
            padding: 8px 14px;
            font-size: 12px;
            color: #0f172a;
        }

        .exam-details-table th:last-child,
        .exam-details-table td:last-child {
            border-right: none;
        }

        .exam-total-row {
            background-color: #e2e8f0;
            font-weight: bold;
        }

        .exam-total-row td {
            border-top: 1.5px solid #64748b;
            border-bottom: none;
            color: #0f172a;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .text-success { color: #047857; font-weight: bold; }
        .text-danger { color: #b91c1c; font-weight: bold; }
        .text-warning { color: #d97706; font-weight: bold; }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #475569;
            border-top: 1.5px solid #cbd5e1;
            padding-top: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td>
                    <div class="header-title">Öğrenci Deneme Değerlendirme Raporu</div>
                    <div class="header-subtitle">Deneme sınav sonuçları ve performans analiz dökümü</div>
                </td>
                <td style="text-align: right; vertical-align: bottom; font-size: 11px; color: #1e293b; font-weight: bold;">
                    Rapor Tarihi: {{ $date }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Student Details -->
    <table class="info-table">
        <tr>
            <td class="info-label">Öğrenci Adı:</td>
            <td class="info-value">{{ $student->name }}</td>
            <td class="info-label">Rehber Koç:</td>
            <td class="info-value">{{ $coach ? $coach->name : 'Atanmamış' }}</td>
        </tr>
        <tr>
            <td class="info-label">E-posta:</td>
            <td class="info-value">{{ $student->email }}</td>
            <td class="info-label">Sistem Durumu:</td>
            <td class="info-value">{{ $student->is_active ? 'Aktif Öğrenci' : 'Pasif' }}</td>
        </tr>
    </table>

    <!-- Overview Stats -->
    <div class="section-title">Genel Deneme Performansı</div>
    <table class="stats-table">
        <tr>
            <td class="stats-card">
                <div class="stats-number color-primary">{{ $stats['total_exams'] }}</div>
                <div class="stats-label">Toplam Sınav</div>
            </td>
            <td style="width: 2%;"></td>
            <td class="stats-card">
                <div class="stats-number color-success">{{ number_format($stats['avg_net'], 2) }}</div>
                <div class="stats-label">Ortalama Net</div>
            </td>
            <td style="width: 2%;"></td>
            <td class="stats-card">
                <div class="stats-number color-accent">{{ number_format($stats['best_net'], 2) }}</div>
                <div class="stats-label">En Yüksek Net</div>
            </td>
            <td style="width: 2%;"></td>
            <td class="stats-card">
                <div class="stats-number color-danger">{{ number_format($stats['worst_net'], 2) }}</div>
                <div class="stats-label">En Düşük Net</div>
            </td>
        </tr>
    </table>

    <!-- Field Stats -->
    @if(count($fieldStats) > 0)
        <div class="section-title">Alan Bazlı Başarı Durumu</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Alan Adı</th>
                    <th class="text-center" style="width: 20%;">Sınav Sayısı</th>
                    <th class="text-center" style="width: 25%;">Ortalama Net</th>
                    <th class="text-center" style="width: 25%;">En Yüksek Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fieldStats as $fieldName => $fStats)
                    <tr>
                        <td style="font-weight: bold; color: #0f172a;">{{ $fieldName }}</td>
                        <td class="text-center" style="font-weight: bold;">{{ $fStats['count'] }}</td>
                        <td class="text-center text-success" style="font-size: 13px;">{{ number_format($fStats['avg_net'], 2) }}</td>
                        <td class="text-center" style="font-weight: bold; font-size: 13px;">{{ number_format($fStats['best_net'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Detailed Exam Log -->
    <div class="section-title">Detaylı Sınav Sonuçları</div>

    @foreach($groupedResults as $examKey => $exam)
        <div class="exam-block">
            <div class="exam-header">
                <table class="exam-header-table">
                    <tr>
                        <td class="exam-header-title">
                            📝 <strong>{{ $exam['exam_name'] }}</strong> 
                            <span style="font-size: 10px; color: #1e293b; margin-left: 10px; font-weight: bold;">
                                (Türü: {{ $exam['exam_type'] ?? 'Genel' }} | Alan: {{ $exam['field_name'] }})
                            </span>
                        </td>
                        <td class="exam-header-meta">
                            Tarih: {{ $exam['exam_date']->format('d.m.Y') }}
                        </td>
                    </tr>
                </table>
            </div>

            <table class="exam-details-table">
                <thead>
                    <tr>
                        <th style="text-align: left; width: 40%;">Ders Adı</th>
                        <th class="text-center" style="width: 15%;">Doğru</th>
                        <th class="text-center" style="width: 15%;">Yanlış</th>
                        <th class="text-center" style="width: 15%;">Boş</th>
                        <th class="text-center" style="width: 15%;">Net Skoru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exam['courses'] as $course)
                        <tr>
                            <td style="font-weight: bold;">{{ $course['course_name'] }}</td>
                            <td class="text-center text-success" style="font-size: 13px;">{{ $course['correct'] }}</td>
                            <td class="text-center text-danger" style="font-size: 13px;">{{ $course['wrong'] }}</td>
                            <td class="text-center text-warning" style="font-size: 13px;">{{ $course['blank'] }}</td>
                            <td class="text-center" style="font-weight: bold; font-size: 13px; background-color: #f8fafc;">
                                {{ number_format($course['net'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="exam-total-row">
                        <td style="font-weight: bold; letter-spacing: 0.5px;">TOPLAM SKOR</td>
                        <td class="text-center text-success" style="font-size: 13px;">{{ $exam['total_correct'] }}</td>
                        <td class="text-center text-danger" style="font-size: 13px;">{{ $exam['total_wrong'] }}</td>
                        <td class="text-center text-warning" style="font-size: 13px;">{{ $exam['total_blank'] }}</td>
                        <td class="text-center" style="background-color: #cbd5e1; font-size: 14px; font-weight: bold; color: #0f172a;">
                            {{ number_format($exam['total_net'], 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        Bu rapor Öğrenci Takip & Koçluk Sistemi üzerinden otomatik olarak üretilmiştir.
    </div>

</body>
</html>
