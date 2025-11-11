@php
  $currency = $payout->tontine->settings['currency'] ?? 'XAF';
  $client = $payout->client;
  $clientName = trim(($client?->first_name ?? '').' '.($client?->last_name ?? '')) ?: '-';
  $daily = (float)($payout->tontine?->daily_amount ?? 0);
  $effectiveDays = $daily > 0 ? (int) round(($payout->amount_gross ?? 0) / $daily) : 0;
  $company = [
    'name'        => 'Mbenda Gest',
    'address'     => 'Moanda, Gabon',
    'phone'       => '066083193 / 077402098',
    'email'       => 'contact@mbendagest.com',
    'website'     => 'www.mbendagest.com',
    'registration'=> '',
  ];
  $receiptNo = sprintf('R-%06d', $payout->id);
  $logoBase64 = null;
  if (empty($skipLogo)) {
    $logoPath = public_path('images/logo.png');
    if (!file_exists($logoPath)) $logoPath = public_path('logo.png');
    if (file_exists($logoPath)) {
      $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));
    }
  }
@endphp
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reçu {{ $receiptNo }}</title>
  <style>
    * { box-sizing:border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#111827; margin:24px; }
    .col-left { float:left; width:55%; }
    .col-right { float:right; width:45%; text-align:right; }
    .clear { clear:both; }
    .logo { max-height:60px; width:auto; display:block; }
    .title { font-weight:700; font-size:20px; margin:0 0 4px; }
    .badge { display:inline-block; padding:3px 10px; border:1px solid #d1d5db; border-radius:4px; font-size:11px; background:#f3f4f6; }
    .muted { color:#6b7280; font-size:11px; line-height:1.3; }
    .divider { height:1px; background:#e5e7eb; margin:16px 0; }
    table.grid { width:100%; border-collapse:collapse; margin-top:8px; }
    table.grid th { background:#f9fafb; text-align:left; font-weight:600; color:#374151; padding:8px; border:1px solid #e5e7eb; font-size:12px; }
    table.grid td { padding:8px; border:1px solid #e5e7eb; font-size:12px; vertical-align:top; }
    table.kv { width:100%; border-collapse:collapse; }
    table.kv td { padding:6px 8px; vertical-align:top; }
    .key { color:#6b7280; width:40%; }
    table.totals { width:100%; border-collapse:collapse; margin-top:12px; }
    table.totals td { padding:8px; border:1px solid #e5e7eb; }
    .label { background:#f9fafb; color:#374151; font-weight:600; }
    .value { text-align:right; font-weight:700; }
    .amount-big { font-size:16px; font-weight:800; color:#065f46; }
    .note { margin-top:10px; font-size:11px; color:#4b5563; }
    .footer { margin-top:26px; font-size:11px; color:#6b7280; width:100%; }
    .sign { border-top:1px solid #e5e7eb; padding-top:6px; margin-top:34px; text-align:center; width:45%; display:inline-block; }
  </style>
</head>
<body>
  <div>
    <div class="col-left">
      @if($logoBase64)
        <img class="logo" src="{{ $logoBase64 }}" alt="Logo">
      @else
        <div style="font-weight:800; font-size:18px;">{{ $company['name'] }}</div>
      @endif
      <div style="margin-top:6px;">
        <div class="muted">{{ $company['address'] }}</div>
        @if($company['registration']) <div class="muted">Registre: {{ $company['registration'] }}</div> @endif
        <div class="muted">Tél: {{ $company['phone'] }}</div>
        <div class="muted">Email: {{ $company['email'] }}</div>
        <div class="muted">{{ $company['website'] }}</div>
      </div>
    </div>
    <div class="col-right">
      <h1 class="title">Reçu de paiement</h1>
      <div class="badge">{{ $receiptNo }}</div>
      <div class="muted" style="margin-top:8px;">Émis le: {{ optional($payout->paid_at)->format('d/m/Y H:i') }}</div>
      <div class="muted">Méthode: Espèces</div>
    </div>
    <div class="clear"></div>
  </div>

  <div class="divider"></div>

  <table class="grid">
    <tr><th>Informations du client</th></tr>
    <tr>
      <td>
        <table class="kv">
          <tr><td class="key">Nom complet</td><td style="font-weight:600">{{ $clientName }}</td></tr>
          <tr><td class="key">Téléphone</td><td>{{ $client?->phone ?? '-' }}</td></tr>
          <tr><td class="key">Adresse</td><td>{{ $client?->address ?? '-' }}</td></tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="grid" style="margin-top:14px;">
    <tr><th colspan="2">Informations de la tontine</th></tr>
    <tr>
      <td style="width:50%;">
        <table class="kv">
          <tr><td class="key">Code</td><td style="font-weight:600">{{ $payout->tontine?->code ?? '-' }}</td></tr>
          <tr><td class="key">Montant journalier</td><td>{{ number_format($daily,2) }} {{ $currency }}</td></tr>
          <tr><td class="key">Durée (jours)</td><td>{{ $payout->tontine?->duration_days ?? '-' }}</td></tr>
          <tr><td class="key">Frais de collecte</td><td>1 jour (équivalent à 1 jour)</td></tr>
        </table>
      </td>
      <td style="width:50%;">
        <table class="kv">
          <tr><td class="key">Début</td><td>{{ optional($payout->tontine?->start_date)->format('d/m/Y') ?? '-' }}</td></tr>
          <tr><td class="key">Fin prévue</td><td>{{ optional($payout->tontine?->expected_end_date)->format('d/m/Y') ?? '-' }}</td></tr>
          <tr><td class="key">Jours pris en compte</td><td>{{ $effectiveDays }}</td></tr>
          <tr><td class="key">Statut</td><td>{{ ucfirst($payout->tontine?->status ?? '-') }}</td></tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="totals">
    <tr>
      <td class="label" style="width:70%;">Montant brut ({{ $effectiveDays }} × {{ number_format($daily,2) }} {{ $currency }})</td>
      <td class="value" style="width:30%;">{{ number_format($payout->amount_gross,2) }} {{ $currency }}</td>
    </tr>
    <tr>
      <td class="label">Frais de collecte (1 jour)</td>
      <td class="value">- {{ number_format($payout->commission_amount,2) }} {{ $currency }}</td>
    </tr>
    <tr>
      <td class="label">Montant net remis au client</td>
      <td class="value amount-big">{{ number_format($payout->amount_net,2) }} {{ $currency }}</td>
    </tr>
  </table>

  <div class="note">
    Ce reçu atteste de la remise en espèces du montant net ci-dessus au client mentionné.
  </div>

  @if($payout->notes)
    <div class="divider"></div>
    <div style="font-weight:600;">Notes</div>
    <div style="white-space:pre-line;">{{ $payout->notes }}</div>
  @endif

  <div class="footer">
    <div class="sign">Signature client</div>
    <div class="sign" style="float:right;">Signature admin — {{ $payout->admin?->name ?? $payout->admin?->email ?? '-' }}</div>
    <div class="clear"></div>
  </div>
</body>
</html>