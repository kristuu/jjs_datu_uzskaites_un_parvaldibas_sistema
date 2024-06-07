<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reservation Cancelled</title>
</head>
<body>
<h3>Rezervācijas pieteikums atcelts</h3>
<p style="margin-bottom: 1rem;">Rezervācijas pieteikums pie
    trenera {{ $reservation->instructor->user->name . ' ' . $reservation->instructor->user->surname . ' (' . $reservation->instructor->certificate->category->name . ') ' }}
    tika atcelts.</p>

<p style="margin-bottom: 0;"><strong>Sākuma laiks</strong>: {{ $reservation->start_time }}</p>
<p style="margin-bottom: 2rem;"><strong>Beigu laiks</strong>: {{ $reservation->end_time }}</p>

<p style="margin-bottom: 0;"><strong>Pieteikumu atcēlušais
        lietotājs</strong>: {{ $reservation->user->name . ' ' . $reservation->user->surname }}</p>
<p style="margin-bottom: 2rem;"><strong>Lietotāja norādītais iemesls</strong>: {{ $reason }}</p>
</body>
</html>
