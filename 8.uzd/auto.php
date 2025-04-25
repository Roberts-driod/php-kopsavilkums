<!-- 8. uzdevums (OOP)
Izveidojiet skriptu 'auto.php' un uzrakstiet tajā klasi Auto, kas satur šādas īpašības: marka, modelis, gads.
Pievienojiet metodi showInfo(), kas atgriež visu minēto informāciju par auto.
Izveidojiet divas Auto klases instances ar dažādām vērtībām un izsauciet showInfo() metodi abiem objektiem.
Paplašiniet Auto klasi, pievienojot konstruktoru, kas pieņem marku, modeli un gadu kā parametrus. -->


<?php
/**
 * Auto klase, kas satur informāciju par automašīnu
 */
class Auto {
    // Klases īpašības
    public $marka;
    public $modelis;
    public $gads;
    
   
    public function __construct($marka, $modelis, $gads) {
        $this->marka = $marka;
        $this->modelis = $modelis;
        $this->gads = $gads;
    }
    
    
    public function showInfo() {
        return "Auto: {$this->marka} {$this->modelis}, Gads: {$this->gads}";
    }
}

// Izveidojam pirmo Auto klases instanci
$auto1 = new Auto("BMW", "X5", 2022);

// Izveidojam otro Auto klases instanci
$auto2 = new Auto("Toyota", "Corolla", 2019);

// Izsaucam showInfo() metodi abiem objektiem un izvadām rezultātu
echo "<h2>Pirmā automašīna:</h2>";
echo $auto1->showInfo();

echo "<hr>";

echo "<h2>Otrā automašīna:</h2>";
echo $auto2->showInfo();
?>