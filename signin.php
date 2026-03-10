<?php
if (!isset($_POST['_action_'])) {
    $_POST['_action_'] = FALSE;
}

if ($_POST['_action_'] === 'TRUE') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {
        $stmt = mysqli_prepare($MySQL, "SELECT id, firstname, lastname, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['user']['valid'] = 'true';
            $_SESSION['user']['id'] = $row['id'];
            $_SESSION['user']['firstname'] = $row['firstname'];
            $_SESSION['user']['lastname'] = $row['lastname'];
            $_SESSION['message'] = '<p class="auth-alert auth-alert-success">Dobrodošli, ' . htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) . '.</p>';

            header("Location: index.php?menu=7");
            exit;
        } else {
            unset($_SESSION['user']);
            $_SESSION['message'] = '<p class="auth-alert auth-alert-error">Pogrešno korisničko ime ili lozinka.</p>';

            header("Location: index.php?menu=6");
            exit;
        }
    } else {
        $_SESSION['message'] = '<p class="auth-alert auth-alert-error">Molimo unesite korisničko ime i lozinku.</p>';
        header("Location: index.php?menu=6");
        exit;
    }
}

print '
<section class="auth-section">
    <div class="auth-container">
        <div id="signin" class="auth-card">
            <div class="auth-header">
                <span class="auth-label">Korisnički pristup</span>
                <h1>Sign In</h1>
                <p>Prijavite se za pristup administraciji i zaštićenim sadržajima.</p>
            </div>

            <form action="" name="myForm" id="myForm" method="POST" class="auth-form" autocomplete="on">
                <input type="hidden" id="_action_" name="_action_" value="TRUE">

                <div class="auth-form-group">
                    <label for="username">Username <span>*</span></label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value=""
                        pattern=".{5,10}"
                        required
                        autocomplete="username"
                        placeholder="Unesite korisničko ime"><br>
                    <small>Korisničko ime mora imati između 5 i 10 znakova.</small>
                </div>

                <div class="auth-form-group">
                    <label for="password">Password <span>*</span></label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        value=""
                        pattern=".{4,}"
                        required
                        autocomplete="current-password"
                        placeholder="Unesite lozinku"><br>
                    <small>Lozinka mora imati najmanje 4 znaka.</small>
                </div>

                <div class="auth-actions">
                    <input type="submit" value="Prijava" class="auth-btn">
                </div>
            </form>
        </div>
    </div>
</section>';
?>