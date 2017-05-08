<?php
$db_host = 'db';
$db_name = 'sf';
$db_user = 'root';
$db_password = 'root';


try {

    $page = isset ($_GET['page']) ? $_GET['page'] : 1;  //pobiera wartość page z url. Default= 1.
    $onPage = isset ($_GET['onpage']) ? $_GET['onpage'] : 10; // --- || -----  Default =10.

    $connection = new PDO("mysql:host=$db_host;name=$db_name", $db_user, $db_password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $startLimit = ($page > 1) ? (($page * $onPage) - $onPage) : 0;

    $result = $connection->query("SELECT * FROM sf.article LIMIT {$startLimit},{$onPage}")
        ->fetchAll(PDO::FETCH_ASSOC);  // przywołuje tu zapytanie jako tablice asocjacyjna.
//        ->fetchAll(PDO::FETCH_OBJ);          // przywołanie tu zapytania jako obiekt


//    var_dump($result);
//
//    foreach ($result as $key => $item)
//    {
//        var_dump($key);
//            var_dump($item);
//    }



//    $tablica = array ('id' => '100',
//        'imie' => 'Przemek',
//        'nazwisko' =>'kmiecik');
//    $tablica2 = array ("Janek", "Tadek", "Ziutek");
//
//    $tablica3 = array_values($tablica);
//
//    var_dump($tablica);
//    var_dump(array_shift($tablica));
//    var_dump($tablica);
//    var_dump(array_shift($tablica));
//    var_dump($tablica);
//    array_unshift($tablica,"janek","imie", "nazwisko");
//    var_dump($tablica);
//
//    var_dump(next($tablica));
//    var_dump(next($tablica));
//
//    var_dump(end($result));
//    var_dump(reset($result));
//    var_dump(current($result));
//    var_dump(next($result));
//    var_dump(each($result));
//    var_dump(next($result));
//    die;


    $result2 = $connection->query("SELECT COUNT(id) as rows FROM sf.article")
        ->fetch(PDO::FETCH_ASSOC);

    $all = $result2['rows'];
    $pages = (int)ceil($all / $onPage);


    if (!$result) {
        echo 'zapytanie do bazy niepoprawne';

    } else {

        echo 'zapytanie do bazy poprawne';
    }

} catch (PDOException $e) {
    echo 'Polaczenie z baza danych nieudane' . $e->getMessage();
    exit;
}
$connection = null;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <div class="content">
            <?php foreach ($result as $item): ?>
                <p> <?php echo $item['id']; ?> <br/>
                    <?php echo $item['title']; ?><br/>
                    <?php echo $item['content']; ?><br/></p>
            <?php endforeach; ?>
        </div>

        <div class="pages">
            <?php for ($i=1; $i<=$pages;$i++): ?>
                <a href="?page=<?php echo $i;?>&onpage=<?php echo $onPage?>"><b></b><?php echo $i; ?>  </a>

            <?php endfor;?>
        </div>

    </body>
</html>



