<?php
require 'functions.php';
$host = '127.0.0.1';
$dbname = 'TP6';
$username = 'root';
$password = '';

$conn = db_connection($host,$dbname,$username,$password);
create_exercice($conn);
$exercices = read_exercice($conn);
if(isset($_GET['id']) && isset($_GET['action'])){
    if($_GET['action'] =='delete'){
        delete_exercice($conn);
    }
    elseif($_GET['action'] =='update'){
        $exercice = update_exercice($conn);
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
        <div class="form">
            <?php if(!isset($_GET['action'])): ?>
                <div class='add'>
                    <h1>Ajouter un exercice</h1>
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
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['action'])=='update'):?>
                <div class='update'>
                    <h1>Modifier l'exercice</h1>
                    <form action="" method="post">
                        <fieldset>
                            <legend>Informations sur l'exercice</legend>
                            <div>
                                <label for="titre">Titre de l'exercice</label>
                                <input type="text" id="titre" name="titre"  value="<?= htmlspecialchars($exercice['titre']) ?>" required>
                            </div>
                            <div>
                                <label for="auteur">Auteur de l'exercice</label>
                                <input type="text" id="auteur" name="auteur" value="<?= htmlspecialchars($exercice['auteur']) ?>" required>
                            </div>
                            <div>
                                <label for="date">Date de création</label>
                                <input type="date" id="date" name="date" value="<?= htmlspecialchars($exercice['date']) ?>" required>
                            </div>
                            <input type="submit" value="Mettre à jour">
                        </fieldset>
                    </form>
                </div>
            <?php endif;?>
        </div>

        <div class="read">
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
                                    <a href="index.php?id=<?= htmlspecialchars($exercice['id']) ?>&action=<?= htmlspecialchars('update') ?>">Modifier</a>
                                    <a href="index.php?id=<?= htmlspecialchars($exercice['id']) ?>&action=<?= htmlspecialchars('delete') ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet exercice ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>           
                </tbody>
            </table>

        </div>
    </div>
</body>
</html>
