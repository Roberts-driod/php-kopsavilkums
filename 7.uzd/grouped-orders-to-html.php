<!-- 7.uzdevums (PHP daudzdimensiju masīva izvade HTML sarakstā)
Izveidojiet PHP skriptu 'grouped-orders-to-html.php', kas 5.uzdevuma masīvu atgriež kā HTML sarakstu. -->


<?php
// Sākotnējais masīvs
$orders = [
    ['order_id' => 1, 'customer' => 'Alise', 'product' => 'Grāmata'],
    ['order_id' => 1, 'customer' => 'Alise', 'product' => 'Pildspalva'],
    ['order_id' => 2, 'customer' => 'Bobs', 'product' => 'Dators'],
    ['order_id' => 2, 'customer' => 'Bobs', 'product' => 'Pelīte'],
    ['order_id' => 3, 'customer' => 'Čārlijs', 'product' => 'Kafijas automāts'],
];

// Tukšs masīvs rezultātam
$groupedOrders = [];

// Pagaidu masīvs, lai sekotu līdzi jau apstrādātajiem pasūtījumiem
$processedOrders = [];

// Apstaigāt katru ierakstu sākotnējā masīvā
foreach ($orders as $order) {
    $orderId = $order['order_id'];
    
    // Ja šis pasūtījuma ID vēl nav apstrādāts
    if (!isset($processedOrders[$orderId])) {
        // Izveidot jaunu ierakstu rezultātu masīvā
        $newOrder = [
            'order_id' => $orderId,
            'customer' => $order['customer'],
            'products' => [$order['product']]
        ];
        
        // Pievienot jauno ierakstu rezultātu masīvam
        $groupedOrders[] = $newOrder;
        
        // Atzīmēt šo pasūtījuma ID kā apstrādātu un saglabāt tā indeksu
        $processedOrders[$orderId] = count($groupedOrders) - 1;
    } else {
        // Ja pasūtījuma ID jau ir apstrādāts, pievienot produktu esošajam ierakstam
        $index = $processedOrders[$orderId];
        $groupedOrders[$index]['products'][] = $order['product'];
    }
}

// HTML dokumenta sākums
echo "<!DOCTYPE html>
<html>
<head>
    <title>Pasūtījumu saraksts</title>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        .order { margin-bottom: 20px; border: 1px solid #ddd; padding: 10px; border-radius: 5px; }
        .order-header { background-color: #f5f5f5; padding: 5px; margin-bottom: 10px; }
        .product-list { margin-left: 20px; }
    </style>
</head>
<body>
    <h1>Pasūtījumu saraksts</h1>";

// Izvade HTML saraksta formātā
echo "<ul>";
foreach ($groupedOrders as $order) {
    echo "<li class='order'>
            <div class='order-header'>
                <strong>Pasūtījuma ID:</strong> " . $order['order_id'] . "<br>
                <strong>Klients:</strong> " . $order['customer'] . "
            </div>
            <div>
                <strong>Produkti:</strong>
                <ul class='product-list'>";
    
    foreach ($order['products'] as $product) {
        echo "<li>" . $product . "</li>";
    }
    
    echo "  </ul>
            </div>
          </li>";
}
echo "</ul>";

// HTML dokumenta beigas
echo "</body>
</html>";
?>