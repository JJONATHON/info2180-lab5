<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$country = $_GET['country'] ?? '';
$lookup = $_GET['lookup'] ?? 'country';

try {
  $conn = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );
} catch (PDOException $e) {
  http_response_code(500);
  echo '<div class="message">Database connection error.</div>';
  exit();
}

if ($lookup === 'cities') {
  // =========================
  // Cities lookup
  // =========================
  if (!empty($country)) {
    $stmt = $conn->prepare(
      "SELECT cities.name, cities.district, cities.population
             FROM cities
             JOIN countries ON cities.country_code = countries.code
             WHERE countries.name LIKE :country"
    );
    $stmt->execute(['country' => "%$country%"]);
  } else {
    $stmt = $conn->query(
      "SELECT cities.name, cities.district, cities.population
             FROM cities"
    );
  }

  $results = $stmt->fetchAll();

  if (!$results) {
    echo '<div class="message">No cities found.</div>';
    exit();
  }

  echo '<table>';
  echo '<thead><tr>
            <th>Name</th>
            <th>District</th>
            <th>Population</th>
          </tr></thead>';
  echo '<tbody>';

  foreach ($results as $row) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
    echo '<td>' . htmlspecialchars($row['district']) . '</td>';
    echo '<td>' . htmlspecialchars($row['population']) . '</td>';
    echo '</tr>';
  }

  echo '</tbody></table>';
} else {
  // =========================
  // Country lookup (default)
  // =========================
  if (!empty($country)) {
    $stmt = $conn->prepare(
      "SELECT name, continent, independence_year, head_of_state
             FROM countries
             WHERE name LIKE :country"
    );
    $stmt->execute(['country' => "%$country%"]);
  } else {
    $stmt = $conn->query(
      "SELECT name, continent, independence_year, head_of_state
             FROM countries"
    );
  }

  $results = $stmt->fetchAll();

  if (!$results) {
    echo '<div class="message">No countries found.</div>';
    exit();
  }

  echo '<table>';
  echo '<thead><tr>
            <th>Name</th>
            <th>Continent</th>
            <th>Independence</th>
            <th>Head of State</th>
          </tr></thead>';
  echo '<tbody>';

  foreach ($results as $row) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
    echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
    echo '<td>' . htmlspecialchars($row['independence_year'] ?? 'N/A') . '</td>';
    echo '<td>' . htmlspecialchars($row['head_of_state'] ?? 'N/A') . '</td>';
    echo '</tr>';
  }

  echo '</tbody></table>';
}
