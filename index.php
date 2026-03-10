<?php
require('credentials.php');

$connexion = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=$charset",
    $user,
    $password,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$requete = $connexion->prepare("SELECT * FROM MATERIEL_M2L ORDER BY ID");
$requete->execute();
$materiels = $requete->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Liste du matériel</title>
</head>
<body>

<h1>Liste du matériel</h1>

<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Année</th>
        <th>Détails</th>
        <th>Type</th>
        <th>Appartenance</th>
    </tr>

    <?php foreach ($materiels as $m): ?>
        <tr>
            <td>
                <a href="show.php?id=<?= $m['ID'] ?>">
                    <?= htmlspecialchars($m['ID']) ?>
                </a>
            </td>
            <td><?= htmlspecialchars($m['Nom']) ?></td>
            <td><?= htmlspecialchars($m['Année']) ?></td>
            <td><?= htmlspecialchars($m['Détails']) ?></td>
            <td><?= htmlspecialchars($m['Type']) ?></td>
            <td><?= htmlspecialchars($m['Appartenance']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
