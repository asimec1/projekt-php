<?php
$apiUrl = 'https://api.hnb.hr/tecajn-eur/v3?format=xml';

print '<section class="hnb-section">';
print '    <div class="hnb-header">';
print '        <span class="hnb-label">HNB XML</span>';
print '        <h2>HNB Rate List (XML)</h2>';
print '        <p>Direct fetch from the official HNB XML endpoint.</p>';
print '    </div>';

/* FETCH XML */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$xmlString = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($xmlString === false || $httpCode !== 200) {
    print '<div class="hnb-alert hnb-alert-error">';
    print '<strong>Greška:</strong> Nije moguće dohvatiti XML podatke.';
    if (!empty($curlError)) {
        print '<br>' . htmlspecialchars($curlError, ENT_QUOTES, 'UTF-8');
    }
    print '</div>';
    print '</section>';
    exit;
}

/* PARSE XML */
libxml_use_internal_errors(true);
$xml = simplexml_load_string($xmlString);

if ($xml === false) {
    print '<div class="hnb-alert hnb-alert-error">';
    print '<strong>Greška:</strong> XML dokument nije moguće parsirati.';
    print '</div>';
    print '</section>';
    exit;
}

$totalItems = isset($xml->item) ? count($xml->item) : 0;
$datumPrimjene = ($totalItems > 0 && isset($xml->item[0]->datum_primjene))
    ? (string)$xml->item[0]->datum_primjene
    : '-';

print '<div class="hnb-meta">';
print '    <div class="hnb-meta-card">';
print '        <span class="hnb-meta-title">Source</span>';
print '        <span class="hnb-meta-value">HNB API v3 XML</span>';
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
print '    <a href="' . htmlspecialchars($apiUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">Open XML source</a>';
print '</p>';

if ($totalItems === 0) {
    print '<div class="hnb-alert hnb-alert-error">Nema dostupnih podataka za prikaz.</div>';
    print '</section>';
    exit;
}

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

foreach ($xml->item as $item) {
    $broj_tecajnice = (string)$item->broj_tecajnice;
    $datum_primjene = (string)$item->datum_primjene;
    $drzava = (string)$item->drzava;
    $drzava_iso = (string)$item->drzava_iso;
    $sifra_valute = (string)$item->sifra_valute;
    $valuta = (string)$item->valuta;
    $kupovni_tecaj = (string)$item->kupovni_tecaj;
    $srednji_tecaj = (string)$item->srednji_tecaj;
    $prodajni_tecaj = (string)$item->prodajni_tecaj;

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