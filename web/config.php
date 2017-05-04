<?php
$db_host = 'db';
$db_name = 'sf';
$db_user = 'root';
$db_password = 'root';


try {
    $connection = new PDO("mysql:host=$db_host;name=$db_name", $db_user, $db_password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo 'Połączony z bazą danych';

    $result = $connection->query('use sf')
        ->execute();


    $result = $connection->query('SELECT id FROM article WHERE id BETWEEN 999 AND 1005')
        ->fetch(PDO::FETCH_OBJ);


//    $wiesze =  $result->rows();

//    var_dump($wiesze);die;

    if (!$result) {
        echo 'zapytanie do bazy niepoprawne';
    } else {


        var_dump($result);die;
        echo 'zapytanie do bazy poprawne';
        ceil();
        trim();

    }


} catch (PDOException $e) {
    echo 'Polaczenie z baza danych nieudane' . $e->getMessage();
    exit;
}
$connection = null;

