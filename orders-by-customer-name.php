<?php
// Inicializējam mainīgos
$servername = "localhost";  
$username = "user111"; 
$password = "password";  
$dbname = "sql_store";  
$searchName = "";
$results = [];
$message = "";
$hasSearched = false;

// Pārbaudām, vai forma ir nosūtīta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hasSearched = true;
    
    // Iegūstam un attīrām meklēšanas vārdu
    if (isset($_POST['customer_name'])) {
        $searchName = trim($_POST['customer_name']);
        
        // Ja ir ievadīts vārds, meklējam pasūtījumus
        if (!empty($searchName)) {
            try {
                // Izveidojam PDO savienojumu
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // SQL vaicājums ar subquery - meklējam pasūtījumus pēc klienta vārda
                $stmt = $conn->prepare("
                    SELECT 
                        o.order_id,
                        o.order_date,
                        o.status,
                        c.customer_id,
                        c.first_name,
                        c.last_name,
                       
                    FROM orders o
                    JOIN customers c ON o.customer_id = c.customer_id
                    WHERE c.customer_id IN (
                        SELECT customer_id 
                        FROM customers 
                        WHERE first_name LIKE :search_name
                    )
                    ORDER BY o.order_date DESC
                ");
                
                // Piesaistām parametru un izpildām vaicājumu
                $stmt->bindValue(':search_name', "%$searchName%", PDO::PARAM_STR);
                $stmt->execute();
                
                // Iegūstam visus rezultātus
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($results) === 0) {
                    $message = "Nav atrasti pasūtījumi klientam ar vārdu '$searchName'.";
                }
                
                // Aizveram savienojumu
                $conn = null;
                
            } catch(PDOException $e) {
                $message = "Datubāzes kļūda: " . $e->getMessage();
            }
        } else {
            $message = "Lūdzu, ievadiet klienta vārdu meklēšanai!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasūtījumu meklēšana pēc klienta vārda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            color: #333;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        
        form {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .message {
            margin: 20px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #ccc;
        }
        
        .error {
            border-left-color: #dc3545;
            background-color: #f8d7da;
        }
        
        .results {
            margin-top: 20px;
        }
        
        .customer-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .customer-info {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .customer-name {
            font-size: 18px;
            font-weight: bold;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .orders-table th, .orders-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .orders-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .orders-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status {
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
    </style>
</head>
<body>
    <h1>Pasūtījumu meklēšana pēc klienta vārda</h1>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="customer_name">Ievadiet klienta vārdu:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($searchName); ?>" placeholder="Piemēram: John">
        <button type="submit">Meklēt pasūtījumus</button>
    </form>
    
    <?php if (!empty($message)): ?>
        <div class="message <?php echo (count($results) === 0 && $hasSearched) ? 'error' : ''; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (count($results) > 0): ?>
        <div class="results">
            <?php
            $currentCustomerId = null;
            $customersArr = [];
            
            // Organizējam rezultātus pēc klienta
            foreach ($results as $row) {
                $customerId = $row['customer_id'];
                
                if (!isset($customersArr[$customerId])) {
                    $customersArr[$customerId] = [
                        'customer_id' => $customerId,
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                       
                        'orders' => []
                    ];
                }
                
                $customersArr[$customerId]['orders'][] = [
                    'order_id' => $row['order_id'],
                    'order_date' => $row['order_date'],
                    'status' => $row['status']
                ];
            }
            
            // Izvadām rezultātus pa klientiem
            foreach ($customersArr as $customer):
            ?>
                <div class="customer-box">
                    <div class="customer-info">
                        <div class="customer-name"><?php echo htmlspecialchars($customer['first_name']) . ' ' . htmlspecialchars($customer['last_name']); ?></div>
                        <div>E-pasts: <?php echo htmlspecialchars($customer['email']); ?></div>
                        <div>Klienta ID: <?php echo htmlspecialchars($customer['customer_id']); ?></div>
                    </div>
                    
                    <h3>Pasūtījumi:</h3>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Pasūtījuma ID</th>
                                <th>Datums</th>
                                <th>Statuss</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customer['orders'] as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                    <td><?php echo date('d.m.Y', strtotime($order['order_date'])); ?></td>
                                    <td>
                                        <?php 
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
                                        ?>
                                        <span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($order['status']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($hasSearched): ?>
        <p>Nav atrasti pasūtījumi.</p>
    <?php endif; ?>
</body>
</html>