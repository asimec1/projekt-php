<?php
$apiUrl = 'https://api.hnb.hr/tecajn-eur/v3';

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function parseRate($value) {
    return (float) str_replace(',', '.', (string)$value);
}

function formatPrice($amount, $currency) {
    return number_format((float)$amount, 2, ',', '.') . ' ' . $currency;
}

/* Demo proizvodi s baznom cijenom u EUR */
$products = [
    [
        'name' => 'Laptop Pro 14',
        'category' => 'Elektronika',
        'description' => 'Lagani prijenosnik za razvoj, nastavu i uredski rad.',
        'price_eur' => 1299.90
    ],
    [
        'name' => 'Wireless Mouse X',
        'category' => 'Dodaci',
        'description' => 'Bežični miš s tihim klikom i dugim trajanjem baterije.',
        'price_eur' => 39.50
    ],
    [
        'name' => '4K Monitor 27',
        'category' => 'Periferija',
        'description' => 'Monitor visoke rezolucije za produktivnost i multimediju.',
        'price_eur' => 349.00
    ],
    [
        'name' => 'Mechanical Keyboard',
        'category' => 'Dodaci',
        'description' => 'Mehanička tipkovnica za programiranje i svakodnevni rad.',
        'price_eur' => 119.99
    ]
];

$selectedCurrency = isset($_GET['currency']) ? strtoupper(trim($_GET['currency'])) : 'USD';

$rates = [
    'EUR' => [
        'rate' => 1.0,
        'country' => 'Eurozona',
        'iso' => 'EMU',
        'code' => '978',
        'buying' => 1.0,
        'middle' => 1.0,
        'selling' => 1.0
    ]
];

$errorMessage = '';
$jsonData = [];
$datumPrimjene = '-';
$brojTecajnice = '-';

/* Dohvat HNB JSON */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$json = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($json === false || $httpCode !== 200) {
    $errorMessage = 'Nije moguće dohvatiti HNB JSON podatke.';
    if (!empty($curlError)) {
        $errorMessage .= ' Detalj: ' . $curlError;
    }
} else {
    $jsonData = json_decode($json, true);

    if (!is_array($jsonData) || empty($jsonData)) {
        $errorMessage = 'JSON odgovor nije ispravan ili nema podataka.';
    } else {
        $datumPrimjene = isset($jsonData[0]['datum_primjene']) ? $jsonData[0]['datum_primjene'] : '-';
        $brojTecajnice = isset($jsonData[0]['broj_tecajnice']) ? $jsonData[0]['broj_tecajnice'] : '-';

        foreach ($jsonData as $item) {
            $currency = isset($item['valuta']) ? strtoupper($item['valuta']) : '';
            if ($currency === '') {
                continue;
            }

            $rates[$currency] = [
                'rate' => parseRate($item['srednji_tecaj'] ?? '0'),
                'country' => $item['drzava'] ?? '',
                'iso' => $item['drzava_iso'] ?? '',
                'code' => $item['sifra_valute'] ?? '',
                'buying' => parseRate($item['kupovni_tecaj'] ?? '0'),
                'middle' => parseRate($item['srednji_tecaj'] ?? '0'),
                'selling' => parseRate($item['prodajni_tecaj'] ?? '0')
            ];
        }
    }
}

if (!isset($rates[$selectedCurrency])) {
    $selectedCurrency = 'EUR';
}

$selectedRate = $rates[$selectedCurrency]['rate'] ?? 1.0;

/* sortiranje valuta za select */
$currencyOptions = array_keys($rates);
sort($currencyOptions);

/* nekoliko istaknutih valuta za kartice */
$featuredCurrencies = ['USD', 'GBP', 'CHF', 'AUD'];

?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HNB Tečajevi i proizvodi</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .fx-page {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 16px 48px;
        }

        .fx-header {
            margin-bottom: 24px;
        }

        .fx-label {
            display: inline-block;
            margin-bottom: 10px;
            padding: 6px 12px;
            border-radius: 999px;
            background: #eef2ff;
            color: #27318b;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .fx-header h1 {
            margin: 0 0 10px 0;
            font-size: 36px;
            line-height: 1.15;
            color: #111827;
        }

        .fx-header p {
            margin: 0;
            color: #4b5563;
            line-height: 1.7;
            max-width: 900px;
        }

        .fx-alert {
            margin: 0 0 20px 0;
            padding: 14px 16px;
            border-radius: 12px;
        }

        .fx-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .fx-card,
        .fx-meta-card,
        .fx-product-card,
        .fx-rate-card,
        .fx-table-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .fx-card {
            padding: 22px;
            margin-bottom: 24px;
        }

        .fx-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: end;
        }

        .fx-form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .fx-form-group label {
            font-weight: 700;
        }

        .fx-form-group select {
            height: 48px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            font-size: 15px;
            box-sizing: border-box;
            background: #fff;
        }

        .fx-btn {
            height: 48px;
            padding: 0 20px;
            border: none;
            border-radius: 12px;
            background: #27318b;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .fx-btn:hover {
            background: #1e3a8a;
        }

        .fx-meta {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .fx-meta-card {
            padding: 18px;
        }

        .fx-meta-title {
            display: block;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .fx-meta-value {
            display: block;
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .fx-section-title {
            margin: 0 0 18px 0;
            font-size: 26px;
            color: #111827;
        }

        .fx-rates-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .fx-rate-card {
            padding: 18px;
        }

        .fx-rate-code {
            display: inline-block;
            margin-bottom: 8px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #eef2ff;
            color: #27318b;
            font-size: 12px;
            font-weight: 700;
        }

        .fx-rate-country {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .fx-rate-value {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .fx-rate-note {
            margin-top: 8px;
            font-size: 13px;
            color: #6b7280;
        }

        .fx-products {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .fx-product-card {
            padding: 22px;
        }

        .fx-product-category {
            display: inline-block;
            margin-bottom: 10px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #f3f4f6;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .fx-product-card h3 {
            margin: 0 0 10px 0;
            font-size: 22px;
            color: #111827;
        }

        .fx-product-card p {
            margin: 0 0 16px 0;
            color: #4b5563;
            line-height: 1.7;
        }

        .fx-price-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-top: 1px solid #e5e7eb;
        }

        .fx-price-row:first-of-type {
            border-top: none;
            padding-top: 0;
        }

        .fx-price-label {
            color: #6b7280;
            font-weight: 700;
        }

        .fx-price-value {
            color: #111827;
            font-weight: 700;
        }

        .fx-table-card {
            padding: 22px;
        }

        .fx-table-wrap {
            overflow-x: auto;
        }

        .fx-table {
            width: 100%;
            min-width: 920px;
            border-collapse: collapse;
        }

        .fx-table th {
            background: #27318b;
            color: #fff;
            text-align: left;
            padding: 14px 16px;
            font-size: 14px;
        }

        .fx-table td {
            padding: 14px 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #374151;
        }

        .fx-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .fx-table tbody tr:hover {
            background: #eef2ff;
        }

        .fx-link-row {
            margin-top: 14px;
        }

        .fx-link-row a {
            color: #27318b;
            font-weight: 700;
            text-decoration: none;
        }

        .fx-link-row a:hover {
            text-decoration: underline;
        }

        @media (max-width: 991px) {
            .fx-meta {
                grid-template-columns: repeat(2, 1fr);
            }

            .fx-rates-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .fx-products {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {
            .fx-header h1 {
                font-size: 28px;
            }

            .fx-form {
                grid-template-columns: 1fr;
            }

            .fx-meta {
                grid-template-columns: 1fr;
            }

            .fx-rates-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<section class="fx-page">
    <div class="fx-header">
        <span class="fx-label">HNB JSON Demo</span>
        <h1>Tečajna lista i proizvodi u više valuta</h1>
        <p>
            Ovaj primjer dohvaća HNB tečajnu listu iz API-ja, a zatim prikazuje nekoliko demo proizvoda
            čija se bazna cijena u EUR automatski preračunava u odabranu valutu.
        </p>
    </div>

    <?php if (!empty($errorMessage)): ?>
        <div class="fx-alert fx-alert-error"><?php echo h($errorMessage); ?></div>
    <?php else: ?>
        <div class="fx-card">
            <h2 class="fx-section-title">Odabir valute</h2>
            <form method="GET" action="" class="fx-form">
                <input type="hidden" name="menu" value="12">
                <div class="fx-form-group">
                    <label for="currency">Valuta za prikaz cijena</label>
                    <select id="currency" name="currency">
                        <?php foreach ($currencyOptions as $currencyCode): ?>
                            <option value="<?php echo h($currencyCode); ?>" <?php echo $currencyCode === $selectedCurrency ? 'selected' : ''; ?>>
                                <?php echo h($currencyCode); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="fx-btn">Prikaži cijene</button>
                </div>
            </form>
        </div>

        <div class="fx-meta">
            <div class="fx-meta-card">
                <span class="fx-meta-title">Datum primjene</span>
                <span class="fx-meta-value"><?php echo h($datumPrimjene); ?></span>
            </div>
            <div class="fx-meta-card">
                <span class="fx-meta-title">Broj tečajnice</span>
                <span class="fx-meta-value"><?php echo h($brojTecajnice); ?></span>
            </div>
            <div class="fx-meta-card">
                <span class="fx-meta-title">Odabrana valuta</span>
                <span class="fx-meta-value"><?php echo h($selectedCurrency); ?></span>
            </div>
            <div class="fx-meta-card">
                <span class="fx-meta-title">Srednji tečaj</span>
                <span class="fx-meta-value"><?php echo number_format($selectedRate, 4, ',', '.'); ?></span>
            </div>
        </div>

        <h2 class="fx-section-title">Istaknuti tečajevi</h2>
        <div class="fx-rates-grid">
            <?php foreach ($featuredCurrencies as $code): ?>
                <?php if (isset($rates[$code])): ?>
                    <div class="fx-rate-card">
                        <span class="fx-rate-code"><?php echo h($code); ?></span>
                        <div class="fx-rate-country"><?php echo h($rates[$code]['country']); ?></div>
                        <div class="fx-rate-value"><?php echo number_format($rates[$code]['middle'], 4, ',', '.'); ?></div>
                        <div class="fx-rate-note">1 EUR = <?php echo number_format($rates[$code]['middle'], 4, ',', '.'); ?> <?php echo h($code); ?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <h2 class="fx-section-title">Demo proizvodi</h2>
        <div class="fx-products">
            <?php foreach ($products as $product): ?>
                <?php $convertedPrice = $product['price_eur'] * $selectedRate; ?>
                <div class="fx-product-card">
                    <span class="fx-product-category"><?php echo h($product['category']); ?></span>
                    <h3><?php echo h($product['name']); ?></h3>
                    <p><?php echo h($product['description']); ?></p>

                    <div class="fx-price-row">
                        <span class="fx-price-label">Bazna cijena</span>
                        <span class="fx-price-value"><?php echo formatPrice($product['price_eur'], 'EUR'); ?></span>
                    </div>

                    <div class="fx-price-row">
                        <span class="fx-price-label">Preračunata cijena</span>
                        <span class="fx-price-value"><?php echo formatPrice($convertedPrice, $selectedCurrency); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="fx-table-card">
            <h2 class="fx-section-title">Sve valute iz HNB API-ja</h2>
            <div class="fx-table-wrap">
                <table class="fx-table">
                    <thead>
                        <tr>
                            <th>Država</th>
                            <th>ISO</th>
                            <th>Valuta</th>
                            <th>Šifra</th>
                            <th>Kupovni</th>
                            <th>Srednji</th>
                            <th>Prodajni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rates as $code => $rateData): ?>
                            <?php if ($code === 'EUR') continue; ?>
                            <tr>
                                <td><?php echo h($rateData['country']); ?></td>
                                <td><?php echo h($rateData['iso']); ?></td>
                                <td><strong><?php echo h($code); ?></strong></td>
                                <td><?php echo h($rateData['code']); ?></td>
                                <td><?php echo number_format($rateData['buying'], 4, ',', '.'); ?></td>
                                <td><?php echo number_format($rateData['middle'], 4, ',', '.'); ?></td>
                                <td><?php echo number_format($rateData['selling'], 4, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="fx-link-row">
                <a href="<?php echo h($apiUrl); ?>" target="_blank" rel="noopener noreferrer">Otvori HNB JSON izvor</a>
            </p>
        </div>
    <?php endif; ?>
</section>
</body>
</html>