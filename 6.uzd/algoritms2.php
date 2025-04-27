<?php
$orders = [
    ['order_id' => 1, 'customer' => 'Alise', 'product' => 'Grāmata'],
    ['order_id' => 1, 'customer' => 'Alise', 'product' => 'Pildspalva'],
    ['order_id' => 2, 'customer' => 'Bobs', 'product' => 'Dators'],
    ['order_id' => 2, 'customer' => 'Bobs', 'product' => 'Pelīte'],
    ['order_id' => 3, 'customer' => 'Čārlijs', 'product' => 'Kafijas automāts'],
];

// Inicializējam tukšu masīvu, kas glabās grupētos pasūtījumus
$groupedOrders = [];

// Caurskatām visus pasūtījumus
foreach ($orders as $order) {
    // Ja tāds pasūtījums jau eksistē, pievienojam produktu
    if (isset($groupedOrders[$order['order_id']])) {
        $groupedOrders[$order['order_id']]['products'][] = $order['product'];
    } else {
        // Ja pasūtījums vēl neeksistē, izveidojam jaunu ierakstu
        $groupedOrders[$order['order_id']] = [
            'order_id' => $order['order_id'],
            'customer' => $order['customer'],
            'products' => [$order['product']]
        ];
    }
}

// Pārveidojam grupētos pasūtījumus masīvā (ja nepieciešams, lai saglabātu secību)
$groupedOrders = array_values($groupedOrders);

// Izdrukājam rezultātu
echo "<pre>";
print_r($groupedOrders);
echo "</pre>";

foreach($groupedOrders as $o) {
    echo $o['order_id'] ;
    echo ' ';
    echo $o['customer'];

echo "<br>";
    foreach($o['products'] as $p) {
        echo $p . " ";
    }
    echo "<br>";
   

}


?>
