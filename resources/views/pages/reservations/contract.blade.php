<!DOCTYPE html>
<html>
<head>
    <title>Reservation Contract</title>
    <style>
        /* Add your contract styles here */
    </style>
</head>
<body>
    <h1>Reservation Contract</h1>
    <p>Reservation ID: {{ $reservation->id }}</p>
    <p>Client Name: {{ $reservation->client->name }}</p>
    <p>Venue: {{ $reservation->venue->name }}</p>
    <p>Date: {{ $reservation->date }}</p>
    <!-- Add more contract details here -->
</body>c
</html>
