<?php
// Connexion à la base de données
$host = '127.0.0.1';
$dbname = 'TP6';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . htmlspecialchars($e->getMessage()));
}

// Vérifie si un ID est passé dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
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
    die("ID invalide.");
}
?>
