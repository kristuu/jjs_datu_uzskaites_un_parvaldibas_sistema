<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation PDF</title>
    <style>
        body {
            font-size: 12px !important;
        }

        *, p, h1, h2, h3, h4, h5, h6, span, th, td {
            font-family: Roboto, sans-serif !important;
        }

        .invoice-info-tr .header {
            font-weight: bold;
            width: 45%;
            vertical-align: middle;
        }

        .invoice-info-tr td:not(.header) {
            width: 55%;
            vertical-align: middle;
        }

        .specifications-table {
            width: 100%;
            border-collapse: collapse;
        }

        .specifications-table .header {
            font-weight: bold;
            vertical-align: top;
            width: 45%;
        }

        .specifications-table td:not(.header) {
            font-weight: normal;
            vertical-align: top;
            height: 55%;
        }

        .specifications-table-inner {
            width: 100%;
            border-collapse: collapse;
        }

        .specifications-table-inner td {
            vertical-align: top;
        }

    </style>
</head>
<body>
<div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
        <tbody>
        <tr>
            <td style="text-align: left; width: 40%;">
                <img src="img/yrs_logo.png" alt="logo" style="width: 125px;">
            </td>
            <td style="width: 60%;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tbody>
                    <tr>
                        <td style="font-weight: bold; font-size: 1.25rem; padding-bottom: 0.5rem; border-bottom: 1px solid black">
                            RĒĶINS Nr. {{ $reservation->id }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tbody>
                                <tr class="invoice-info-tr">
                                    <td class="header" style="padding-top: 0.5rem;">
                                        Izrakstīšanas datums
                                    </td>
                                    <td style="padding-top: 0.5rem;">
                                        {{ $reservation->created_at->format('d.m.Y') }}
                                    </td>
                                </tr>
                                <tr class="invoice-info-tr">
                                    <td class="header">Apmaksāt līdz</td>
                                    <td>{{ $reservation->created_at->addDays(7)->format('d.m.Y') }}</td>
                                </tr>
                                <tr class="invoice-info-tr">
                                    <td class="header">Apmaksas termiņš</td>
                                    <td>7 (septiņas) kalendārās dienas</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <div style="padding: 1rem; border: 1px solid lightgray; border-radius: 0.5rem; margin-bottom: 1rem;">
        <table class="specifications-table">
            <tbody>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table class="specifications-table-inner">
                        <tbody>
                        <tr>
                            <td class="header">
                                Pakalpojuma sniedzējs
                            </td>
                            <td>
                                Jauno Jātnieku skola
                            </td>
                        </tr>
                        <tr>
                            <td class="header">
                                Juridiskā adrese
                            </td>
                            <td>
                                Mārupes iela 4<br/>Rīga, LV-1002
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <table class="specifications-table-inner">
                        <tbody>
                        <tr>
                            <td class="header">
                                Reģistrācijas numurs
                            </td>
                            <td>
                                40008114442
                            </td>
                        </tr>
                        <tr>
                            <td class="header">
                                PVN numurs
                            </td>
                            <td>
                                LV40008114442
                            </td>
                        </tr>
                        <tr>
                            <td class="header">
                                Banka
                            </td>
                            <td>
                                AS Swedbank, HABALV22
                            </td>
                        </tr>
                        <tr>
                            <td class="header">
                                Bankas konta numurs
                            </td>
                            <td>
                                LV19HABA0551017883565
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="padding: 1rem; border: 1px solid lightgray; border-radius: 0.5rem; margin-bottom: 2rem;">
        <table class="specifications-table">
            <tbody>
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table class="specifications-table-inner">
                        <tbody>
                        <tr>
                            <td class="header">
                                Pakalpojuma saņēmējs
                            </td>
                            <td>
                                {{ $reservation->user->name . ' ' . $reservation->user->surname }}
                            </td>
                        </tr>
                        <tr>
                            <td class="header">
                                Adrese
                            </td>
                            <td>
                                Plānupes iela 7, dz. 8<br/>Inčukalns, Siguldas novads, LV-2141
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <table class="specifications-table-inner">
                        <tbody>
                        <tr>
                            <td class="header">
                                Personas kods
                            </td>
                            <td>
                                {{ substr_replace($reservation->user->person_code, '-', 6, 0) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="header">
                                PVN numurs
                            </td>
                            <td>
                                {{ substr_replace($reservation->user->person_code, '-', 6, 0) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="header">
                                Banka
                            </td>
                            <td>
                                AS Swedbank, HABALV22
                            </td>
                        </tr>
                        <tr>
                            <td class="header">
                                Bankas konta numurs
                            </td>
                            <td>
                                {{ $reservation->user->iban_code ?? '-' }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: center;">
        <thead>
        <tr>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Nosaukums</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Daudzums</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Norises datums</th>
            <th style="border-bottom: 1px solid #ddd; padding: 8px;">Sākuma - Beigu laiks</th>
        </tr>
        </thead>
        <tbody>
        <tr class="h1 text-center">
            <td style="border-bottom: 1px solid #ddd; padding: 8px;" class="fw-bold">Jāšanas treniņš</td>
            <td style="border-bottom: 1px solid #ddd; padding: 8px;">1</td>
            <td style="border-bottom: 1px solid #ddd; padding: 8px;">{{ $reservation->instructorAvailability->start_time->format('d.m.Y') }}</td>
            <td style="border-bottom: 1px solid #ddd; padding: 8px;">{{ $reservation->instructorAvailability->start_time->format('H:i') }}
                - {{ $reservation->instructorAvailability->end_time->format('H:i') }}</td>
        </tr>
        </tbody>
    </table>
    <div style="margin-top: 20px;">
        <p style="text-align: right; font-family: 'Courier New', monospace;">Rēķins sagatavots
            elektroniski un derīgs bez
            paraksta</p>
    </div>
</div>
</body>
</html>
