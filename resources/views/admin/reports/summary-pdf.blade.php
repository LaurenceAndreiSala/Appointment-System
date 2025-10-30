<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Summary Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #000; margin: 20px; }
        h1, h2, h3 { color: #2C3E50; text-align: center; }
        .section { margin-top: 35px;}

        /* ✅ Header section with logo + title */
        .section-financial {
             margin-top: 150px;
        }
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            border-bottom: 2px solid #2C3E50;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .header img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        .header h1 {
            font-size: 28px;
            margin: 0;
            color: #2C3E50;
        }

        /* ✅ Overview Inline Cards */
        .overview {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            gap: 15px;
            text-align: center;
        }
        .card {
            flex: 1;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f8f9fa;
            padding: 15px 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card h3 {
            font-size: 15px;
            margin-bottom: 8px;
            color: #1a5276;
        }
        .card p {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #2C3E50;
        }

        canvas { margin-top: 20px; }
        .chart-container {
            width: 100%;
            text-align: center;
            margin: 30px 0;
        }
        .chart-graph {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/clinic-logo.png') }}" alt="Clinic Logo">
        <h1>MediCAL Summary Report</h1>
    </div>

    <p style="text-align:center;">Generated on {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</p>

    <!-- ✅ Overview Section -->
    <div class="section">
        <h2>Overview</h2>
        <div class="overview">
            <div class="card">
                <h3>Completed</h3>
                <p>{{ $completedAppointments }}</p>
            </div>
            <div class="card">
                <h3>Pending</h3>
                <p>{{ $pendingAppointments }}</p>
            </div>
            <div class="card">
                <h3>Cancelled</h3>
                <p>{{ $cancelledAppointments }}</p>
            </div>
            <div class="card">
                <h3>Total Payments</h3>
                <p>PHP{{ number_format($totalPayments, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- ✅ Financial Section -->
    <div class="section-financial">
        <h2>Financial Overview</h2>
        <p>Revenue: PHP{{ number_format($revenue, 2) }}</p>
        <p>Average Per Patient: PHP{{ number_format($avgPerPatient, 2) }}</p>
    </div>

    <!-- ✅ Appointments Table -->
    <div class="section">
        <h2>Appointments in the Last 7 Days</h2>
        <table width="100%" border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr style="background:#007bff;color:#fff;">
                    <th>Date</th>
                    <th>Appointments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointmentsPerDay as $date => $count)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                        <td style="text-align:center;">{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ✅ Charts Section -->
    <div class="chart-container">
        <h3>Charts Preview</h3>
        <div class="chart-graph">
            <img src="https://quickchart.io/chart?c={type:'pie',data:{labels:['Completed','Pending','Cancelled'],datasets:[{data:[{{ $completedAppointments }},{{ $pendingAppointments }},{{ $cancelledAppointments }}]}]}}" width="300">
            <img src="https://quickchart.io/chart?c={type:'pie',data:{labels:['Revenue','Pending','Field'],datasets:[{data:[{{ $revenue }},{{ $pending }},{{ $field }}]}]}}" width="300">
        </div>
    </div>

    <p style="text-align:center;margin-top:40px;font-size:12px;color:#555;">
         {{ date('Y') }} MediCAL | Automatically generated summary report
    </p>
</body>
</html>
