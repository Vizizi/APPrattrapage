<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Classe</title>
    <link rel="stylesheet" href="../view/styles.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



    <style>
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
    
   
    <main class="main-content">
        <section class="hero">
            <h2>Bienvenue dans votre salle de classe connectée</h2>
            <p>E-Classe vous permet de connaître l'envrionnement de  votre salle de classe à distance.</p>
            <a href="connexion.php" class="cta-button">Connectez-vous</a>
        </section>

        <div class="features">
            <div class="feature-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Pour étudier dans de meilleures conditions</h3>
                <p>Elle permet de connaître les paramètres de la classe.</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-users"></i>
                <h3>Collaboration</h3>
                <p>Toute la classe peut voir et utiliser les objets connectés à disposition</p>
            </div>

            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Suivi personnalisé</h3>
                <p>Accédez à des statistiques grâce aux données collectées dans la salle.</p>
            </div>
        </div>
    </main>


<body>

 
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