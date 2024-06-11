@php(
    $user = auth()->user()
)

<h3 style="margin: 0 0 1rem 0;">Rezervācijas pieteikums atcelts</h3>
<p>Rezervācijas pieteikums pie
    trenera <strong>{{ $reservation->instructor->user->name }} {{ $reservation->instructor->user->surname }}
        (kategorija {{ $reservation->instructor->certificate->category->name }})</strong>
    tika atcelts.</p>

<p style="margin: 0;"><strong>Sākuma
        laiks</strong>: {{ $reservation->InstructorAvailability->start_time->format('d.m.Y H.i') }}</p>
<p style="margin: 0 0 1rem 0;"><strong>Beigu
        laiks</strong>: {{ $reservation->InstructorAvailability->end_time->format('d.m.Y H.i') }}</p>

<p style="margin: 0;"><strong>Pieteikumu atcēlušais
        lietotājs</strong>: {{ $user->name }} {{ $user->surname }}
</p>
<p style="margin: 0 0 2rem 0;"><strong>Lietotāja norādītais iemesls</strong>: {{ $reason }}</p>

@if ($toInstructor)
    <p style="margin-bottom: 0;"><strong>Šis laiks automātiski tika atzīmēts kā atkal pieejams. Lai pārvaldītu savus
            pieejamos laikus, lūdzam doties uz savu trenera profilu.</strong></p>
@endif
