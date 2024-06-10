<h3 style="margin: 0 0 1rem 0;">Rezervācijas pieteikums atcelts</h3>
<p>Rezervācijas pieteikums pie
    trenera <strong>{{ $reservation->instructor->user->name }} {{ $reservation->instructor->user->surname }}
        (kategorija {{ $reservation->instructor->certificate->category->name }})</strong>
    tika atcelts.</p>

<p style="margin-bottom: 0;"><strong>Sākuma
        laiks</strong>: {{ $reservation->instructorAvailability->start_time->format('d.m.Y H.i') }}</p>
<p style="margin-bottom: 1rem;"><strong>Beigu
        laiks</strong>: {{ $reservation->instructorAvailability->end_time->format('d.m.Y H.i') }}</p>

<p style="margin-bottom: 0;"><strong>Pieteikumu atcēlušais
        lietotājs</strong>: {{ $reservation->user->name }} {{ $reservation->user->surname }}
</p>
<p style="margin-bottom: 1rem;"><strong>Lietotāja norādītais iemesls</strong>: {{ $reason }}</p>

<p style="margin-bottom: 0;"><strong>Šis laiks automātiski tika atzīmēts kā atkal pieejams. Lai pārvaldītu savus
        pieejamos laikus, lūdzam doties uz savu trenera profilu.</strong></p>
