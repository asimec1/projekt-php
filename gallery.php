<?php
$galleryItems = [
    1 => [
        'title' => 'Web dizajn',
        'image' => 'gallery/gallery-1.jpg',
        'description' => 'Primjer moderne naslovne slike za web projekt.'
    ],
    2 => [
        'title' => 'Programiranje',
        'image' => 'gallery/gallery-2.jpg',
        'description' => 'Vizual koji prikazuje razvoj web aplikacija i rad s kodom.'
    ],
    3 => [
        'title' => 'API integracija',
        'image' => 'gallery/gallery-3.jpg',
        'description' => 'Primjer stranice s dohvatom podataka putem vanjskog API-ja.'
    ],
    4 => [
        'title' => 'Baza podataka',
        'image' => 'gallery/gallery-4.jpg',
        'description' => 'Rad s podacima i povezivanje PHP aplikacije s bazom podataka.'
    ],
    5 => [
        'title' => 'Frontend sučelje',
        'image' => 'gallery/gallery-5.webp',
        'description' => 'Responzivan prikaz korisničkog sučelja za desktop i mobilne uređaje.'
    ],
    6 => [
        'title' => 'CMS modul',
        'image' => 'gallery/gallery-6.png',
        'description' => 'Administracijsko sučelje za upravljanje sadržajem.'
    ],
    7 => [
        'title' => 'E-commerce',
        'image' => 'gallery/gallery-7.jpg',
        'description' => 'Prikaz webshop funkcionalnosti i proizvoda.'
    ],
    8 => [
        'title' => 'Analitika',
        'image' => 'gallery/gallery-8.jpg',
        'description' => 'Vizualizacija podataka i pregled rezultata rada aplikacije.'
    ]
];

if (isset($_GET['action']) && (int)$_GET['action'] > 0) {

    $galleryId = (int)$_GET['action'];

    if (isset($galleryItems[$galleryId])) {
        $item = $galleryItems[$galleryId];

        echo '<div class="gallery-article-wrap">';
            echo '<article class="gallery-article">';

                echo '<div class="gallery-article-cover">';
                    if (!empty($item['image']) && file_exists($item['image'])) {
                        echo '<img src="' . htmlspecialchars($item['image'], ENT_QUOTES) . '" alt="' . htmlspecialchars($item['title'], ENT_QUOTES) . '" title="' . htmlspecialchars($item['title'], ENT_QUOTES) . '">';
                    } else {
                        echo '<img src="images/no-image.jpg" alt="' . htmlspecialchars($item['title'], ENT_QUOTES) . '" title="' . htmlspecialchars($item['title'], ENT_QUOTES) . '">';
                    }
                echo '</div>';

                echo '<div class="gallery-article-body">';
                    echo '<h1>' . htmlspecialchars($item['title'], ENT_QUOTES) . '</h1>';
                    echo '<div class="gallery-article-content">';
                        echo '<p>' . htmlspecialchars($item['description'], ENT_QUOTES) . '</p>';
                    echo '</div>';

                    echo '<div class="gallery-back">';
                        echo '<a href="index.php?menu=' . (int)$menu . '">&larr; Povratak na galeriju</a>';
                    echo '</div>';
                echo '</div>';

            echo '</article>';
        echo '</div>';
    } else {
        echo '<p>Tražena slika nije pronađena.</p>';
    }

} else {

    echo '<div class="gallery-wrap">';
        echo '<div class="gallery-header">';
            echo '<span class="gallery-label">Gallery</span>';
            echo '<h1>Galerija slika</h1>';
            echo '<p>Primjer hardkodirane galerije slika u grid prikazu.</p>';
        echo '</div>';

        echo '<div class="gallery-grid">';

            foreach ($galleryItems as $id => $item) {
                echo '<div class="gallery-card">';
                    echo '<a class="gallery-card-link" href="index.php?menu=' . (int)$menu . '&amp;action=' . (int)$id . '">';

                        echo '<div class="gallery-card-media">';
                            if (!empty($item['image']) && file_exists($item['image'])) {
                                echo '<img src="' . htmlspecialchars($item['image'], ENT_QUOTES) . '" alt="' . htmlspecialchars($item['title'], ENT_QUOTES) . '" title="' . htmlspecialchars($item['title'], ENT_QUOTES) . '">';
                            } else {
                                echo '<img src="images/no-image.jpg" alt="' . htmlspecialchars($item['title'], ENT_QUOTES) . '" title="' . htmlspecialchars($item['title'], ENT_QUOTES) . '">';
                            }
                        echo '</div>';

                        echo '<div class="gallery-card-body">';
                            echo '<h2>' . htmlspecialchars($item['title'], ENT_QUOTES) . '</h2>';
                            echo '<p>' . htmlspecialchars($item['description'], ENT_QUOTES) . '</p>';
                        echo '</div>';

                    echo '</a>';
                echo '</div>';
            }

        echo '</div>';
    echo '</div>';
}
?>