<?php
require('credentials.php');

try {
    $connexion = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// --- DELETE ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $req = $connexion->prepare("DELETE FROM MATERIEL WHERE id = ?");
    $req->execute([$_GET['delete']]);
    header("Location: index.php");
    exit;
}

// --- MODE EDIT ---
$edit = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $req = $connexion->prepare("SELECT * FROM MATERIEL WHERE id = ?");
    $req->execute([$_GET['edit']]);
    $edit = $req->fetch();
}

// --- INSERT / UPDATE ---
if (!empty($_POST['nom']) && !empty($_POST['id_type'])) {

    $parentValue = !empty($_POST['id_parent']) ? $_POST['id_parent'] : null;

    if (!empty($_POST['id'])) {
        $sql = "UPDATE MATERIEL 
                SET nom = :nom, annee = :annee, details = :details, id_type = :id_type, id_parent = :id_parent
                WHERE id = :id";

        $req = $connexion->prepare($sql);
        $req->execute([
            ':nom' => $_POST['nom'],
            ':annee' => $_POST['annee'],
            ':details' => $_POST['details'],
            ':id_type' => $_POST['id_type'],
            ':id_parent' => $parentValue,
            ':id' => $_POST['id']
        ]);
    } else {
        $sql = "INSERT INTO MATERIEL (nom, annee, details, id_type, id_parent)
                VALUES (:nom, :annee, :details, :id_type, :id_parent)";
       
        $req = $connexion->prepare($sql);
        $req->execute([
            ':nom' => $_POST['nom'],
            ':annee' => $_POST['annee'],
            ':details' => $_POST['details'],
            ':id_type' => $_POST['id_type'],
            ':id_parent' => $parentValue
        ]);
    }

    header("Location: index.php");
    exit;
}

// --- DÉTAIL ---
$selected = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sel = $connexion->prepare("SELECT * FROM vue_materiel WHERE id = ?");
    $sel->execute([$_GET['id']]);
    $selected = $sel->fetch();
}

// --- LISTES ---
$categories = $connexion->query("SELECT * FROM CATEGORIE ORDER BY libelle ASC")->fetchAll();
$parents = $connexion->query("SELECT id, nom FROM MATERIEL ORDER BY nom ASC")->fetchAll();

// --- RECHERCHE MULTICRITÈRE ---
$conditions = [];
$params = [];

if (!empty($_GET['search_nom'])) {
    $conditions[] = "nom LIKE :nom";
    $params[':nom'] = "%" . $_GET['search_nom'] . "%";
}

if (!empty($_GET['search_type'])) {
    $conditions[] = "id_type = :type";
    $params[':type'] = $_GET['search_type'];
}

if (!empty($_GET['search_annee'])) {
    $conditions[] = "annee = :annee";
    $params[':annee'] = $_GET['search_annee'];
}

$sql = "SELECT * FROM vue_materiel";

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY id ASC";

$stmt = $connexion->prepare($sql);
$stmt->execute($params);
$liste = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Inventaire Informatique</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
/* TON CSS INCHANGÉ */
body { font-family: 'Poppins', sans-serif; margin: 0; background: #eef2f7; }
.header { background: linear-gradient(135deg, #2563eb, #1e40af); color: white; padding: 25px 40px; font-size: 24px; font-weight: 600; }
.container { max-width: 1200px; margin: 30px auto; padding: 20px; }
.card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
h2 { margin-top: 0; color: #1e293b; }
.form-group { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 15px; }
input, select, textarea { padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; }
input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; }
.btn { background: #2563eb; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration:none;}
.btn:hover { background: #1e40af; }
table { width: 100%; border-collapse: collapse; }
th { background: #f1f5f9; font-size: 12px; }
th, td { padding: 12px; }
tr { border-bottom: 1px solid #e2e8f0; }
.tag { background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 6px; }
.detail { border-left: 5px solid #2563eb; }
.close { float: right; color: red; }
</style>
</head>

<body>

<div class="header">💻 Gestion du Parc Informatique</div>

<div class="container">

<!-- RECHERCHE -->
<div class="card">
<h2>🔍 Recherche</h2>

<form method="get">
<div class="form-group">

<input type="text" name="search_nom" placeholder="Nom"
value="<?= $_GET['search_nom'] ?? '' ?>">

<input type="number" name="search_annee" placeholder="Année"
value="<?= $_GET['search_annee'] ?? '' ?>">

<select name="search_type">
<option value="">Type</option>
<?php foreach($categories as $cat): ?>
<option value="<?= $cat['id_type'] ?>"
<?php if(isset($_GET['search_type']) && $_GET['search_type'] == $cat['id_type']) echo 'selected'; ?>>
<?= htmlspecialchars($cat['libelle']) ?>
</option>
<?php endforeach; ?>
</select>

</div>

<button class="btn">Rechercher</button>
<a href="index.php" class="btn" style="background:#64748b;">Reset</a>

</form>
</div>

<?php if ($selected): ?>
<div class="card detail">
<a href="index.php" class="close">✖</a>
<h2><?= htmlspecialchars($selected['nom']) ?></h2>
<p><?= htmlspecialchars($selected['type_libelle']) ?></p>
<p><?= htmlspecialchars($selected['annee']) ?></p>
<p><?= nl2br(htmlspecialchars($selected['details'] ?: 'Aucun détail')) ?></p>
</div>
<?php endif; ?>

<!-- FORMULAIRE -->
<div class="card">
<h2><?= $edit ? "✏️ Modifier" : "➕ Ajouter" ?></h2>

<form method="post">

<?php if ($edit): ?>
<input type="hidden" name="id" value="<?= $edit['id'] ?>">
<?php endif; ?>

<div class="form-group">
<input name="nom" value="<?= $edit['nom'] ?? '' ?>" required>
<input name="annee" type="number" value="<?= $edit['annee'] ?? '' ?>" required>

<select name="id_type" required>
<option value="">Type</option>
<?php foreach($categories as $cat): ?>
<option value="<?= $cat['id_type'] ?>"
<?php if(isset($edit) && $edit['id_type'] == $cat['id_type']) echo 'selected'; ?>>
<?= htmlspecialchars($cat['libelle']) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="form-group">
<select name="id_parent">
<option value="">Parent</option>
<?php foreach($parents as $p): ?>
<option value="<?= $p['id'] ?>"
<?php if(isset($edit) && $edit['id_parent'] == $p['id']) echo 'selected'; ?>>
<?= htmlspecialchars($p['nom']) ?>
</option>
<?php endforeach; ?>
</select>

<textarea name="details"><?= $edit['details'] ?? '' ?></textarea>
</div>

<button class="btn"><?= $edit ? "Modifier" : "Ajouter" ?></button>
</form>
</div>

<!-- TABLEAU -->
<div class="card">
<h2>📋 Liste</h2>

<table>
<thead>
<tr>
<th>ID</th>
<th>Nom</th>
<th>Année</th>
<th>Type</th>
<th>Parent</th>
<th>Actions</th>
</tr>
</thead>

<tbody>
<?php foreach ($liste as $item): ?>
<tr>
<td>#<?= $item['id'] ?></td>
<td><?= htmlspecialchars($item['nom']) ?></td>
<td><?= htmlspecialchars($item['annee']) ?></td>
<td><?= htmlspecialchars($item['type_libelle']) ?></td>
<td><?= htmlspecialchars($item['parent_nom'] ?: '—') ?></td>
<td>
<a href="?id=<?= $item['id'] ?>">👁️</a>
<a href="?edit=<?= $item['id'] ?>">✏️</a>
<a href="?delete=<?= $item['id'] ?>" onclick="return confirm('Supprimer ?')">❌</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>

</div>
</body>
</html>
