<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


// require_once './vendor/autoload.php'; 

// require_once  __DIR__ . './vendor/autoload.php';
require_once  'D:\xampp\htdocs\php-login-minimal-master\vendor\autoload.php';
 

// date_default_timezone_set('Europe\Kiev');

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo $conn->resourceId .'client attached';
    }

    function onMessage(ConnectionInterface $from, $msg)
    {$numRecv=count($this->clients) -1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s'."\n",
        $from->resourceId, $msg, $numRecv, $numRecv==1?'':'s');

        foreach ($this->clients as $client){
            $client->send($msg);
        }
    }
    
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo 'Connection {$conn->resourceId} has disconnected\n';
    }
  
    public function onError(ConnectionInterface $conn, \Exception $e){
        echo "An error has occured: {$e->getMessage()}\n";
        $conn->close();
    }}

use Ratchet\Server\IoServer;


$server = IoServer::factory(
    new Chat(),
    3000
);

$server->run();
