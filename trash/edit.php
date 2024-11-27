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

// Vérifie si un ID est passé
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Récupère les données existantes
    try {
        $stmt = $conn->prepare("SELECT * FROM exercice WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $exercice = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$exercice) {
            die("Exercice introuvable.");
        }
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage()));
    }

    // Met à jour les données si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    die("ID invalide.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un exercice</title>
</head>
<body>
    <h1>Modifier l'exercice</h1>
    <form action="" method="post">
        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($exercice['titre']) ?>" required>
        <br>
        <label for="auteur">Auteur</label>
        <input type="text" id="auteur" name="auteur" value="<?= htmlspecialchars($exercice['auteur']) ?>" required>
        <br>
        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($exercice['date']) ?>" required>
        <br>
        <input type="submit" value="Mettre à jour">
    </form>
</body>
</html>
