<?php
require('credentials.php');

try {
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (!empty($_POST['nom']) && !empty($_POST['id_type'])) {
    $sql = "INSERT INTO MATERIEL (nom, annee, details, id_type, id_parent)
            VALUES (:nom, :annee, :details, :id_type, :id_parent)";
   
    $ins = $connexion->prepare($sql);
   
    $parentValue = !empty($_POST['id_parent']) ? $_POST['id_parent'] : null;

    $ins->execute([
        ':nom'       => $_POST['nom'],
        ':annee'     => $_POST['annee'],
        ':details'   => $_POST['details'],
        ':id_type'   => $_POST['id_type'],
        ':id_parent' => $parentValue
    ]);

    header("Location: index.php");
    exit;
}

$selected = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sel = $connexion->prepare("SELECT * FROM vue_materiel WHERE id = ?");
    $sel->execute([$_GET['id']]);
    $selected = $sel->fetch();
}

$liste = $connexion->query("SELECT * FROM vue_materiel ORDER BY id ASC")->fetchAll();

$categories = $connexion->query("SELECT * FROM CATEGORIE ORDER BY libelle ASC")->fetchAll();

$parents = $connexion->query("SELECT id, nom FROM MATERIEL ORDER BY nom ASC")->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Inventaire Informatique</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: #eef2f7;
}

.header {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: white;
    padding: 25px 40px;
    font-size: 24px;
    font-weight: 600;
}

.container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
}

.card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

h2 {
    margin-top: 0;
    color: #1e293b;
}

.form-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

input, select, textarea {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #2563eb;
}

.btn {
    background: #2563eb;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.btn:hover {
    background: #1e40af;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f1f5f9;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
}

th, td {
    padding: 12px;
    text-align: left;
}

tr {
    border-bottom: 1px solid #e2e8f0;
}

tr:hover {
    background: #f8fafc;
    cursor: pointer;
}

.tag {
    background: #dbeafe;
    color: #1e40af;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
}

.detail {
    border-left: 5px solid #2563eb;
}

.close {
    float: right;
    text-decoration: none;
    color: red;
    font-weight: bold;
}
</style>
</head>

<body>

<div class="header">
     Gestion du Parc Informatique
</div>

<div class="container">

<?php if ($selected): ?>
<div class="card detail">
    <a href="index.php" class="close">✖</a>
    <h2><?php echo htmlspecialchars($selected['nom']); ?></h2>
    <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($selected['type_libelle']); ?></p>
    <p><strong>Année :</strong> <?php echo htmlspecialchars($selected['annee']); ?></p>
    <p><strong>Détails :</strong><br>
        <?php echo nl2br(htmlspecialchars($selected['details'] ?: 'Aucun détail')); ?>
    </p>
    <p><strong>Parent :</strong> <?php echo htmlspecialchars($selected['parent_nom'] ?: 'Aucun'); ?></p>
</div>
<?php endif; ?>

<div class="card">
    <h2> Ajouter un matériel</h2>

    <form method="post">
        <div class="form-group">
            <input name="nom" placeholder="Nom" required>
            <input name="annee" type="number" placeholder="Année" required>

            <select name="id_type" required>
                <option value="">Type</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?php echo $cat['id_type']; ?>">
                        <?php echo htmlspecialchars($cat['libelle']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <select name="id_parent">
                <option value="">Parent (optionnel)</option>
                <?php foreach($parents as $p): ?>
                    <option value="<?php echo $p['id']; ?>">
                        <?php echo htmlspecialchars($p['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <textarea name="details" placeholder="Détails techniques" style="grid-column: span 2;"></textarea>
        </div>

        <button class="btn">Ajouter</button>
    </form>
</div>

<div class="card">
    <h2> Liste du matériel</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Année</th>
                <th>Type</th>
                <th>Parent</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($liste as $item): ?>
            <tr onclick="window.location.href='?id=<?php echo $item['id']; ?>'">
                <td><span class="tag">#<?php echo $item['id']; ?></span></td>
                <td><?php echo htmlspecialchars($item['nom']); ?></td>
                <td><?php echo htmlspecialchars($item['annee']); ?></td>
                <td><?php echo htmlspecialchars($item['type_libelle']); ?></td>
                <td><?php echo htmlspecialchars($item['parent_nom'] ?: '—'); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

</div>

</body>
</html>
