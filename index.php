<?php
$host = '127.0.0.1';
$dbname = 'TP6';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Erreur de connexion : " . htmlspecialchars($e->getMessage()));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enregistrer'])) {
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des exercices</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un exercice</h1>
        <div class="add">
            <form action="" method="post">
                <fieldset>
                    <legend>Informations sur l'exercice</legend>
                    
                    <div>
                        <label for="titre">Titre de l'exercice</label>
                        <input type="text" id="titre" name="titre" required>
                    </div>

                    <div>
                        <label for="auteur">Auteur de l'exercice</label>
                        <input type="text" id="auteur" name="auteur" required>
                    </div>

                    <div>
                        <label for="date">Date de création</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    
                    <div>
                        <input type="submit" name="enregistrer" value="Enregistrer">
                    </div>
                </fieldset>
            </form>
            <?php if (isset($_GET['success'])): ?>
                <p style="color: green;">Enregistrement réussi !</p>
            <?php endif; ?>

            <div>
                <a href="list.php">voir les exercices</a>
            </div>
        </div>
    </div>
</body>
</html>
