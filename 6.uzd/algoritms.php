<!-- 6. uzdevums (PHP algoritms)
$orders = [
    ['order_id' => 1, 'customer' => 'Alise', 'product' => 'Grāmata'],
    ['order_id' => 1, 'customer' => 'Alise', 'product' => 'Pildspalva'],
    ['order_id' => 2, 'customer' => 'Bobs', 'product' => 'Dators'],
    ['order_id' => 2, 'customer' => 'Bobs', 'product' => 'Pelīte'],
    ['order_id' => 3, 'customer' => 'Čārlijs', 'product' => 'Kafijas automāts'],
];

Izveidojiet algoritmu, kas no $orders masīva izveido $groupedOrders daudzdimensiju hierarhisku masīvu, kur katrs pasūtījums satur savu identifikatoru, klienta vārdu un masīvu ar produktiem.

$groupedOrders = [
    [
        'order_id' => 1,
        'customer' => 'Alise',
        'products' => ['Grāmata', 'Pildspalva'],
    ],
    [
        'order_id' => 2,
        'customer' => 'Bobs',
        'products' => ['Dators', 'Pelīte'],
    ],
    [
        'order_id' => 3,
        'customer' => 'Čārlijs',
        'products' => ['Kafijas automāts'],
    ],
]; -->


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

// Izvadīt rezultātu
echo '<pre>';
print_r($groupedOrders);
echo '</pre>';


?>