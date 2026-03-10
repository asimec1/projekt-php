<?php
if (!isset($_POST['_action_'])) {
    $_POST['_action_'] = FALSE;
}

$message = '';
$messageClass = '';

$firstname = trim($_POST['firstname'] ?? '');
$lastname  = trim($_POST['lastname'] ?? '');
$email     = trim($_POST['email'] ?? '');
$username  = trim($_POST['username'] ?? '');
$country   = trim($_POST['country'] ?? '');

if ($_POST['_action_'] === 'TRUE') {
    $password = $_POST['password'] ?? '';

    if ($firstname === '' || $lastname === '' || $email === '' || $username === '' || $password === '') {
        $message = 'Molimo ispunite sva obavezna polja.';
        $messageClass = 'auth-alert auth-alert-error';
    } else {
        $stmt = mysqli_prepare($MySQL, "SELECT id, email, username FROM users WHERE email = ? OR username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "ss", $email, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $message = 'Korisnik s tim emailom ili korisničkim imenom već postoji.';
            $messageClass = 'auth-alert auth-alert-error';
        } else {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

            $stmt = mysqli_prepare(
                $MySQL,
                "INSERT INTO users (firstname, lastname, email, username, password, country) VALUES (?, ?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "ssssss", $firstname, $lastname, $email, $username, $pass_hash, $country);
            $insert = mysqli_stmt_execute($stmt);

            if ($insert) {
                $message = ucfirst(strtolower($firstname)) . ' ' . ucfirst(strtolower($lastname)) . ', hvala na registraciji.';
                $messageClass = 'auth-alert auth-alert-success';

                $firstname = '';
                $lastname = '';
                $email = '';
                $username = '';
                $country = '';
            } else {
                $message = 'Došlo je do greške prilikom registracije. Pokušajte ponovno.';
                $messageClass = 'auth-alert auth-alert-error';
            }
        }
    }
}

print '
<section class="auth-section">
    <div class="auth-container">
        <div id="register" class="auth-card auth-card-wide">
            <div class="auth-header">
                <span class="auth-label">Korisnička registracija</span>
                <h1>Registration Form</h1>
                <p>Ispunite podatke za izradu korisničkog računa i pristup sustavu.</p>
            </div>';

            if ($message !== '') {
                print '<p class="' . $messageClass . '">' . htmlspecialchars($message) . '</p>';
            }

        print '
            <form action="" id="registration_form" name="registration_form" method="POST" class="auth-form" autocomplete="on">
                <input type="hidden" id="_action_" name="_action_" value="TRUE">

                <div class="auth-grid">
                    <div class="auth-form-group">
                        <label for="fname">First Name <span>*</span></label>
                        <input
                            type="text"
                            id="fname"
                            name="firstname"
                            value="' . htmlspecialchars($firstname) . '"
                            placeholder="Your name..."
                            required>
                    </div>

                    <div class="auth-form-group">
                        <label for="lname">Last Name <span>*</span></label>
                        <input
                            type="text"
                            id="lname"
                            name="lastname"
                            value="' . htmlspecialchars($lastname) . '"
                            placeholder="Your last name..."
                            required>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="email">Your E-mail <span>*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="' . htmlspecialchars($email) . '"
                        placeholder="Your e-mail..."
                        required>
                </div>

                <div class="auth-grid">
                    <div class="auth-form-group">
                        <label for="username">Username <span>*</span></label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="' . htmlspecialchars($username) . '"
                            pattern=".{5,10}"
                            placeholder="Username..."
                            required>
                        <small>Username mora imati između 5 i 10 znakova.</small>
                    </div>

                    <div class="auth-form-group">
                        <label for="password">Password <span>*</span></label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            pattern=".{4,}"
                            placeholder="Password..."
                            required>
                        <small>Password mora imati najmanje 4 znaka.</small>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label for="country">Country</label>
                    <select name="country" id="country">
                        <option value="">Molimo odaberite</option>';

                        $query = "SELECT * FROM countries ORDER BY country_name ASC";
                        $result = mysqli_query($MySQL, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($country === $row['country_code']) ? ' selected' : '';
                            print '<option value="' . htmlspecialchars($row['country_code']) . '"' . $selected . '>' . htmlspecialchars($row['country_name']) . '</option>';
                        }

                print '
                    </select>
                </div>

                <div class="auth-actions">
                    <input type="submit" value="Registracija" class="auth-btn">
                </div>
            </form>
        </div>
    </div>
</section>';
?>