@php(
    $user = auth()->user()
)

<h3 style="margin: 0 0 1rem 0;">Rezervācijas pieteikums noraidīts</h3>
<p>Rezervācijas pieteikums pie
    trenera <strong>{{ $reservation->instructor->user->name }} {{ $reservation->instructor->user->surname }}
        (kategorija {{ $reservation->instructor->certificate->category->name }})</strong>
    tika noraidīts.</p>

<p style="margin: 0;"><strong>Sākuma
        laiks</strong>: {{ $reservation->InstructorAvailability->start_time->format('d.m.Y H.i') }}</p>
<p style="margin: 0 0 1rem 0;"><strong>Beigu
        laiks</strong>: {{ $reservation->InstructorAvailability->end_time->format('d.m.Y H.i') }}</p>

<p style="margin: 0;"><strong>Trenera norādītais noraidījuma iemesls</strong>: {{ $reason }}</p>
