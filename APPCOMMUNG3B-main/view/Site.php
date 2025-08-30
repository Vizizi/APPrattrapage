<?php
require_once '../model/DATABASE.php';

$db = new Database();

$measurements = $db->getLatestMeasurements();
$actuators = $db->getActuatorsStatus();
/*$limits = $db->getSensorsLimits();*/
$historicalData = $db->getHistoricalData(20);
$actuatorHistory = $db->getActuatorHistory(50);
$sensorHistory = $db->getSensorHistory(50);

$sensorValues = [];
foreach ($measurements as $measure) {
    $sensorValues[$measure['nom']] = $measure;
}

/*$sensorLimits = [];
foreach ($limits as $limit) {
    $sensorLimits[$limit['nom']] = $limit;
}*/

$actuatorStatus = [];
foreach ($actuators as $actuator) {
    $actuatorStatus[$actuator['nom']] = $actuator;
}

$chartData = $db->getHistoricalData(50);
$chartValues = [
    'temperature' => [],
    'humidite' => [],
    'luminosite' => [],
    'humidite_sol' => [],
    'co2' => []
];

foreach ($chartData as $data) {
    if (isset($chartValues[$data['nom']])) {
        $chartValues[$data['nom']][] = [
            'x' => $data['date_heure'],
            'y' => $data['valeur']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌱 SerreConnect - Gestion Intelligente de Serre</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #494ba8;
            --secondary: #454e94;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3498db;
            --light: #ecf0f1;
            --dark: #34495e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        nav {
            width: 250px;
            background-color: var(--secondary);
            color: white;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            padding: 0 20px 20px;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            flex-direction: column;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .nav-links a:hover, .nav-links a.active {
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid var(--primary);
        }

        .nav-links a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        main {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .hidden {
            display: none !important;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: var(--secondary);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--primary);
        }

        .sensors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .sensor-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px;
            transition: transform 0.3s;
        }

        .sensor-card:hover {
            transform: translateY(-5px);
        }

        .sensor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .sensor-info {
            display: flex;
            align-items: center;
        }

        .sensor-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(39, 174, 96, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: var(--primary);
        }

        .sensor-title {
            font-weight: 600;
        }

        .sensor-status {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .status-optimal {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--primary);
        }

        .status-warning {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning);
        }

        .status-critical {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger);
        }

        .sensor-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sensor-details small {
            color: #7f8c8d;
            font-size: 0.8rem;
        }

        .actuator-controls {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .actuator-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .actuator-info {
            display: flex;
            align-items: center;
        }

        .actuator-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: var(--info);
        }

        .actuator-title {
            font-weight: 600;
        }

        .actuator-info small {
            color: #7f8c8d;
            font-size: 0.8rem;
        }

        .toggle-switch {
            width: 50px;
            height: 26px;
            background-color: #ddd;
            border-radius: 13px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: white;
            top: 3px;
            left: 3px;
            transition: transform 0.3s;
        }

        .toggle-switch.active {
            background-color: var(--primary);
        }

        .toggle-switch.active::after {
            transform: translateX(24px);
        }

        .chart-container {
            height: 400px;
            width: 100%;
            margin-top: 20px;
        }

        .data-table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .data-table th {
            background-color: var(--secondary);
            color: white;
            font-weight: 500;
        }

        .data-table tr:hover {
            background-color: #f5f5f5;
        }

        .alert {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }

        .alert i {
            font-size: 1.5rem;
            margin-right: 15px;
        }

        .alert-warning {
            background-color: rgba(243, 156, 18, 0.1);
            border-left: 4px solid var(--warning);
        }

        .alert-warning i {
            color: var(--warning);
        }

        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            border-left: 4px solid var(--danger);
        }

        .alert-danger i {
            color: var(--danger);
        }

        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            border-left: 4px solid var(--primary);
        }

        .alert-success i {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            nav {
                width: 100%;
                padding: 10px 0;
            }

            .nav-links {
                flex-direction: row;
                overflow-x: auto;
            }

            .nav-links a {
                padding: 10px 15px;
                white-space: nowrap;
                border-left: none;
                border-bottom: 3px solid transparent;
            }

            .nav-links a:hover, .nav-links a.active {
                border-left: none;
                border-bottom: 3px solid var(--primary);
            }

            .sensors-grid, .actuator-controls {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <div class="logo">
                E-classe
            </div>
            <div class="nav-links">
                <a href="#" onclick="showSection('dashboard')" class="active">
                    <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                </a>
                <a href="#" onclick="showSection('sensors')">
                    <i class="fas fa-thermometer-half"></i> Capteurs
                </a>
                <a href="#" onclick="showSection('actuators')">
                    <i class="fas fa-cogs"></i> Actionneurs
                </a>
                <a href="#" onclick="showSection('data')">
                    <i class="fas fa-chart-line"></i> Données
                </a>
                <a href="#" onclick="showSection('alerts')">
                    <i class="fas fa-bell"></i> Alertes
                </a>
                <a href="index.php" >
                    <i></i> Déconnexion
                </a>
            </div>
        </nav>

        <main>
            <!-- Dashboard Section -->
            <section id="dashboard-section">
                <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-thermometer-half"></i>
                        État des Capteurs
                    </h2>
                    <div class="sensors-grid">
                        <div class="sensor-card" id="temperature-card">
                            <div class="sensor-header">
                                <div class="sensor-info">
                                    <div class="sensor-icon">
                                        <i class="fas fa-thermometer-half"></i>
                                    </div>
                                    <div class="sensor-title">Température</div>
                                </div>
                                <div class="sensor-status" id="temp-status">
                                    <i class="fas fa-check-circle"></i> Optimal
                                </div>
                            </div>
                            <div class="sensor-value" id="temp-value">
                                <?php echo $sensorValues['temperature']['valeur'] ?? 'N/A'; ?>°C
                            </div>
                            <div class="sensor-details">
                                <small>
                                    Plage optimale: 
                                    <?php echo $sensorLimits['temperature']['lim_min'] ?? 'N/A'; ?>-
                                    <?php echo $sensorLimits['temperature']['lim_max'] ?? 'N/A'; ?>°C
                                </small>
                            </div>
                        </div>

                        <div class="sensor-card" id="humidity-card">
                            <div class="sensor-header">
                                <div class="sensor-info">
                                    <div class="sensor-icon">
                                        <i class="fas fa-tint"></i>
                                    </div>
                                    <div class="sensor-title">Humidité</div>
                                </div>
                                <div class="sensor-status" id="humidity-status">
                                    <i class="fas fa-check-circle"></i> Optimal
                                </div>
                            </div>
                            <div class="sensor-value" id="humidity-value">
                                <?php echo $sensorValues['humidite']['valeur'] ?? 'N/A'; ?>%
                            </div>
                            <div class="sensor-details">
                                <small>
                                    Plage optimale: 
                                    <?php echo $sensorLimits['humidite']['lim_min'] ?? 'N/A'; ?>-
                                    <?php echo $sensorLimits['humidite']['lim_max'] ?? 'N/A'; ?>%
                                </small>
                            </div>
                        </div>

                        <div class="sensor-card" id="light-card">
                            <div class="sensor-header">
                                <div class="sensor-info">
                                    <div class="sensor-icon">
                                        <i class="fas fa-sun"></i>
                                    </div>
                                    <div class="sensor-title">Luminosité</div>
                                </div>
                                <div class="sensor-status" id="light-status">
                                    <i class="fas fa-check-circle"></i> Optimal
                                </div>
                            </div>
                            <div class="sensor-value" id="light-value">
                                <?php echo $sensorValues['luminosite']['valeur'] ?? 'N/A'; ?>%
                            </div>
                            <div class="sensor-details">
                                <small>
                                    Plage optimale: 
                                    <?php echo $sensorLimits['luminosite']['lim_min'] ?? 'N/A'; ?>-
                                    <?php echo $sensorLimits['luminosite']['lim_max'] ?? 'N/A'; ?>%
                                </small>
                            </div>
                        </div>

                        <div class="sensor-card" id="soil-card">
                            <div class="sensor-header">
                                <div class="sensor-info">
                                    <div class="sensor-icon">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                    <div class="sensor-title">Humidité Sol</div>
                                </div>
                                <div class="sensor-status" id="soil-status">
                                    <i class="fas fa-check-circle"></i> Optimal
                                </div>
                            </div>
                            <div class="sensor-value" id="soil-value">
                                <?php echo $sensorValues['humidite_sol']['valeur'] ?? 'N/A'; ?>%
                            </div>
                            <div class="sensor-details">
                                <small>
                                    Plage optimale: 
                                    <?php echo $sensorLimits['humidite_sol']['lim_min'] ?? 'N/A'; ?>-
                                    <?php echo $sensorLimits['humidite_sol']['lim_max'] ?? 'N/A'; ?>%
                                </small>
                            </div>
                        </div>
                        <div class="sensor-card" id="co2-card">
                            <div class="sensor-header">
                                <div class="sensor-info">
                                    <div class="sensor-icon" style="color: #9b59b6;">
                                        <i class="fas fa-wind"></i>
                                    </div>
                                    <div class="sensor-title">CO₂</div>
                                </div>
                                <div class="sensor-status" id="co2-status">
                                    <i class="fas fa-check-circle"></i> Optimal
                                </div>
                            </div>
                            <div class="sensor-value" id="co2-value">
                                <?= htmlspecialchars($sensorValues['co2']['valeur'] ?? 'N/A') ?> ppm
                            </div>
                            <div class="sensor-details">
                                <small>
                                    Plage optimale: 
                                    <?= htmlspecialchars($sensorLimits['co2']['lim_min'] ?? 'N/A') ?>-
                                    <?= htmlspecialchars($sensorLimits['co2']['lim_max'] ?? 'N/A') ?> ppm
                                </small>
                            </div>
                        </div>
                            </div>
                        </div>

                <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-cogs"></i>
                        Contrôles Automatiques
                    </h2>
                    <div class="actuator-controls">
                        <div class="actuator-item">
                            <div class="actuator-info">
                                <div class="actuator-icon">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div>
                                    <div class="actuator-title">LED</div>
                                    <small>Éclairage LED</small>
                                </div>
                            </div>
                            <div class="toggle-switch <?php echo $actuatorStatus['led']['etat'] ? 'active' : ''; ?>" 
                                 onclick="toggleActuator(this, 'led')"></div>
                        </div>

                        <div class="actuator-item">
                            <div class="actuator-info">
                                <div class="actuator-icon">
                                    <i class="fas fa-fan"></i>
                                </div>
                                <div>
                                    <div class="actuator-title">Moteur</div>
                                    <small>Ventilation</small>
                                </div>
                            </div>
                            <div class="toggle-switch <?php echo $actuatorStatus['moteur']['etat'] ? 'active' : ''; ?>" 
                                 onclick="toggleActuator(this, 'moteur')"></div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Évolution des Paramètres (24h)
                    </h2>
                    <div class="chart-container">
                        <canvas id="sensorsChart"></canvas>
                    </div>
                </div>
            </section>
            <section id="sensors-section" class="hidden">
                <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-history"></i>
                        Historique des Capteurs
                    </h2>
                    <div class="history-container">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th class="sortable-header" onclick="sortTable(this, 0)">Date/Heure <i class="fas fa-sort"></i></th>
                                    <th class="sortable-header" onclick="sortTable(this, 1)">Capteur <i class="fas fa-sort"></i></th>
                                    <th class="sortable-header" onclick="sortTable(this, 2)">Valeur <i class="fas fa-sort"></i></th>
                                    <th>Unité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sensorHistory as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($record['date_heure']))) ?></td>
                                    <td>
                                        <i class="fas fa-<?= 
                                            match($record['nom']) {
                                                'temperature' => 'thermometer-half',
                                                'humidite' => 'tint',
                                                'luminosite' => 'sun',
                                                'humidite_sol' => 'leaf',
                                                default => 'question-circle'
                                            }
                                        ?>"></i>
                                        <?= htmlspecialchars(ucfirst($record['nom'])) ?>
                                    </td>
                                    <td><?= htmlspecialchars($record['valeur']) ?></td>
                                    <td><?= htmlspecialchars($record['unite']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        <section id="actuators-section" class="hidden">
            <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-history"></i>
                        Historique des Actionneurs
                    </h2>
                    <div class="history-container">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th class="sortable-header" onclick="sortTable(this, 0)">Date/Heure <i class="fas fa-sort"></i></th>
                                    <th class="sortable-header" onclick="sortTable(this, 1)">Actionneur <i class="fas fa-sort"></i></th>
                                    <th class="sortable-header" onclick="sortTable(this, 2)">État <i class="fas fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($actuatorHistory as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($record['date_heure']))) ?></td>
                                    <td>
                                        <i class="fas fa-<?= 
                                            match($record['nom']) {
                                                'led' => 'lightbulb',
                                                'moteur' => 'fan',
                                                default => 'cog'
                                            }
                                        ?>"></i>
                                        <?= htmlspecialchars(ucfirst($record['nom'])) ?>
                                    </td>
                                    <td>
                                        <span class="actuator-status <?= $record['etat'] ? 'status-active' : 'status-inactive' ?>">
                                            <?= $record['etat'] ? 'Activé' : 'Désactivé' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <!-- Data Section -->
            <section id="data-section" class="hidden">
                <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-database"></i>
                        Historique des Données
                    </h2>
                    <div class="data-table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date/Heure</th>
                                    <th>Capteur</th>
                                    <th>Valeur</th>
                                    <th>Unité</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody id="dataTableBody">
                                <?php foreach ($historicalData as $data): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($data['date_heure'])); ?></td>
                                    <td>
                                        <i class="fas fa-<?php 
                                            switch($data['nom']) {
                                                case 'temperature': echo 'thermometer-half'; break;
                                                case 'humidite': echo 'tint'; break;
                                                case 'luminosite': echo 'sun'; break;
                                                case 'humidite_sol': echo 'leaf'; break;
                                                default: echo 'question-circle';
                                            }
                                        ?>"></i> 
                                        <?php echo ucfirst($data['nom']); ?>
                                    </td>
                                    <td><?php echo $data['valeur']; ?></td>
                                    <td><?php echo $data['unite']; ?></td>
                                    <td>
                                        <span class="sensor-status">
                                            <?php 
                                            $value = $data['valeur'];
                                            $min = $sensorLimits[$data['nom']]['lim_min'] ?? null;
                                            $max = $sensorLimits[$data['nom']]['lim_max'] ?? null;

                                            if ($min !== null && $max !== null) {
                                                if ($value >= $min && $value <= $max) {
                                                    echo '<i class="fas fa-check-circle"></i> Optimal';
                                                } elseif ($value < $min * 0.8 || $value > $max * 1.3) {
                                                    echo '<i class="fas fa-times-circle"></i> Critique';
                                                } else {
                                                    echo '<i class="fas fa-exclamation-triangle"></i> Attention';
                                                }
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Alerts Section -->
            <section id="alerts-section" class="hidden">
                <div class="card">
                    <h2 class="section-title">
                        <i class="fas fa-bell"></i>
                        Système d'Alertes
                    </h2>
                    <div id="alertsContainer">
                        <?php

                        foreach ($sensorValues as $sensor => $data) {
                            $value = $data['valeur'];
                            $min = $sensorLimits[$sensor]['lim_min'] ?? null;
                            $max = $sensorLimits[$sensor]['lim_max'] ?? null;

                            if ($min !== null && $max !== null) {
                                if ($value < $min) {
                                    echo '<div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <div>
                                                <strong>'.ucfirst($sensor).' trop bas</strong><br>
                                                La valeur ('.$value.$data['unite'].') est en dessous du minimum ('.$min.$data['unite'].').
                                            </div>
                                          </div>';
                                } elseif ($value > $max) {
                                    echo '<div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <div>
                                                <strong>'.ucfirst($sensor).' trop haut</strong><br>
                                                La valeur ('.$value.$data['unite'].') est au dessus du maximum ('.$max.$data['unite'].').
                                            </div>
                                          </div>';
                                }
                            }
                        }

                        foreach ($actuatorStatus as $actuator => $data) {
                            if ($data['etat'] == 1) {
                                echo '<div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i>
                                        <div>
                                            <strong>'.ucfirst($actuator).' activé</strong><br>
                                            L\'actionneur est actuellement en marche depuis '.date('d/m/Y H:i', strtotime($data['date_heure'])).'.
                                        </div>
                                      </div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>

        function showSection(sectionName) {

            document.querySelectorAll('main > section').forEach(section => {
                section.classList.add('hidden');
            });

            document.getElementById(sectionName + '-section').classList.remove('hidden');

            document.querySelectorAll('.nav-links a').forEach(link => {
                link.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        function toggleActuator(element, actuatorType) {
            element.classList.toggle('active');
            const isActive = element.classList.contains('active');

            updateActuator(actuatorType, isActive ? 1 : 0);
        }

        function updateActuator(actuatorType, status) {
            fetch('../model/update_actuator.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    actuator: actuatorType,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(`${actuatorType} ${status ? 'activé' : 'désactivé'} avec succès`, 'success');
                } else {
                    showAlert(`Erreur lors de la mise à jour de ${actuatorType}`, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Erreur de connexion au serveur', 'danger');
            });
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'times-circle'}"></i>
                <div>${message}</div>
            `;

            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        function initChart() {
            const ctx = document.getElementById('sensorsChart');
            if (!ctx) return;

            window.sensorsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [
                        {
                            label: 'Température (°C)',
                            data: [
                                <?php foreach ($chartValues['temperature'] as $point): ?>
                                { x: new Date('<?php echo $point['x']; ?>'), y: <?php echo $point['y']; ?> },
                                <?php endforeach; ?>
                            ],
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Humidité (%)',
                            data: [
                                <?php foreach ($chartValues['humidite'] as $point): ?>
                                { x: new Date('<?php echo $point['x']; ?>'), y: <?php echo $point['y']; ?> },
                                <?php endforeach; ?>
                            ],
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Luminosité (%)',
                            data: [
                                <?php foreach ($chartValues['luminosite'] as $point): ?>
                                { x: new Date('<?php echo $point['x']; ?>'), y: <?php echo $point['y']; ?> },
                                <?php endforeach; ?>
                            ],
                            borderColor: '#f39c12',
                            backgroundColor: 'rgba(243, 156, 18, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Humidité Sol (%)',
                            data: [
                                <?php foreach ($chartValues['humidite_sol'] as $point): ?>
                                { x: new Date('<?php echo $point['x']; ?>'), y: <?php echo $point['y']; ?> },
                                <?php endforeach; ?>
                            ],
                            borderColor: '#27ae60',
                            backgroundColor: 'rgba(39, 174, 96, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'hour',
                                displayFormats: {
                                    hour: 'HH:mm'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Heure'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Valeur'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + 
                                           (context.dataset.label.includes('Temp') ? '°C' : '%');
                                }
                            }
                        }
                    }
                }
            });
        }

        function refreshData() {
            fetch('get_latest_data.php')
                .then(response => response.json())
                .then(data => {

                    if (data.temperature) {
                        document.getElementById('temp-value').textContent = data.temperature.value + '°C';
                        updateSensorStatus('temp', data.temperature.value, 
                            <?php echo $sensorLimits['temperature']['lim_min'] ?? 0; ?>, 
                            <?php echo $sensorLimits['temperature']['lim_max'] ?? 100; ?>);
                    }
                    if (data.humidite) {
                        document.getElementById('humidity-value').textContent = data.humidite.value + '%';
                        updateSensorStatus('humidity', data.humidite.value, 
                            <?php echo $sensorLimits['humidite']['lim_min'] ?? 0; ?>, 
                            <?php echo $sensorLimits['humidite']['lim_max'] ?? 100; ?>);
                    }
                    if (data.luminosite) {
                        document.getElementById('light-value').textContent = data.luminosite.value + '%';
                        updateSensorStatus('light', data.luminosite.value, 
                            <?php echo $sensorLimits['luminosite']['lim_min'] ?? 0; ?>, 
                            <?php echo $sensorLimits['luminosite']['lim_max'] ?? 100; ?>);
                    }
                    if (data.humidite_sol) {
                        document.getElementById('soil-value').textContent = data.humidite_sol.value + '%';
                        updateSensorStatus('soil', data.humidite_sol.value, 
                            <?php echo $sensorLimits['humidite_sol']['lim_min'] ?? 0; ?>, 
                            <?php echo $sensorLimits['humidite_sol']['lim_max'] ?? 100; ?>);
                    }

                    if (data.led !== undefined) {
                        const ledSwitch = document.querySelector('.toggle-switch[onclick*="led"]');
                        if (ledSwitch) {
                            data.led ? ledSwitch.classList.add('active') : ledSwitch.classList.remove('active');
                        }
                    }
                    if (data.moteur !== undefined) {
                        const motorSwitch = document.querySelector('.toggle-switch[onclick*="moteur"]');
                        if (motorSwitch) {
                            data.moteur ? motorSwitch.classList.add('active') : motorSwitch.classList.remove('active');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function updateSensorStatus(sensor, value, minOptimal, maxOptimal) {
            const statusElement = document.getElementById(sensor + '-status');

            let status, statusClass, icon;

            if (value >= minOptimal && value <= maxOptimal) {
                status = 'Optimal';
                statusClass = 'status-optimal';
                icon = 'check-circle';
            } else if (value < minOptimal * 0.8 || value > maxOptimal * 1.3) {
                status = 'Critique';
                statusClass = 'status-critical';
                icon = 'times-circle';
            } else {
                status = 'Attention';
                statusClass = 'status-warning';
                icon = 'exclamation-triangle';
            }

            statusElement.className = `sensor-status ${statusClass}`;
            statusElement.innerHTML = `<i class="fas fa-${icon}"></i> ${status}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            initChart();

            updateSensorStatus('temp', <?php echo $sensorValues['temperature']['valeur'] ?? 0; ?>, 
                <?php echo $sensorLimits['temperature']['lim_min'] ?? 0; ?>, 
                <?php echo $sensorLimits['temperature']['lim_max'] ?? 100; ?>);

            updateSensorStatus('humidity', <?php echo $sensorValues['humidite']['valeur'] ?? 0; ?>, 
                <?php echo $sensorLimits['humidite']['lim_min'] ?? 0; ?>, 
                <?php echo $sensorLimits['humidite']['lim_max'] ?? 100; ?>);

            updateSensorStatus('light', <?php echo $sensorValues['luminosite']['valeur'] ?? 0; ?>, 
                <?php echo $sensorLimits['luminosite']['lim_min'] ?? 0; ?>, 
                <?php echo $sensorLimits['luminosite']['lim_max'] ?? 100; ?>);

            updateSensorStatus('soil', <?php echo $sensorValues['humidite_sol']['valeur'] ?? 0; ?>, 
                <?php echo $sensorLimits['humidite_sol']['lim_min'] ?? 0; ?>, 
                <?php echo $sensorLimits['humidite_sol']['lim_max'] ?? 100; ?>);

            setInterval(refreshData, 10000);
        });
    </script>
</body>
</html>