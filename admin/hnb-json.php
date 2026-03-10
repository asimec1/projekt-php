<?php
$apiUrl = 'https://api.hnb.hr/tecajn-eur/v3';

print '<section class="hnb-section">';
print '    <div class="hnb-header">';
print '        <span class="hnb-label">HNB JSON</span>';
print '        <h2>HNB Rate List (JSON)</h2>';
print '        <p>Direct fetch from the official HNB JSON endpoint.</p>';
print '    </div>';

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
    print '<div class="hnb-alert hnb-alert-error">';
    print '<strong>Greška:</strong> Nije moguće dohvatiti JSON podatke.';
    if (!empty($curlError)) {
        print '<br>' . htmlspecialchars($curlError, ENT_QUOTES, 'UTF-8');
    }
    print '</div>';
    print '</section>';
    exit;
}

$json_data = json_decode($json, true);

if (!is_array($json_data) || empty($json_data)) {
    print '<div class="hnb-alert hnb-alert-error">';
    print '<strong>Greška:</strong> JSON odgovor nije ispravan ili nema podataka.';
    print '</div>';
    print '</section>';
    exit;
}

$totalItems = count($json_data);
$datumPrimjene = isset($json_data[0]['datum_primjene']) ? $json_data[0]['datum_primjene'] : '-';

print '<div class="hnb-meta">';
print '    <div class="hnb-meta-card">';
print '        <span class="hnb-meta-title">Source</span>';
print '        <span class="hnb-meta-value">HNB API v3 JSON</span>';
print '    </div>';
print '    <div class="hnb-meta-card">';
print '        <span class="hnb-meta-title">Date</span>';
print '        <span class="hnb-meta-value">' . htmlspecialchars($datumPrimjene, ENT_QUOTES, 'UTF-8') . '</span>';
print '    </div>';
print '    <div class="hnb-meta-card">';
print '        <span class="hnb-meta-title">Currencies</span>';
print '        <span class="hnb-meta-value">' . (int)$totalItems . '</span>';
print '    </div>';
print '</div>';

print '<p class="hnb-link-row">';
print '    <a href="' . htmlspecialchars($apiUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">Open JSON source</a>';
print '</p>';

print '<div class="hnb-table-wrap">';
print '    <table class="hnb-table">';
print '        <thead>';
print '            <tr>';
print '                <th>Exchange No.</th>';
print '                <th>Date</th>';
print '                <th>Country</th>';
print '                <th>ISO</th>';
print '                <th>Currency code</th>';
print '                <th>Currency</th>';
print '                <th>Buying</th>';
print '                <th>Middle</th>';
print '                <th>Selling</th>';
print '            </tr>';
print '        </thead>';
print '        <tbody>';

foreach ($json_data as $item) {
    $broj_tecajnice = isset($item['broj_tecajnice']) ? $item['broj_tecajnice'] : '';
    $datum_primjene = isset($item['datum_primjene']) ? $item['datum_primjene'] : '';
    $drzava = isset($item['drzava']) ? $item['drzava'] : '';
    $drzava_iso = isset($item['drzava_iso']) ? $item['drzava_iso'] : '';
    $sifra_valute = isset($item['sifra_valute']) ? $item['sifra_valute'] : '';
    $valuta = isset($item['valuta']) ? $item['valuta'] : '';
    $kupovni_tecaj = isset($item['kupovni_tecaj']) ? $item['kupovni_tecaj'] : '';
    $srednji_tecaj = isset($item['srednji_tecaj']) ? $item['srednji_tecaj'] : '';
    $prodajni_tecaj = isset($item['prodajni_tecaj']) ? $item['prodajni_tecaj'] : '';

    print '<tr>';
    print '    <td>' . htmlspecialchars($broj_tecajnice, ENT_QUOTES, 'UTF-8') . '</td>';
    print '    <td>' . htmlspecialchars($datum_primjene, ENT_QUOTES, 'UTF-8') . '</td>';
    print '    <td>' . htmlspecialchars($drzava, ENT_QUOTES, 'UTF-8') . '</td>';
    print '    <td>' . htmlspecialchars($drzava_iso, ENT_QUOTES, 'UTF-8') . '</td>';
    print '    <td>' . htmlspecialchars($sifra_valute, ENT_QUOTES, 'UTF-8') . '</td>';
    print '    <td><strong>' . htmlspecialchars($valuta, ENT_QUOTES, 'UTF-8') . '</strong></td>';
    print '    <td>' . htmlspecialchars($kupovni_tecaj, ENT_QUOTES, 'UTF-8') . '</td>';
    print '    <td>' . htmlspecialchars($srednji_tecaj, ENT_QUOTES, 'UTF-8') . '</td>';
    print '    <td>' . htmlspecialchars($prodajni_tecaj, ENT_QUOTES, 'UTF-8') . '</td>';
    print '</tr>';
}

print '        </tbody>';
print '    </table>';
print '</div>';
print '</section>';
?>