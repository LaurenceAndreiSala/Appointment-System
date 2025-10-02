<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Summary Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">📊 Summary Report</h2>
    <table>
        <thead>
            <tr>
                <th>Metric</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Completed Appointments</td><td>{{ $completedAppointments }}</td></tr>
            <tr><td>Pending Appointments</td><td>{{ $pendingAppointments }}</td></tr>
            <tr><td>Cancelled Appointments</td><td>{{ $cancelledAppointments }}</td></tr>
            <tr><td>Total Revenue (₱)</td><td>{{ number_format($totalPayments, 2) }}</td></tr>
        </tbody>
    </table>
</body>
</html>
