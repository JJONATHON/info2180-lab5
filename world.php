<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

// Read the GET parameter (?country=...)
$country = $_GET['country'] ?? '';

// If no country is provided -> return all countries
if ($country === '') {
  $stmt = $conn->query("SELECT * FROM countries");
} else {
  // Use LIKE for partial searches
  $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
  $stmt->execute(['country' => "%$country%"]);
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<ul>
  <?php foreach ($results as $row): ?>
    <li><?= $row['name'] . ' is ruled by ' . $row['head_of_state']; ?></li>
  <?php endforeach; ?>
</ul>