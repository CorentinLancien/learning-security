<?php
require_once('functions.php');
$siteKey = '6Le4ya4kAAAAAKVxK5D8W3l104TmeV6SFHspvOGI'; 
$secret = '6Le4ya4kAAAAAOoOjy-eee_V-GXYBkjmYmW5V2Kl'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = $secret;
    $recaptcha_response = $_POST['recaptcha_response'];
    
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    if ($recaptcha->score >= 0.5) {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            $result = saveUser(htmlentities($_POST['username']), htmlentities($_POST['email']), htmlentities($_POST['password']));
            if($result === true) {
                header('Location: index.php');
            } else {
                echo "Une erreur est survenue " . $result;
            }
        }    
    }
} 
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ma super app sécurisée - Inscription</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <script src="https://www.google.com/recaptcha/api.js?render=6Le4ya4kAAAAAKVxK5D8W3l104TmeV6SFHspvOGI">
    </script>
    <script>
    grecaptcha.ready(function () {
    grecaptcha.execute('6Le4ya4kAAAAAKVxK5D8W3l104TmeV6SFHspvOGI', { action: 'label' }).then(function (token) {
    var recaptchaResponse = document.getElementById('recaptchaResponse');
    recaptchaResponse.value = token;
                });
            });
    </script>
</head>
<body>
<div class="container">
    <h1>Inscription</h1>
    <form action="/register.php" method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback">
                S'il vous plaît entrez un nom d'utilisateur.
            </div>
        </div>
        <div class="form-group">
            <label for="email">Adresse email :</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">
                S'il vous plaît entrez une adresse email valide.
            </div>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">
                S'il vous plaît entrez un mot de passe.
            </div>
        </div>
        <div class="form-group">
            <label for="password-confirm">Confirmez le mot de passe :</label>
            <input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
            <div class="invalid-feedback">
                S'il vous plaît confirmez votre mot de passe.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
    </form>
    <script>
        var password = document.getElementById("password");
        var confirm_password = document.getElementById("password-confirm");

        function validatePassword(){
            console.log('here');
            if(password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Les mots de passe ne correspondent pas");
                return false;
            } else {
                confirm_password.setCustomValidity('');
                return true;
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;

        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</div>
</body>
</html>