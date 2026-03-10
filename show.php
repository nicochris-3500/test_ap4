<?php
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: index.php");
    exit;
}

require('credentials.php');

$connexion = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=$charset",
    $user,
    $password,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$requete = $connexion->prepare("SELECT * FROM MATERIEL_M2L WHERE ID = :id");
$requete->execute(['id' => $_GET['id']]);
$materiel = $requete->fetch(PDO::FETCH_ASSOC);

if (!$materiel) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Détail du matériel</title>
</head>
<body>

<h1>Détail du matériel</h1>

<table border="1" cellpadding="6">
    <?php foreach ($materiel as $cle => $val): ?>
        <tr>
            <td><strong><?= htmlspecialchars($cle) ?></strong></td>
            <td><?= htmlspecialchars($val) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="index.php">Retour à la liste</a>

</body>
</html>
