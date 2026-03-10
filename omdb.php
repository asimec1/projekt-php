<?php
print '<section class="omdb-section">';
print '    <div class="omdb-container">';
print '        <div class="omdb-header">';
print '            <span class="omdb-label">OMDB API</span>';
print '            <h1>Search movies with OMDB</h1>';
print '            <p class="omdb-subtitle">Find detailed information about a movie by title and optional year.</p>';
print '        </div>';

$isSubmitted = (isset($_POST['action']) && $_POST['action'] === 'TRUE');

if (!$isSubmitted) {
    print '
        <div class="omdb-card">
            <h2>Search form</h2>
            <p class="omdb-note">
                <strong>Example movies:</strong> Fight Club, Inception, The Matrix
            </p>

            <form class="omdb-form" action="" name="imdbsearch" method="POST">
                <div class="omdb-form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" placeholder="Enter movie title, e.g. Fight Club" required>
                </div>

                <div class="omdb-form-group">
                    <label for="year">Year</label>
                    <input type="text" id="year" name="year" placeholder="Enter year, e.g. 1999" pattern="[0-9]{4}">
                </div>

                <input type="hidden" name="action" value="TRUE">

                <div class="omdb-form-actions">
                    <input type="submit" value="Search movie" class="omdb-btn">
                </div>
            </form>
        </div>';
} else {
    $key = 'be5ea402';
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $year = isset($_POST['year']) ? trim($_POST['year']) : '';

    print '<div class="omdb-card">';
    print '    <div class="omdb-result-header">';
    print '        <h2>Search result</h2>';
    print '        <p><a class="omdb-back" href="index.php?menu=10">← Back to search</a></p>';
    print '    </div>';

    if ($title === '') {
        print '<div class="omdb-alert omdb-alert-error">Please enter a movie title.</div>';
    } else {
        if ($year !== '') {
            $url = 'https://www.omdbapi.com/?apikey=' . urlencode($key) . '&t=' . urlencode($title) . '&y=' . urlencode($year);
        } else {
            $url = 'https://www.omdbapi.com/?apikey=' . urlencode($key) . '&t=' . urlencode($title);
        }

        $json = @file_get_contents($url);
        $_data = json_decode($json, true);

        if (isset($_data['Response']) && $_data['Response'] === 'True') {
            $poster = (!empty($_data['Poster']) && $_data['Poster'] !== 'N/A') ? $_data['Poster'] : 'img/no-image.jpg';

            $firstRating = 'N/A';
            if (!empty($_data['Ratings']) && isset($_data['Ratings'][0]['Source'], $_data['Ratings'][0]['Value'])) {
                $firstRating = $_data['Ratings'][0]['Source'] . ': ' . $_data['Ratings'][0]['Value'];
            }

            $website = (!empty($_data['Website']) && $_data['Website'] !== 'N/A') ? $_data['Website'] : '';

            print '
                <div class="omdb-result">
                    <div class="omdb-poster">
                        <img src="' . htmlspecialchars($poster, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($_data['Title'], ENT_QUOTES, 'UTF-8') . '">
                    </div>

                    <div class="omdb-details">
                        <h3>' . htmlspecialchars($_data['Title'], ENT_QUOTES, 'UTF-8') . '</h3>

                        <div class="omdb-info-grid">
                            <p><strong>Year:</strong> ' . htmlspecialchars($_data['Year'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Rated:</strong> ' . htmlspecialchars($_data['Rated'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Released:</strong> ' . htmlspecialchars($_data['Released'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Runtime:</strong> ' . htmlspecialchars($_data['Runtime'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Genre:</strong> ' . htmlspecialchars($_data['Genre'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Director:</strong> ' . htmlspecialchars($_data['Director'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Writer:</strong> ' . htmlspecialchars($_data['Writer'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Actors:</strong> ' . htmlspecialchars($_data['Actors'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Language:</strong> ' . htmlspecialchars($_data['Language'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Country:</strong> ' . htmlspecialchars($_data['Country'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Awards:</strong> ' . htmlspecialchars($_data['Awards'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>IMDb rating:</strong> ' . htmlspecialchars($_data['imdbRating'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Production:</strong> ' . htmlspecialchars($_data['Production'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                            <p><strong>Rating source:</strong> ' . htmlspecialchars($firstRating, ENT_QUOTES, 'UTF-8') . '</p>
                        </div>

                        <div class="omdb-plot">
                            <strong>Plot:</strong>
                            <p>' . htmlspecialchars($_data['Plot'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</p>
                        </div>';

                        if ($website !== '') {
                            print '<p><strong>Website:</strong> <a href="' . htmlspecialchars($website, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars($website, ENT_QUOTES, 'UTF-8') . '</a></p>';
                        }

            print '
                    </div>
                </div>';
        } else {
            $errorMessage = isset($_data['Error']) ? $_data['Error'] : 'Something went wrong.';
            print '<div class="omdb-alert omdb-alert-error">' . htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') . '</div>';
        }
    }

    print '</div>';
}

print '    </div>';
print '</section>';
?>