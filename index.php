<?php

require "credentials.php";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
die("Connexion échouée");
}

$sql = "SELECT * FROM materiel_m2l";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Inventaire M2L</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Inventaire du matériel</h1>

<table>

<tr>
<th>ID</th>
<th>Nom</th>
<th>Année</th>
<th>Détails</th>
<th>Type</th>
<th>Appartenance</th>
</tr>

<?php

while($row = $result->fetch_assoc()){

echo "<tr>";

echo "<td>".$row["ID"]."</td>";
echo "<td>".$row["Nom"]."</td>";
echo "<td>".$row["Annee"]."</td>";
echo "<td>".$row["Details"]."</td>";
echo "<td>".$row["Type"]."</td>";
echo "<td>".$row["Appartenance"]."</td>";

echo "</tr>";

}

?>

</table>

</body>
</html>
