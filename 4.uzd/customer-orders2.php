<!-- 4.uzdevums (MySQL Join)
Izveidojiet 'customer-orders.php' failu, kurā tiek attēloti dati par visiem klientiem (customers) un to pasūtījumiem (orders).
Atgrieziet datus kā hierarhisku HTML sarakstu. -->

<?php
// Datubāzes savienojuma parametri
$servername = "localhost";  
$username = "user111";
$password = "password";  
$dbname = "sql_store";  

// Izveidojam savienojumu
$conn = new mysqli($servername, $username, $password, $dbname);

// Pārbaudām savienojumu
if ($conn->connect_error) {
    die("Savienojums neizdevās: " . $conn->connect_error);
}

// Iestatām UTF-8 kodējumu
$conn->set_charset("utf8");

// SQL vaicājums, kas iegūst visus klientus
$sql = "SELECT c.customer_id, c.first_name, c.last_name, c.address, c.phone
        FROM customers c
        ORDER BY c.customer_id";

$result = $conn->query($sql);

// HTML dokumenta sākums
echo "<!DOCTYPE html>
<html>
<head>
    <title>Klienti un pasūtījumi</title>
    <meta charset='UTF-8'>
</head>
<body>
    <h1>Klienti un viņu pasūtījumi</h1>";

if ($result->num_rows > 0) {
    echo "<ul>";
    
    while($row = $result->fetch_assoc()) {
        echo "<li><strong>Klients:</strong> " . $row["first_name"] . " " . $row["last_name"] .
             " (ID: " . $row["customer_id"] . ")" .
             "<br><strong>Adrese:</strong> " . $row["address"] .
             "<br><strong>Tālrunis:</strong> " . $row["phone"] .
             "<br><strong>Pasūtījumi:</strong>";
        
        // Jauns vaicājums, lai iegūtu pasūtījumus katram klientam
        $order_sql = "SELECT * FROM orders WHERE customer_id = " . $row["customer_id"];
        $order_result = $conn->query($order_sql);
        
        echo "<ul>";
        
        if ($order_result->num_rows > 0) {
            while($order_row = $order_result->fetch_assoc()) {
                echo "<li>Pasūtījuma ID: " . $order_row["order_id"] .
                     "<br>Datums: " . $order_row["order_date"] .
                     "<br>Statuss: " . $order_row["status"];
                
                // Ja nepieciešams, varat iegūt papildu informāciju par pasūtījuma summu
                // No order_items tabulas vai kādas citas tabulas, kas satur summas
                
                echo "</li>";
            }
        } else {
            echo "<li>Nav pasūtījumu</li>";
        }
        
        echo "</ul></li>";
    }
    
    echo "</ul>";
} else {
    echo "<p>Dati nav atrasti</p>";
}

// Aizveram savienojumu
$conn->close();

echo "</body>
</html>";
?>