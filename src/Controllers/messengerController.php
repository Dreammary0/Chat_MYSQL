<?php

namespace Controllers;
use Twig\Environment;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

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
    
function show_messages(){ 
     $content = json_decode(file_get_contents("mes.json"));
        foreach($content->messages as $message){
            echo "<p>";
            echo "$message->date      $message->login:     <b>$message->message</b>";
            echo "</p>";        }
    }
    
function add_message_to_file($message, $log){
 $content = json_decode(file_get_contents("mes.json"));
        $message_object = (object) [
            'date' => date('d.m.Y H:i'),
            'login' => $log,
            'message' => $message];
        $content->messages[] = $message_object;
        file_put_contents("mes.json", json_encode($content));  
        $this->log->pushHandler($this->messengerHandler);
        $this->log->info('New message', ['user' => $log, 'send' => $message]);

}
    
}
