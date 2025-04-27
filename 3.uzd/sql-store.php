<!-- 3.uzdevums (Dati no MySQL datubāzes)
Izveidojiet jaunu MySQL lietotāju, kam ir pilna piekļuve 'sql_store' datubāzei.
Pieslēdzieties datubāzei izmantojot šo lietotāju jaunā failā 'sql-store.php'.
Atgrieziet datus no 'customers' tabulas kā HTML sarakstu. -->

<?php
$servername = "localhost";  
$username = "user111"; 
$password = "password";  
$dbname = "sql_store";  


try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
   
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    $stmt = $conn->prepare("SELECT * FROM customers");
    $stmt->execute();
    
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);



    echo "<ul>";
    foreach ($result as $row) {
        echo "<li>";
        echo "ID: " . $row["customer_id"] . "</div>";
        echo " " . $row["first_name"] . " " . $row["last_name"] . "</div>";
        echo " ";
        
        echo "Tālrunis: " . $row["phone"] . "<br>";
        if (isset($row["email"])) {
            echo "E-pasts: " . $row["email"] . "<br>";
        }
        echo "</div>";
        echo "</li>";
    }
    echo "</ul>";
} 



 catch(PDOException $e) {
    echo "Savienojuma kļūda: " . $e->getMessage();
}


    $conn = null;

?>