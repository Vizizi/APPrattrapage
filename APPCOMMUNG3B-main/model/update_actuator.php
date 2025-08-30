<?php
require_once 'DATABASE.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'Données JSON invalides']);
    exit;
}

if (!isset($data['actuator']) || !is_string($data['actuator']) || 
    !isset($data['status']) || !is_numeric($data['status'])) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants ou invalides']);
    exit;
}

$actuator = trim($data['actuator']);
$status = (int)$data['status'];
$status = ($status === 1) ? 1 : 0; 

try {
    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT id FROM actionneurs WHERE nom = :nom LIMIT 1");
    $stmt->bindParam(':nom', $actuator, PDO::PARAM_STR);
    $stmt->execute();

    $actionneur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$actionneur) {
        http_response_code(404); 
        echo json_encode(['success' => false, 'message' => 'Actionneur non trouvé']);
        exit;
    }

    $conn->beginTransaction();

    try {
        $stmt = $conn->prepare(
            "INSERT INTO etats_actionneurs (actionneur_id, etat) 
             VALUES (:id, :etat)"
        );

        $stmt->bindParam(':id', $actionneur['id'], PDO::PARAM_INT);
        $stmt->bindParam(':etat', $status, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'insertion");
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'actuator' => $actuator,
            'status' => $status,
            'timestamp' => date('Y-m-d H:i:s')
        ]);

    } catch (Exception $e) {
        $conn->rollBack();
        http_response_code(500); 
        echo json_encode(['success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
    }

} catch (PDOException $e) {
    http_response_code(500); 
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']);
}
?>