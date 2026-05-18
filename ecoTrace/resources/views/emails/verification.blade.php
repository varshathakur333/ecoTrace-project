<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Plus Jakarta Sans', Arial, sans-serif; background-color: #f9f9f7; color: #1a1c1b; margin: 0; padding: 20px; }
        .card { border: 2px solid #1a1c1b; background-color: #ffffff; padding: 24px; box-shadow: 4px 4px 0px #1a1c1b; max-width: 600px; margin: auto; }
        .header { background-color: #004335; color: #ffffff; padding: 16px; font-weight: bold; border-bottom: 2px solid #1a1c1b; font-size: 20px; text-transform: uppercase; }
        .btn { display: inline-block; background-color: #caf208; color: #1a1c1b; border: 2px solid #1a1c1b; padding: 12px 24px; text-decoration: none; font-weight: bold; margin-top: 15px; box-shadow: 2px 2px 0px #1a1c1b; }
        .meta { background-color: #eeeeec; padding: 12px; margin-top: 15px; border-left: 4px solid #004335; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">EcoTrace // Admin Alert</div>
        <h2>New Collector Verification Required</h2>
        <p>A new agency has registered on EcoTrace E-Waste Platform and requires active business verification:</p>
        
        <div class="meta">
            <strong>Business Name:</strong> {{ $collector->business_name }}<br>
            <strong>License No:</strong> {{ $collector->license_no }}<br>
            <strong>Contact Person:</strong> {{ $collector->name }}<br>
            <strong>Email:</strong> {{ $collector->email }}<br>
            <strong>Phone:</strong> {{ $collector->phone }}<br>
            <strong>Address:</strong> {{ $collector->address }}
        </div>

        <p>Please review and verify their license credentials in the Admin Control dashboard.</p>
        <a href="{{ route('login') }}" class="btn">Go to Dashboard</a>
    </div>
</body>
</html>
