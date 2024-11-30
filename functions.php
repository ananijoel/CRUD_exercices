<?php
function dbConnection($host, $username, $password) {
    try {
        // Connexion sans spécifier de base de données pour gérer tout le serveur
        $conn = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Erreur de connexion au serveur : " . htmlspecialchars($e->getMessage()));
    }
}

function createDatabaseAndTable($conn, $dbname, $tableName) {
    try {
        // Vérifie si la base de données existe
        $stmt = $conn->query("SHOW DATABASES LIKE '$dbname'");
        if ($stmt->rowCount() === 0) {
            // Crée la base de données si elle n'existe pas
            $conn->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
            //echo "Base de données '$dbname' créée avec succès.<br>";
        } else {
            //echo "La base de données '$dbname' existe déjà.<br>";
        }

        // Sélectionne la base de données
        $conn->exec("USE `$dbname`");

        // Vérifie si la table existe
        $stmt = $conn->query("SHOW TABLES LIKE '$tableName'");
        if ($stmt->rowCount() === 0) {
            // Crée la table si elle n'existe pas
            $conn->exec("
                CREATE TABLE `$tableName` (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    titre VARCHAR(255) NOT NULL,
                    auteur VARCHAR(255) NOT NULL,
                    date DATE NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
            //echo "Table '$tableName' créée avec succès.<br>";
        } else {
            //echo "La table '$tableName' existe déjà.<br>";
        }
    } catch (PDOException $e) {
        die("Erreur : " . htmlspecialchars($e->getMessage()));
    }
}

function create_exercice($conn){
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enregistrer'])) {
        // Récupération des données
        $titre = trim($_POST['titre']);
        $auteur = trim($_POST['auteur']);
        $date = $_POST['date'];
    
        
        if (empty($titre) || empty($auteur) || empty($date)) {
            echo "Tous les champs sont obligatoires.";
        } else {
            try {
                $stmt = $conn->prepare("INSERT INTO exercice (titre, auteur, date) VALUES (:titre, :auteur, :date)");
                $stmt->bindParam(':titre', $titre);
                $stmt->bindParam(':auteur', $auteur);
                $stmt->bindParam(':date', $date);
    
                if ($stmt->execute()) {
                    
                    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                    exit;
                } else {
                    echo "Erreur lors de l'enregistrement.";
                }
            } catch (PDOException $e) {
                echo "Erreur SQL : " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
function read_exercices($conn){
    try {
        $stmt = $conn->query("SELECT * FROM exercice"); 
        $exercices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $exercices;
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage()));
    }
}
function read_single_exercice($conn){
    $id = intval($_GET['id']);
    
    // Récupère les données existantes
    try {
        $stmt = $conn->prepare("SELECT * FROM exercice WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $exercice = $stmt->fetch(PDO::FETCH_ASSOC);
        return $exercice;
        if (!$exercice) {
            die("Exercice introuvable.");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage()));
    }
}
function update_exercice($conn){
    if (is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);
        // Met à jour les données si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
            $titre = trim($_POST['titre']);
            $auteur = trim($_POST['auteur']);
            $date = $_POST['date'];
    
            if (empty($titre) || empty($auteur) || empty($date)) {
                echo "Tous les champs sont obligatoires.";
            } else {
                try {
                    $stmt = $conn->prepare("UPDATE exercice SET titre = :titre, auteur = :auteur, date = :date WHERE id = :id");
                    $stmt->bindParam(':titre', $titre);
                    $stmt->bindParam(':auteur', $auteur);
                    $stmt->bindParam(':date', $date);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
                    if ($stmt->execute()) {
                        header("Location: index.php");
                        exit;
                    } else {
                        echo "Erreur lors de la mise à jour.";
                    }
                } catch (PDOException $e) {
                    die("Erreur SQL : " . htmlspecialchars($e->getMessage()));
                }
            }
        }
    } else {
        die('update failed ID invalide');
    }
}
function delete_exercice($conn){
    if (is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);
        try {
            $stmt = $conn->prepare("DELETE FROM exercice WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Redirige après la suppression
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            die("Erreur lors de la suppression : " . htmlspecialchars($e->getMessage()));
        }
    } else {
        //die("ID invalide.");
        die('deletion failed ID invalide');
    }
}
?>