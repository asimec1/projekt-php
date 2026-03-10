<?php 
	
	#Add news
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'add_news') {

		$query  = "INSERT INTO news (title, description, archive, date) VALUES (
					'" . mysqli_real_escape_string($MySQL, $_POST['title']) . "',
					'" . mysqli_real_escape_string($MySQL, $_POST['description']) . "',
					'" . mysqli_real_escape_string($MySQL, $_POST['archive']) . "',
					NOW()
				)";
		$result = mysqli_query($MySQL, $query);

		if (!$result) {
			die("Greška kod insert news: " . mysqli_error($MySQL));
		}

		$news_id = mysqli_insert_id($MySQL);

		# picture upload
		if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != '') {
			$ext = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
			$allowed = array('jpg', 'jpeg', 'png', 'gif');

			if (in_array($ext, $allowed)) {
				$_picture = $news_id . '-img-' . time() . '.' . $ext;
				$uploadPath = 'news/' . $_picture;

				if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadPath)) {
					mysqli_query($MySQL, "UPDATE news SET picture='" . mysqli_real_escape_string($MySQL, $_picture) . "' WHERE id=" . (int)$news_id . " LIMIT 1");
				}
			}
		}

		# video upload
		if (isset($_FILES['video']) && $_FILES['video']['error'] == UPLOAD_ERR_OK && $_FILES['video']['name'] != '') {
			$ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
			$allowed_video = array('mp4', 'webm', 'ogg');

			if (in_array($ext, $allowed_video)) {
				$_video = $news_id . '-video-' . time() . '.' . $ext;
				$uploadVideoPath = 'news/' . $_video;

				if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadVideoPath)) {
					mysqli_query($MySQL, "UPDATE news SET video='" . mysqli_real_escape_string($MySQL, $_video) . "' WHERE id=" . (int)$news_id . " LIMIT 1");
				}
			}
		}

		$_SESSION['message'] = '<p>You successfully added news!</p>';
		header("Location: index.php?menu=7&action=2");
		exit;
	}
	
	# Update news
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'edit_news') {

		$query  = "UPDATE news SET 
					title='" . mysqli_real_escape_string($MySQL, $_POST['title']) . "',
					description='" . mysqli_real_escape_string($MySQL, $_POST['description']) . "',
					archive='" . mysqli_real_escape_string($MySQL, $_POST['archive']) . "'
				WHERE id=" . (int)$_POST['edit'] . "
				LIMIT 1";
		$result = mysqli_query($MySQL, $query);

		if (!$result) {
			die("Greška kod update news: " . mysqli_error($MySQL));
		}

		# picture upload
		if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != '') {
			$ext = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));
			$allowed = array('jpg', 'jpeg', 'png', 'gif');

			if (in_array($ext, $allowed)) {
				$_picture = (int)$_POST['edit'] . '-img-' . time() . '.' . $ext;
				$uploadPath = 'news/' . $_picture;

				if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadPath)) {
					$_query  = "UPDATE news SET picture='" . mysqli_real_escape_string($MySQL, $_picture) . "'";
					$_query .= " WHERE id=" . (int)$_POST['edit'] . " LIMIT 1";
					mysqli_query($MySQL, $_query);
				}
			}
		}

		# video upload
		if (isset($_FILES['video']) && $_FILES['video']['error'] == UPLOAD_ERR_OK && $_FILES['video']['name'] != '') {
			$ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
			$allowed_video = array('mp4', 'webm', 'ogg');

			if (in_array($ext, $allowed_video)) {
				$_video = (int)$_POST['edit'] . '-video-' . time() . '.' . $ext;
				$uploadVideoPath = 'news/' . $_video;

				if (move_uploaded_file($_FILES['video']['tmp_name'], $uploadVideoPath)) {
					$_query  = "UPDATE news SET video='" . mysqli_real_escape_string($MySQL, $_video) . "'";
					$_query .= " WHERE id=" . (int)$_POST['edit'] . " LIMIT 1";
					mysqli_query($MySQL, $_query);
				}
			}
		}

		$_SESSION['message'] = '<p>You successfully changed news!</p>';
		header("Location: index.php?menu=7&action=2");
		exit;
	}
	# End update news
	
	# Delete news
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
		
		# Delete picture
        $query  = "SELECT picture FROM news";
        $query .= " WHERE id=".(int)$_GET['delete']." LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
        $row = @mysqli_fetch_array($result);
        @unlink("news/".$row['picture']); 
		
		# Delete news
		$query  = "DELETE FROM news";
		$query .= " WHERE id=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>You successfully deleted news!</p>';
		
		# Redirect
		header("Location: index.php?menu=7&action=2");
	}
	# End delete news
	
	
	#Show news info
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM news";
		$query .= " WHERE id=".$_GET['id'];
		$query .= " ORDER BY date DESC";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>News overview</h2>
		<div class="news">
			<img src="news/' . $row['picture'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
			<h2>' . $row['title'] . '</h2>
			' . $row['description'] . '
			<time datetime="' . $row['date'] . '">' . pickerDateToMysql($row['date']) . '</time>
			<hr>
		</div>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	
	#Add news 
	$use_wysiwyg = true;

	if ($use_wysiwyg) {
		echo '
		<script src="https://cdn.tiny.cloud/1/lsc4v6pk57ymc89vc3nrn4jd87z58oqs2xrxtpxw5qt4qu2d/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
		<script>
		document.addEventListener("DOMContentLoaded", function () {
			tinymce.init({
				selector: "textarea.wysiwyg",
				height: 400,
				menubar: true,
				plugins: "lists link image media table code fullscreen preview wordcount",
				toolbar: "undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | table | code fullscreen preview",
				branding: false,
				setup: function (editor) {
					editor.on("change keyup", function () {
						tinymce.triggerSave();
					});
				}
			});

			const formEdit = document.getElementById("news_form_edit");
			if (formEdit) {
				formEdit.addEventListener("submit", function () {
					tinymce.triggerSave();
				});
			}

			const formAdd = document.getElementById("news_form");
			if (formAdd) {
				formAdd.addEventListener("submit", function () {
					tinymce.triggerSave();
				});
			}
		});
		</script>';
	}

	# Add news
	if (isset($_GET['add']) && $_GET['add'] != '') {

		print '
		<h2>Add news</h2>
		<form action="" id="news_form" name="news_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="add_news">

			<label for="title">Title *</label>
			<input type="text" id="title" name="title" placeholder="News title.." required>

			<label for="description">Description *</label>
			<textarea id="description" class="wysiwyg" name="description" placeholder="News description.."></textarea>

			<label for="picture">Picture</label>
			<input type="file" id="picture" name="picture" accept=".jpg,.jpeg,.png,.gif">

			<label for="video">Video</label>
			<input type="file" id="video" name="video" accept=".mp4,.webm,.ogg">

			<label for="archive">Archive:</label><br />
			<input type="radio" name="archive" value="Y"> YES &nbsp;&nbsp;
			<input type="radio" name="archive" value="N" checked> NO

			<hr>

			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}

	# Edit news
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM news WHERE id=" . (int)$_GET['edit'];
		$result = mysqli_query($MySQL, $query);
		$row = mysqli_fetch_array($result);
		$checked_archive = false;

		print '
		<h2>Edit news</h2>
		<form action="" id="news_form_edit" name="news_form_edit" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="edit_news">
			<input type="hidden" id="edit" name="edit" value="' . (int)$row['id'] . '">

			<label for="title">Title *</label>
			<input type="text" id="title" name="title" value="' . htmlspecialchars($row['title'], ENT_QUOTES) . '" placeholder="News title.." required>

			<label for="description">Description *</label>
			<textarea id="description" class="wysiwyg" name="description" placeholder="News description..">' . htmlspecialchars($row['description'], ENT_QUOTES) . '</textarea>

			<label for="picture">Picture</label>
			<input type="file" id="picture" name="picture" accept=".jpg,.jpeg,.png,.gif">';

			if (!empty($row['picture'])) {
				print '<p>Current picture:<br><img src="news/' . htmlspecialchars($row['picture'], ENT_QUOTES) . '" style="max-width:200px; height:auto;"></p>';
			}

			print '
			<label for="video">Video</label>
			<input type="file" id="video" name="video" accept=".mp4,.webm,.ogg">';

			if (!empty($row['video'])) {
				print '<p>Current video:<br>
				<video width="320" controls>
					<source src="news/' . htmlspecialchars($row['video'], ENT_QUOTES) . '">
					Your browser does not support the video tag.
				</video></p>';
			}

			print '
			<div class="form-group">
				<label for="archive">Archive:</label><br />
				<input type="radio" name="archive" value="Y"'; if($row['archive'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> YES &nbsp;&nbsp;
				<input type="radio" name="archive" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NO
			</div>
			<hr>

			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	else {
		print '
		<h2>News</h2>
		<div id="news">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Title</th>
						<th>Description</th>
						<th>Date</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM news";
				$query .= " ORDER BY date DESC";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['id']. '"><img src="img/user.png" alt="user"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '"><img src="img/edit.png" alt="uredi"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['id']. '"><img src="img/delete.png" alt="obriši"></a></td>
						<td>' . $row['title'] . '</td>
						<td>';
						if(strlen($row['description']) > 160) {
                            echo substr(strip_tags(html_entity_decode($row['description'])), 0, 160).'...';
                        } else {
                            echo strip_tags(html_entity_decode($row['description']));
                        }
						print '
						</td>
						<td>' . pickerDateToMysql($row['date']) . '</td>
						<td>';
							if ($row['archive'] == 'Y') { print '<img src="img/inactive.png" alt="" title="" />'; }
                            else if ($row['archive'] == 'N') { print '<img src="img/active.png" alt="" title="" />'; }
						print '
						</td>
					</tr>';
				}
			print '
				</tbody>
			</table>
			<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;add=true" class="AddLink">Add news</a>
		</div>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>