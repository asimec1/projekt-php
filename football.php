<?php
function fetchFootballMatches($competitionCode = 'PL', $matchday = 11, $season = '', $status = '')
{
    $apiToken = '0ca84118345643b18d1f55f21b972d50';

    $params = array();

    if (!empty($matchday)) {
        $params[] = 'matchday=' . urlencode($matchday);
    }

    if (!empty($season)) {
        $params[] = 'season=' . urlencode($season);
    }

    if (!empty($status)) {
        $params[] = 'status=' . urlencode($status);
    }

    $queryString = !empty($params) ? ('?' . implode('&', $params)) : '';
    $url = 'https://api.football-data.org/v4/competitions/' . urlencode($competitionCode) . '/matches' . $queryString;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Auth-Token: ' . $apiToken
    ));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false || $httpCode !== 200) {
        return array(
            'success' => false,
            'error' => !empty($curlError) ? $curlError : ('API request failed. HTTP code: ' . $httpCode),
            'data' => null
        );
    }

    $data = json_decode($response, true);

    if (!is_array($data)) {
        return array(
            'success' => false,
            'error' => 'Invalid JSON response.',
            'data' => null
        );
    }

    return array(
        'success' => true,
        'error' => '',
        'data' => $data
    );
}

function footballScore($match)
{
    $home = isset($match['score']['fullTime']['home']) && $match['score']['fullTime']['home'] !== null
        ? $match['score']['fullTime']['home']
        : '-';

    $away = isset($match['score']['fullTime']['away']) && $match['score']['fullTime']['away'] !== null
        ? $match['score']['fullTime']['away']
        : '-';

    return $home . ' : ' . $away;
}

function footballStatusLabel($status)
{
    $map = array(
        'SCHEDULED' => 'Scheduled',
        'TIMED' => 'Timed',
        'IN_PLAY' => 'Live',
        'PAUSED' => 'Paused',
        'FINISHED' => 'Finished',
        'POSTPONED' => 'Postponed',
        'CANCELLED' => 'Cancelled',
        'SUSPENDED' => 'Suspended'
    );

    return isset($map[$status]) ? $map[$status] : $status;
}

$matchday = isset($_GET['matchday']) ? (int)$_GET['matchday'] : 11;
$season = isset($_GET['season']) ? trim($_GET['season']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$result = fetchFootballMatches('PL', $matchday, $season, $status);

print '<section class="football-section">';
print '    <div class="football-container">';
print '        <div class="football-header">';
print '            <span class="football-label">Football API</span>';
print '            <h1>Premier League Matches</h1>';
print '            <p class="football-subtitle">Browse Premier League fixtures and results by matchday.</p>';
print '        </div>';

print '        <div class="football-card football-form-card">';
print '            <form method="GET" action="" class="football-form">';
print '                <input type="hidden" name="menu" value="11">';

print '                <div class="football-form-group">';
print '                    <label for="matchday">Matchday</label>';
print '                    <input type="number" min="1" max="38" id="matchday" name="matchday" value="' . htmlspecialchars($matchday, ENT_QUOTES, 'UTF-8') . '">';
print '                </div>';

print '                <div class="football-form-group">';
print '                    <label for="season">Season</label>';
print '                    <input type="text" id="season" name="season" value="' . htmlspecialchars($season, ENT_QUOTES, 'UTF-8') . '" placeholder="e.g. 2024">';
print '                </div>';

print '                <div class="football-form-group">';
print '                    <label for="status">Status</label>';
print '                    <select id="status" name="status">
                                <option value="">All</option>
                                <option value="SCHEDULED"' . ($status === 'SCHEDULED' ? ' selected' : '') . '>Scheduled</option>
                                <option value="TIMED"' . ($status === 'TIMED' ? ' selected' : '') . '>Timed</option>
                                <option value="IN_PLAY"' . ($status === 'IN_PLAY' ? ' selected' : '') . '>Live</option>
                                <option value="PAUSED"' . ($status === 'PAUSED' ? ' selected' : '') . '>Paused</option>
                                <option value="FINISHED"' . ($status === 'FINISHED' ? ' selected' : '') . '>Finished</option>
                                <option value="POSTPONED"' . ($status === 'POSTPONED' ? ' selected' : '') . '>Postponed</option>
                              </select>';
print '                </div>';

print '                <div class="football-form-actions">';
print '                    <button type="submit" class="football-btn">Show matches</button>';
print '                </div>';
print '            </form>';
print '        </div>';

if (!$result['success']) {
    print '<div class="football-alert football-alert-error">' . htmlspecialchars($result['error'], ENT_QUOTES, 'UTF-8') . '</div>';
} else {
    $data = $result['data'];
    $competitionName = isset($data['competition']['name']) ? $data['competition']['name'] : 'Competition';
    $competitionEmblem = isset($data['competition']['emblem']) ? $data['competition']['emblem'] : '';
    $seasonStart = isset($data['filters']['season']) ? $data['filters']['season'] : '';
    $matchCount = isset($data['resultSet']['count']) ? (int)$data['resultSet']['count'] : 0;

    print '<div class="football-meta">';
    print '    <div class="football-meta-card">';
    print '        <span class="football-meta-title">Competition</span>';
    print '        <span class="football-meta-value">' . htmlspecialchars($competitionName, ENT_QUOTES, 'UTF-8') . '</span>';
    print '    </div>';
    print '    <div class="football-meta-card">';
    print '        <span class="football-meta-title">Matchday</span>';
    print '        <span class="football-meta-value">' . htmlspecialchars($matchday, ENT_QUOTES, 'UTF-8') . '</span>';
    print '    </div>';
    print '    <div class="football-meta-card">';
    print '        <span class="football-meta-title">Matches</span>';
    print '        <span class="football-meta-value">' . $matchCount . '</span>';
    print '    </div>';
    print '</div>';

    if (!empty($competitionEmblem)) {
        print '<div class="football-emblem-wrap"><img class="football-emblem" src="' . htmlspecialchars($competitionEmblem, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($competitionName, ENT_QUOTES, 'UTF-8') . '"></div>';
    }

    if (!empty($data['matches'])) {
        print '<div class="football-matches">';

        foreach ($data['matches'] as $match) {
            $homeTeam = isset($match['homeTeam']['name']) ? $match['homeTeam']['name'] : 'Home team';
            $awayTeam = isset($match['awayTeam']['name']) ? $match['awayTeam']['name'] : 'Away team';
            $utcDate = isset($match['utcDate']) ? $match['utcDate'] : '';
            $statusLabel = footballStatusLabel(isset($match['status']) ? $match['status'] : '');
            $score = footballScore($match);
            $venue = isset($match['venue']) && !empty($match['venue']) ? $match['venue'] : 'N/A';
            $stage = isset($match['stage']) ? $match['stage'] : 'N/A';

            $dateText = $utcDate;
            if (!empty($utcDate)) {
                $timestamp = strtotime($utcDate);
                if ($timestamp !== false) {
                    $dateText = date('d.m.Y. H:i', $timestamp) . ' UTC';
                }
            }

            print '<article class="football-match-card">';
            print '    <div class="football-match-top">';
            print '        <span class="football-badge">' . htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8') . '</span>';
            print '        <span class="football-date">' . htmlspecialchars($dateText, ENT_QUOTES, 'UTF-8') . '</span>';
            print '    </div>';

            print '    <div class="football-teams">';
            print '        <div class="football-team football-home">' . htmlspecialchars($homeTeam, ENT_QUOTES, 'UTF-8') . '</div>';
            print '        <div class="football-score">' . htmlspecialchars($score, ENT_QUOTES, 'UTF-8') . '</div>';
            print '        <div class="football-team football-away">' . htmlspecialchars($awayTeam, ENT_QUOTES, 'UTF-8') . '</div>';
            print '    </div>';

            print '    <div class="football-match-bottom">';
            print '        <span><strong>Stage:</strong> ' . htmlspecialchars($stage, ENT_QUOTES, 'UTF-8') . '</span>';
            print '        <span><strong>Venue:</strong> ' . htmlspecialchars($venue, ENT_QUOTES, 'UTF-8') . '</span>';
            print '    </div>';
            print '</article>';
        }

        print '</div>';
    } else {
        print '<div class="football-alert">No matches found for the selected filters.</div>';
    }
}

print '    </div>';
print '</section>';
?>