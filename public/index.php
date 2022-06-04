<?php

use Controllers\messengerController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$loader = new FilesystemLoader(dirname(__DIR__) . "/templates");
// Создаем логгер с именем "user"
$log = new Logger('user');
// Добавляем хендлер, который будет писать логи в файл
$userHandler = new StreamHandler('mes.log', Logger::INFO);
$log->pushHandler($userHandler);
$twig = new Environment($loader);
$controller = new messengerController($twig, $log);

date_default_timezone_set('Asia/Vladivostok');


$connection = new PDO('mysql:host=localhost;dbname=testDB;charset=utf8', 'root', '00012278');
$stmt = $connection->prepare('SELECT * from users;');
$stmt->execute();
$results = $stmt->fetchAll();
$users = array();
foreach($results as $result){
    $users[$result['login']]=$result['password'];
}


$sql_mes = 'SELECT * from mess';
$stmt2 = $connection->prepare($sql_mes);
$stmt2->execute();
$mes_sql = $stmt2->fetchAll();
$controller->ShowMes($mes_sql);


if (isset($_GET['logs'])) {
    echo("Список логов: ");
    $file = file_get_contents('mes.log');
    echo $file;
}


if (isset($_GET['login'])&&isset($_GET['password']) || (isset($_GET['logs'])) ) {
    setcookie('login', $_GET['login']);
    $usr = $_GET['login'];
    $pwd = $_GET['password'];

     if ($users[$usr]!="" && $users[$usr]==$pwd || $usr=="default"){
        $login_successful = true; 
        echo ("Авторизирован как   ");
        echo('<b>'.$_GET['login'].'</b>');
        $log->info('User name is ', ['who' => $usr]);
    }
    else if (!(isset($_GET['logs']))){
        echo "<p>";
        echo("Неверный логин или пароль!");
        $log->error('no login or passzord!');
    }
}


if ($login_successful){
$controller-> mesform();
}

 if (isset($_GET['message'])){
    $controller->add_message_to_file($_GET['message'], $_COOKIE['login'], $connection);
    header('Refresh: 0; url=index.php'); 
    
    
    
 if (isset($_GET['clear'])) {
    $sql = 'DELETE FROM mess';
    $stmt3 = $connection->prepare($sql);
    $stmt3->execute();
    
    $log->info('Chat is cleared');
}
   
}
