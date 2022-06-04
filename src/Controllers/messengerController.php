<?php

namespace Controllers;
use Twig\Environment;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;

class messengerController
{
    private $twig;
    private $log;
    private $messengerHandler;

    public function __construct($twig)
    {
        $this->twig = $twig;
        $this->log = new Logger('action');
        $this->messengerHandler = new StreamHandler('mes.log', Logger::INFO);
        echo $this->twig->render('main.html.twig');
    }

   
function mesform(){
    echo $this->twig->render('mesform.html.twig');
    }
    


function add_message_to_file($message,$log, $conn){
        if ($message !== '') {
            $now = date("Y-m-d H:i:s");
            $sql = 'insert into mess (user_date, user_login, user_message) values (:now, :login, :message)';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam('now', $now, PDO::PARAM_STR);
            $stmt->bindParam('login', $log, PDO::PARAM_STR);
            $stmt->bindParam('message', $message, PDO::PARAM_STR);
            $stmt->execute();
            $this->log->pushHandler($this->messengerHandler);
            $this->log->info('New message', ['user' => $log, 'send' => $message]);
            
        }
    }
    
    
    function ShowMes($mess){
       foreach ($mess as $message){
          
          echo '<p style="color: blue">'.$message['user_date']. "   ".'<b style="color: black">' .
          $message['user_login'].":   ".'</b>'. '<big style="color: black">'.
          $message['user_message'].'</big>'.'</p>' ;
          
}
    }
    
}