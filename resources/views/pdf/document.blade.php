<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }

    .header,
    .footer {
      line-height: 1.1;

    }

    .header p,
    .footer p {
      margin: 2px;
    }

    .recipient,
    .invoice-details {
      margin-bottom: 20px;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .table th {
      background-color: #f4f4f4;
    }

    .highlight {
      font-weight: bold;
    }

    .total {
      margin-top: 10px;
    }

    .bank-details {
      margin-top: 20px;
    }

    .logo {
      text-align: center;
      float: left;
      margin-top: -75px;
      margin-left: -50px;
    }

    .logo img {
      max-width: 400px;
    }

    .flex-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      line-height: 1.1;
    }

    .flex-container .recipient,
    .flex-container .invoice-details {
      width: 48%;
    }
  </style>
</head>

<body>
  <div class="logo">
    <img src="{{ public_path('images/Rechnung.jpg') }}" alt="Logo">
  </div>

  <div class="header" style="float: right">
    <p>Royal Gebäudereinigung GbR</p>
    <p>Helsingborger Str. 4</p>
    <p>28719 Bremen</p>
  </div>
  <div style="clear: both"></div>

  <section class="flex-container">
    <div class="recipient">
      <p><strong>Praxis für Ergotherapie</strong></p>
      <p>Am Wall GbR</p>
      <p>Jana Böhling & Lisa Lingenberg</p>
      <p>Contrescarpe 120, 28195 Bremen</p>
    </div>

    <div class="invoice-details" style="float: right">
      <p>Rechnung Nr.: <span class="highlight">{{ $rechnungNr }}</span></p>
      <p>Kunden Nr.: <span class="highlight">{{ $kundenNr }}</span></p>
      <p>Datum: <span class="highlight">{{ $datum }}</span></p>
    </div>
  </section>

  <p>Rechnung: <span class="highlight">{{ $monat }}</span></p>

  <p>Vielen Dank für Ihren Auftrag. Vereinbarungsgemäß berechnen wir Ihnen hiermit folgende Leistungen:</p>

  <table class="table">
    <thead>
      <tr>
        <th>Dienstleistung / Datum</th>
        <th>Stunden</th>
        <th>Stundenlohn</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $dienstleistungDatum }}</td>
        <td>{{ $stunden }}</td>
        <td>{{ $stundenlohn }}</td>
      </tr>
    </tbody>
  </table>

  <p class="total">
    Summe: <span class="highlight">{{ $summe }} €</span><br>
    Zzgl. MwSt. {{ $zzglMwst }}%: <span class="highlight">{{ ($summe / 100) * $zzglMwst }} €</span><br>
    Gesamtbetrag: <span class="highlight">{{ $gesamtbetrag }} €</span>
  </p>

  <div class="bank-details">
    <p>Bitte überweisen Sie den Rechnungsbetrag innerhalb von 14 Tagen auf unser unten genanntes Konto:</p>
    <p><strong>Mohammad Haji</strong></p>
    <p>IBAN: <span class="highlight">DE25 2905 0101 0083 8416 35</span></p>
    <p>BIC: <span class="highlight">SBREDE22XXX</span></p>
    <p>Verwendungszweck: <span class="highlight">{{ $rechnungNr }}</span></p>
  </div>

  <div class="footer">
    <p>Mit freundlichen Grüßen</p>
    <p>Ihr Royal-Team</p>
    <p>Tel.: +49 1590 1179433</p>
    <p>www.Royal-Reinigung.de</p>
    <p>info@Royal-Reinigung.de</p>
  </div>
</body>

</html>
