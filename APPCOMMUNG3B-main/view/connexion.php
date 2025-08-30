<?php
session_start();
require_once '../model/config.php'; 

$email_err = $password_err = $confirm_password_err = "";
$login_err = "";
$registration_success = "";
$email = ""; 

if (isset($_GET['registered']) && $_GET['registered'] == 'true') {
    $registration_success = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";
} elseif (isset($_GET['error'])) {
    if ($_GET['error'] === 'email_exists') {
        $email_err = "Cette adresse e-mail est déjà utilisée.";
    } elseif ($_GET['error'] === 'insert_failed') {

        $email_err = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
    }
} elseif (isset($_GET['login_error']) && $_GET['login_error'] == '1') {
    $login_err = "Email ou mot de passe incorrect.";
} elseif (isset($_GET['email'])) {
    $email_err = urldecode($_GET['email']);
} elseif (isset($_GET['password'])) {
    $password_err = urldecode($_GET['password']);
} elseif (isset($_GET['confirm'])) {
    $confirm_password_err = urldecode($_GET['confirm']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'register') {

        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirmPassword"]);

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Veuillez entrer une adresse e-mail valide.";
        }

        if (empty($password) || strlen($password) < 6) {
            $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
        }

        if ($password !== $confirm_password) {
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }

        if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

            $sql = "SELECT id FROM users WHERE email = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $email_err = "Cette adresse e-mail est déjà utilisée.";

                }
                $stmt->close();
            }

            if (empty($email_err)) {

                $sql = "INSERT INTO users (email, password, created_at) VALUES (?, ?, NOW())";
                if ($stmt = $mysqli->prepare($sql)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt->bind_param("ss", $email, $hashed_password);
                    if ($stmt->execute()) {
                        header("Location: " . htmlspecialchars($_SERVER["PHP_SELF"]) . "?registered=true"); 
                        exit();
                    } else {

                        $email_err = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                    }
                    $stmt->close();
                }
            }
        }

    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {

        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        if (empty($email)) {
            $login_err = "Veuillez entrer votre adresse e-mail.";
        }

        if (empty($password)) {
            $login_err = "Veuillez entrer votre mot de passe.";
        }

        if (empty($login_err)) {
            $sql = "SELECT id, email, password FROM users WHERE email = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("s", $email);
                if ($stmt->execute()) {
                    $stmt->store_result();

                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($id, $email_db, $hashed_password);
                        if ($stmt->fetch()) {
                            if (password_verify($password, $hashed_password)) {
                                session_regenerate_id(true);
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email_db;
                                header("Location: ../view/Site.php"); 
                                exit();
                            } else {

                                $login_err = "Email ou mot de passe incorrect.";
                            }
                        }
                    } else {

                        $login_err = "Email ou mot de passe incorrect.";
                    }
                } else {

                    $login_err = "Une erreur est survenue lors de la connexion. Veuillez réessayer.";
                }
                $stmt->close();
            } else {

                $login_err = "Une erreur est survenue lors de la connexion. Veuillez réessayer.";
            }
        }
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion / Inscription - E-Classe</title>
    <link rel="stylesheet" href="../view/styles.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



    <style>
        /* Reset et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f9ff;
            color: #333;
        }
        
        /* Header */
        header {
            background: linear-gradient(0deg, #ffffff65 0%, #5d61b3ff 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-right {
            display: flex;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 40px;
            margin-right: 10px;
        }
        
        .logo h1 {
            color: #1a73e8;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        nav ul {
            display: flex;
            position : right;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 1.5rem;
        }
        
        nav ul li a {
            text-decoration: none;
            color: #151617ff;
            font-weight: 700;
            transition: color 0.3s;
            padding: 0.5rem 0;
        }
        
        nav ul li a:hover {
            color: #1a73e8;
            border-bottom: 0px solid #1a73e8;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info img {
            width: 32px;
            height: 32px;
            margin-right: 10px;
        }
        
        /* Footer */
        footer {
            background: linear-gradient(0deg, #ffffff65 0%, #5d61b3ff 100%) ;
            color: white;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-section {
            flex: 1;
            padding: 0 1rem;
        }
        
        .footer-section h3 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-section ul li a {
            color: #e8f0fe;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-section ul li a:hover {
            color: white;
            text-decoration: underline;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            border-top: 1px solid #4285f4;
        }
    </style>
</head>

    <!-- Header -->
    <header>
        <div class="logo">
            
            <h1>E-Classe</h1>
        </div>
        <div class ="header-right">
            <nav>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                    
                </ul>
            </nav>
        </div>
        
    </header>
    
    <main>
        
    </main>
    
   
    


<body>

    <div id="auth-section">
        <div class="auth-container">
            <h2 class="text-center mb-20">
                <i class=""></i> Accès E-Classe
            </h2>

            <?php if (!empty($registration_success)): ?>
                <p class="success-message"><?php echo $registration_success; ?></p>
            <?php endif; ?>

            <div id="login-form">
                <h3 class="mb-20">Connexion</h3>
                <?php if (!empty($login_err)): ?>
                    <p class="error-message"><?php echo $login_err; ?></p>
                <?php endif; ?>
                <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="loginEmail">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="loginEmail" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">
                            <i class="fas fa-lock"></i> Mot de passe
                        </label>
                        <input type="password" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="showRegister()">
                        <i class="fas fa-user-plus"></i> S'inscrire
                    </button>
                </form>
            </div>

            <div id="register-form" class="hidden">
                <h3 class="mb-20">Inscription</h3>
                <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="action" value="register">
                    <div class="form-group">
                        <label for="registerEmail">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="registerEmail" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        <p class="error-message"><?php echo $email_err; ?></p>
                    </div>
                    <div class="form-group">
                        <label for="registerPassword">
                            <i class="fas fa-lock"></i> Mot de passe
                        </label>
                        <input type="password" id="registerPassword" name="password" required>
                        <p class="error-message"><?php echo $password_err; ?></p>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">
                            <i class="fas fa-lock"></i> Confirmer le mot de passe
                        </label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <p class="error-message"><?php echo $confirm_password_err; ?></p>
                    </div>
                    <button type="submit" class="btn">
                        <i class="fas fa-user-plus"></i> S'inscrire
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="showLogin()">
                        <i class="fas fa-sign-in-alt"></i> Déjà inscrit ?
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRegister() {
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
        }

        function showLogin() {
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('login-form').classList.remove('hidden');
        }

        document.getElementById('registerForm').addEventListener('submit', function (e) {
            const pwd = document.getElementById('registerPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            if (pwd !== confirm) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const isRegistering = <?php echo json_encode(!empty($email_err) || !empty($password_err) || !empty($confirm_password_err) || (!empty($_POST['action']) && $_POST['action'] === 'register')); ?>;
            const isRegisteredSuccess = urlParams.get('registered') === 'true';

            if (isRegistering && !isRegisteredSuccess) { 
                showRegister();
            } else if (isRegisteredSuccess) { 
                showLogin();
            } else if (urlParams.get('login_error') === '1') { 
                showLogin();
            } else {

                showLogin();
            }
        });

    </script>

</body>
<footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Navigation</h3>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="connexion.php">Connexion</a></li>

                </ul>
            </div>
            
        
            
            <div class="footer-section">
                <h3>Légal</h3>
                <ul>
                    <li><a href="#">Mentions légales</a></li>
                    <li><a href="#">Confidentialité</a></li>
                    <li><a href="#">Conditions d'utilisation</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li>Email: isep@isep.fr</li>
                    <li>Tél: 01 23 45 67 89</li>
                </ul>
            </div>
        </div>
        
        
    </footer>
</html>