<!DOCTYPE html>
<html>

<head>
  <title>{{ $title }}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
  <h1>{{ $title }}</h1>
  <p>Rechnung Nr: {{ $RechnungNr }}</p>
  <p>Kunden Nr: {{ $KundenNr }}</p>
  <p>Datum: {{ $Datum }}</p>
  <p>Monat: {{ $Monat }}</p>
  <p>Dienstleistung / Datum: {{ $DienstleistungDatum }}</p>
  <p>Stunden: {{ $Stunden }}</p>
  <p>Stundenlohn: {{ $Stundenlohn }}</p>
  <p>Summe: {{ $Summe }}</p>
  <p>Zzgl. MwSt: {{ $ZzglMwSt }}%</p>
  <p>Gesamtbetrag: {{ $Gesamtbetrag }}</p>
  <p>Verwendungszweck: {{ $Verwendungszweck }}</p>
</body>

</html>
