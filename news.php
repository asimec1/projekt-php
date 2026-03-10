<?php
if (isset($_GET['action']) && (int)$_GET['action'] > 0) {

    $news_id = (int)$_GET['action'];

    $query  = "SELECT * FROM news";
    $query .= " WHERE id=" . $news_id;
    $query .= " AND archive='N'";
    $query .= " LIMIT 1";
    $result = mysqli_query($MySQL, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        echo '<div class="news-article-wrap">';
            echo '<article class="news-article">';

                if (!empty($row['picture']) && file_exists('news/' . $row['picture'])) {
                    echo '<div class="news-article-cover">';
                        echo '<img src="news/' . htmlspecialchars($row['picture'], ENT_QUOTES) . '" alt="' . htmlspecialchars($row['title'], ENT_QUOTES) . '" title="' . htmlspecialchars($row['title'], ENT_QUOTES) . '">';
                    echo '</div>';
                }
                else {
                    echo '<div class="news-article-cover">';
                        echo '<img src="images/no-image.jpg" alt="' . htmlspecialchars($row['title'], ENT_QUOTES) . '" title="' . htmlspecialchars($row['title'], ENT_QUOTES) . '">';
                    echo '</div>';
                }

                echo '<div class="news-article-body">';
                    echo '<time datetime="' . htmlspecialchars($row['date'], ENT_QUOTES) . '">' . pickerDateToMysql($row['date']) . '</time>';
                    echo '<h1>' . htmlspecialchars($row['title'], ENT_QUOTES) . '</h1>';

                    echo '<div class="news-article-content">';
                        echo html_entity_decode($row['description'], ENT_QUOTES, 'UTF-8');
                    echo '</div>';

                    if (!empty($row['video']) && file_exists('news/' . $row['video'])) {
                        echo '<div class="news-article-video">';
                            echo '<h3>Video</h3>';
                            echo '<video controls preload="metadata">';
                                echo '<source src="news/' . htmlspecialchars($row['video'], ENT_QUOTES) . '" type="video/mp4">';
                                echo 'Vaš preglednik ne podržava video element.';
                            echo '</video>';
                        echo '</div>';
                    }

                    echo '<div class="news-back">';
                        echo '<a href="index.php?menu=' . (int)$menu . '">&larr; Povratak na sve vijesti</a>';
                    echo '</div>';
                echo '</div>';

            echo '</article>';
        echo '</div>';
    }
    else {
        echo '<p>Tražena vijest nije pronađena.</p>';
    }

}
else {

    $query  = "SELECT * FROM news";
	$query .= " WHERE archive='N'";
	$query .= " ORDER BY date DESC";
	$result = mysqli_query($MySQL, $query);

	echo '<div class="news-list">';

	while ($row = mysqli_fetch_array($result)) {

		echo '<div class="news-card">';

			if (!empty($row['picture']) && file_exists('news/' . $row['picture'])) {
				echo '<div class="news-card-media">';
					echo '<img src="news/' . htmlspecialchars($row['picture'], ENT_QUOTES) . '" alt="' . htmlspecialchars($row['title'], ENT_QUOTES) . '" title="' . htmlspecialchars($row['title'], ENT_QUOTES) . '">';

					if (!empty($row['video']) && file_exists('news/' . $row['video'])) {
						echo '<span class="news-badge-video">VIDEO</span>';
					}

				echo '</div>';
			}
			else {
				echo '<div class="news-card-media">';
					echo '<img src="images/no-image.jpg" alt="' . htmlspecialchars($row['title'], ENT_QUOTES) . '" title="' . htmlspecialchars($row['title'], ENT_QUOTES) . '">';
					if (!empty($row['video']) && file_exists('news/' . $row['video'])) {
						echo '<span class="news-badge-video">VIDEO</span>';
					}
				echo '</div>';
			}

			echo '<div class="news-card-body">';
				echo '<time datetime="' . htmlspecialchars($row['date'], ENT_QUOTES) . '">' . pickerDateToMysql($row['date']) . '</time>';
				echo '<h2>' . htmlspecialchars($row['title'], ENT_QUOTES) . '</h2>';
				echo '<p>' . newsExcerpt($row['description'], 300) . '</p>';

				echo '<div class="news-card-footer">';
					echo '<a class="news-btn" href="index.php?menu=' . (int)$menu . '&amp;action=' . (int)$row['id'] . '">Prikaži više</a>';
				echo '</div>';
			echo '</div>';

		echo '</div>';
	}

	echo '</div>';
}
?>