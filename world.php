<?php
// world.php

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
  $conn = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password
  );
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  http_response_code(500);
  echo "Database connection failed: " . htmlspecialchars($e->getMessage());
  exit;
}

$country = isset($_GET['country']) ? trim($_GET['country']) : '';
$lookup = isset($_GET['lookup']) ? trim($_GET['lookup']) : '';

if ($country === '') {
  echo '<div class="message">Please enter a country name.</div>';
  exit;
}

$pattern = '%' . $country . '%';

try {
  if ($lookup === 'cities') {
    // CITIES QUERY (JOIN between countries and cities)
    $stmt = $conn->prepare("
            SELECT cities.name, cities.district, cities.population
            FROM cities
            JOIN countries ON countries.code = cities.country_code
            WHERE countries.name LIKE :country
            ORDER BY cities.population DESC;
        ");
    $stmt->bindParam(':country', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results) {
      echo '<div class="message">No cities found for that country.</div>';
      exit;
    }

    echo '<table>';
    echo '<thead><tr><th>Name</th><th>District</th><th>Population</th></tr></thead><tbody>';
    foreach ($results as $row) {
      echo '<tr>';
      echo '<td>' . htmlspecialchars($row['name']) . '</td>';
      echo '<td>' . htmlspecialchars($row['district']) . '</td>';
      echo '<td>' . htmlspecialchars($row['population']) . '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';

  } else {
    // COUNTRIES QUERY
    $stmt = $conn->prepare("
            SELECT name, continent, independence_year, head_of_state
            FROM countries
            WHERE name LIKE :country;
        ");
    $stmt->bindParam(':country', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$results) {
      echo '<div class="message">No countries found matching that name.</div>';
      exit;
    }

    echo '<table>';
    echo '<thead><tr><th>Name</th><th>Continent</th><th>Independence</th><th>Head of State</th></tr></thead><tbody>';
    foreach ($results as $row) {
      echo '<tr>';
      echo '<td>' . htmlspecialchars($row['name']) . '</td>';
      echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
      echo '<td>' . htmlspecialchars($row['independence_year']) . '</td>';
      echo '<td>' . htmlspecialchars($row['head_of_state']) . '</td>';
      echo '</tr>';
    }
    echo '</tbody></table>';
  }

} catch (PDOException $e) {
  http_response_code(500);
  echo "Query failed: " . htmlspecialchars($e->getMessage());
  exit;
}
