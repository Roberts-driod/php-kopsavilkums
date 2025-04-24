<!-- 5.uzdevums (MySQL subqueries)
Izveidojiet HTML formu jaunā failā 'orders-by-customer-name.php' 
ar vienu ievades lauku, kurā jāievada vārds. Izmantojot šo vārdu (first_name) atlasiet visus klienta pasūtījumus un attēlojiet to šajā pašā failā. -->


<?php
// Pieslēgšanās datubāzei - nomainiet pēc nepieciešamības
$servername = "localhost";
$username = "user111";
$password = "password";
$dbname = "sql_store";

// Savienojuma izveide
$conn = new mysqli($servername, $username, $password, $dbname);

// Savienojuma pārbaude
if ($conn->connect_error) {
    die("Savienojums neizdevās: " . $conn->connect_error);
}

// Iestatām UTF-8 kodējumu
$conn->set_charset("utf8");

// HTML dokumenta sākums
echo "<!DOCTYPE html>
<html>
<head>
    <title>Pasūtījumu meklēšana pēc klienta vārda</title>
    <meta charset='UTF-8'>
</head>
<body>
    <h1>Pasūtījumu meklēšana pēc klienta vārda</h1>
    
    <form method='post' action=''>
        <label for='first_name'>Ievadiet klienta vārdu:</label>
        <input type='text' name='first_name' id='first_name'>
        <input type='submit' value='Meklēt'>
    </form>";

// Pārbaudam, vai forma ir nosūtīta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['first_name'])) {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    
    // SQL vaicājums ar apakšvaicājumu (subquery)
    $sql = "SELECT o.* 
            FROM orders o
            WHERE o.customer_id IN (
                SELECT c.customer_id
                FROM customers c
                WHERE c.first_name LIKE '%$first_name%'
            )
            ORDER BY o.order_date DESC";
    
    $result = $conn->query($sql);
    
    echo "<h2>Pasūtījumi klientam ar vārdu: $first_name</h2>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1'>
              <tr>
                <th>Pasūtījuma ID</th>
                <th>Datums</th>
                <th>Statuss</th>
                <th>Klienta ID</th>
              </tr>";
              
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                  <td>" . $row["order_id"] . "</td>
                  <td>" . $row["order_date"] . "</td>
                  <td>" . $row["status"] . "</td>
                  <td>" . $row["customer_id"] . "</td>
                  </tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Pasūtījumi nav atrasti vai arī klients ar šādu vārdu nepastāv.</p>";
    }
    
    // Papildus parādām klientu informāciju
    $client_sql = "SELECT * FROM customers WHERE first_name LIKE '%$first_name%'";
    $client_result = $conn->query($client_sql);
    
    if ($client_result->num_rows > 0) {
        echo "<h2>Klienti ar vārdu: $first_name</h2>";
        echo "<ul>";
        
        while($client_row = $client_result->fetch_assoc()) {
            echo "<li>ID: " . $client_row["customer_id"] . 
                 ", Vārds: " . $client_row["first_name"] . 
                 ", Uzvārds: " . $client_row["last_name"] . 
                 ", Tālrunis: " . $client_row["phone"] . "</li>";
        }
        
        echo "</ul>";
    }
}

// Aizveram savienojumu
$conn->close();

echo "</body>
</html>";
?>