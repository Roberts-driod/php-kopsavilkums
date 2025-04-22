<!-- 4.uzdevums (MySQL Join)
Izveidojiet 'customer-orders.php' failu, kurā tiek attēloti dati par visiem klientiem (customers) un to pasūtījumiem (orders).
Atgrieziet datus kā hierarhisku HTML sarakstu. -->

<?php
// Datubāzes savienojuma parametri
$servername = "localhost";  
$username = "user111"; 
$password = "password";  
$dbname = "sql_store";  

try {
    // Izveidojam PDO savienojumu
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Iestatām PDO kļūdu režīmu uz izņēmumiem
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sagatvojam SQL vaicājumu, kas iegūst klientus un to pasūtījumus
    $stmt = $conn->prepare("
        SELECT 
            c.customer_id,
            c.first_name,
            c.last_name,
           
            c.phone,
            o.order_id,
            o.order_date,
            o.status,
            o.shipped_date,
            o.comments
        FROM customers c
        LEFT JOIN orders o ON c.customer_id = o.customer_id
        ORDER BY c.customer_id, o.order_date DESC
    ");
    $stmt->execute();
    
    // HTML dokumenta sākums
    echo "<!DOCTYPE html>
    <html lang='lv'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Klienti un pasūtījumi</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                line-height: 1.6;
                color: #333;
            }
            h1, h2 {
                color: #333;
            }
            .customer {
                margin-bottom: 30px;
                padding: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f9f9f9;
            }
            .customer-info {
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }
            .customer-name {
                font-size: 20px;
                font-weight: bold;
                color: #2c3e50;
            }
            .customer-details {
                margin-top: 5px;
                color: #666;
            }
            .orders-list {
                list-style-type: none;
                padding-left: 0;
            }
            .order-item {
                margin-bottom: 15px;
                padding: 10px;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .order-header {
                font-weight: bold;
                display: flex;
                justify-content: space-between;
                margin-bottom: 5px;
            }
            .order-status {
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 0.9em;
            }
            .status-shipped {
                background-color: #d4edda;
                color: #155724;
            }
            .status-pending {
                background-color: #fff3cd;
                color: #856404;
            }
            .status-processing {
                background-color: #d1ecf1;
                color: #0c5460;
            }
            .status-cancelled {
                background-color: #f8d7da;
                color: #721c24;
            }
            .no-orders {
                font-style: italic;
                color: #777;
            }
            .order-date {
                color: #666;
            }
            .order-details {
                margin-top: 5px;
                font-size: 0.95em;
            }
        </style>
    </head>
    <body>
        <h1>Klienti un viņu pasūtījumi</h1>";

    // Masīvs klientu datiem un to pasūtījumiem
    $customers = [];
    
    // Apstrādājam datus un organizējam pēc klienta
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $customer_id = $row['customer_id'];
        
        // Ja šis ir jauns klients, pievienojam to masīvam
        if (!isset($customers[$customer_id])) {
            $customers[$customer_id] = [
                'customer_id' => $customer_id,
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                
                'phone' => $row['phone'],
                'orders' => []
            ];
        }
        
        // Ja ir pasūtījuma ID, pievienojam pasūtījumu klientam
        if ($row['order_id']) {
            $customers[$customer_id]['orders'][] = [
                'order_id' => $row['order_id'],
                'order_date' => $row['order_date'],
                'status' => $row['status'],
                'shipped_date' => $row['shipped_date'],
                'comments' => $row['comments']
            ];
        }
    }
    
    // Izvadām klientus un to pasūtījumus
    foreach ($customers as $customer) {
        echo "<div class='customer'>";
        echo "<div class='customer-info'>";
        echo "<div class='customer-name'>" . $customer['first_name'] . " " . $customer['last_name'] . " (ID: " . $customer['customer_id'] . ")</div>";
        echo "<div class='customer-details'>";
        
        echo "Tālrunis: " . $customer['phone'];
        echo "</div>";
        echo "</div>";
        
        echo "<h3>Pasūtījumi:</h3>";
        
        // Pārbaudam, vai klientam ir pasūtījumi
        if (count($customer['orders']) > 0) {
            echo "<ul class='orders-list'>";
            foreach ($customer['orders'] as $order) {
                echo "<li class='order-item'>";
                
                // Statusam piešķiram klasi atkarībā no vērtības
                $statusClass = "";
                switch (strtolower($order['status'])) {
                    case 'shipped':
                        $statusClass = "status-shipped";
                        break;
                    case 'pending':
                        $statusClass = "status-pending";
                        break;
                    case 'processing':
                        $statusClass = "status-processing";
                        break;
                    case 'cancelled':
                        $statusClass = "status-cancelled";
                        break;
                }
                
                echo "<div class='order-header'>";
                echo "<span>Pasūtījums #" . $order['order_id'] . "</span>";
                echo "<span class='order-status " . $statusClass . "'>" . $order['status'] . "</span>";
                echo "</div>";
                
                echo "<div class='order-date'>Datums: " . date('d.m.Y', strtotime($order['order_date'])) . "</div>";
                
                echo "<div class='order-details'>";
                if ($order['shipped_date']) {
                    echo "Piegādāts: " . date('d.m.Y', strtotime($order['shipped_date'])) . "<br>";
                }
                if ($order['comments']) {
                    echo "Komentāri: " . $order['comments'];
                }
                echo "</div>";
                
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='no-orders'>Šim klientam nav pasūtījumu.</p>";
        }
        
        echo "</div>";
    }
    
    echo "</body>
    </html>";

} catch(PDOException $e) {
    echo "Savienojuma kļūda: " . $e->getMessage();
}

// Aizveram savienojumu
$conn = null;
?>