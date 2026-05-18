<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Plus Jakarta Sans', Arial, sans-serif; background-color: #f9f9f7; color: #1a1c1b; margin: 0; padding: 20px; }
        .card { border: 2px solid #1a1c1b; background-color: #ffffff; padding: 24px; box-shadow: 4px 4px 0px #1a1c1b; max-width: 600px; margin: auto; }
        .header { background-color: #004335; color: #ffffff; padding: 16px; font-weight: bold; border-bottom: 2px solid #1a1c1b; font-size: 20px; text-transform: uppercase; }
        .status { font-size: 18px; font-weight: bold; color: #006b55; }
        .meta { background-color: #eeeeec; padding: 12px; margin-top: 15px; border-left: 4px solid #004335; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">EcoTrace // Booking Update</div>
        <h2>Service Booking Confirmation</h2>
        <p>Hello,</p>
        <p>Your EcoTrace service booking status has been updated:</p>

        <div class="meta">
            <strong>Booking ID:</strong> #{{ $booking->id }}<br>
            <strong>Service Listing:</strong> {{ $booking->service->title }}<br>
            <strong>Pickup Date:</strong> {{ $booking->booking_date->format('Y-m-d') }}<br>
            <strong>Est. Weight:</strong> {{ $booking->weight }} kg<br>
            <strong>Status:</strong> <span class="status">{{ strtoupper($booking->status) }}</span><br>
            <strong>Collector Notes:</strong> {{ $booking->notes ?? 'None' }}
        </div>

        <p>Thank you for contributing to carbon diversion and circular recycling economy!</p>
    </div>
</body>
</html>
