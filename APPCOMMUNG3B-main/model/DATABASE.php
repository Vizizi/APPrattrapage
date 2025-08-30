<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'appg3';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Erreur de connexion : ' . $e->getMessage();
        }

        return $this->conn;
    }

    public function getLatestMeasurements() {
        $query = "SELECT c.nom, m.valeur, c.unite, m.date_heure 
                  FROM mesures m
                  JOIN capteurs c ON m.capteur_id = c.id
                  WHERE m.date_heure = (
                      SELECT MAX(date_heure) 
                      FROM mesures 
                      WHERE capteur_id = m.capteur_id
                  )
                  ORDER BY m.capteur_id";

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActuatorsStatus() {
        $query = "SELECT a.nom, ea.etat, ea.date_heure 
                  FROM etats_actionneurs ea
                  JOIN actionneurs a ON ea.actionneur_id = a.id
                  WHERE ea.date_heure = (
                      SELECT MAX(date_heure) 
                      FROM etats_actionneurs 
                      WHERE actionneur_id = ea.actionneur_id
                  )
                  ORDER BY ea.actionneur_id";

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*public function getSensorsLimits() {
        $query = "SELECT c.nom, l.lim_min, l.lim_max 
                  FROM limites l
                  JOIN capteurs c ON l.id_capteur = c.id";

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }*/

    public function getHistoricalData($limit = 50) {
        $query = "SELECT c.nom, m.valeur, c.unite, m.date_heure 
                  FROM mesures m
                  JOIN capteurs c ON m.capteur_id = c.id
                  ORDER BY m.date_heure DESC
                  LIMIT :limit";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActuatorHistory($limit = 50) {
    $conn = $this->connect();
    $query = "SELECT a.nom, ea.etat, ea.date_heure 
              FROM etats_actionneurs ea
              JOIN actionneurs a ON ea.actionneur_id = a.id
              ORDER BY ea.date_heure DESC
              LIMIT :limit";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getSensorHistory($limit = 50) {
    $conn = $this->connect();
    $query = "SELECT c.nom, m.valeur, c.unite, m.date_heure 
              FROM mesures m
              JOIN capteurs c ON m.capteur_id = c.id
              ORDER BY m.date_heure DESC
              LIMIT :limit";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}