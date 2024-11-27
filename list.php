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

// Récupération des données
try {
    $stmt = $conn->query("SELECT * FROM exercice"); 
    $exercices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau HTML</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Liste des exercices</h1>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>titre</th>
                <th>auteur</th>
                <th>date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($exercices)): ?>
                <tr>
                    <td colspan="4">Aucune donnée trouvée.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($exercices as $exercice): ?>
                    <tr>
                        <td><?= htmlspecialchars($exercice['id']) ?></td>
                        <td><?= htmlspecialchars($exercice['titre']) ?></td>
                        <td><?= htmlspecialchars($exercice['auteur']) ?></td>
                        <td><?= htmlspecialchars($exercice['date']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= htmlspecialchars($exercice['id']) ?>">Modifier</a>
                            <a href="delete.php?id=<?= htmlspecialchars($exercice['id']) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?')">Supprimer</a>
                        </td>


                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>           
        </tbody>
    </table>
    <a href="index.php">Enregister un nouvel exercice</a>
</body>
</html>
