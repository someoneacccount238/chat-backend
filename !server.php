<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require_once 'vendor\autoload.php';

date_default_timezone_set('Europe\Kiev');

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
        echo '{$conn->resourceId} client attached';
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo 'Connection {$conn->resourceId} has disconnected\n';
    }

    function onMessage(ConnectionInterface $from, $data)
    {
        $dat = date('Y-m-d H:i:s');
        $from_id = $from->resourceId;
        $data = json_decode($data);
        $type = $data->type;

        switch ($type) {
            case 'chat':
                $user_id = $data->user_id;
                $chat_msg = $data->chat_msg;

                $arr = explode('+', $chat_msg);
                $arr2 = explode('=', $arr[1]);

                $ddd = $arr[0];
                $sender = $arr2[0];
                $recep = $arr2[1];

                //записать сообщение в историю
                $f = file_get_contents('notifs.json');
                $item = json_decode($f, true);

                for ($i = 0; $i < count($item); $i++) {
                    if ($item[$i]['email'] == $sender) {
                        $item[$i]['last_time_opened'] = $dat;
                    }
                }

                for ($i = 0; $i < count($item); $i++) {
                    $arr = [$ddd, $sender, $recep, $dat];
                    //зависываем сообщение в запись, связанную с этим email
                    if ($item[$i]['email'] == $sender) {
                        array_push($item[$i], $arr);
                    }
                }

                $jsonData = json_encode($item, JSON_FORCE_OBJECT);
                file_put_contents('notifs.json', $jsonData);

                $response_to = "
    <div class=\"msg left-msg\">
        <div class=\"msg-img\" style=\"background-image: url(https://image.flaticon/icons/svg/327/327779.svg)\">

        </div>
        <div class=\"msg-bubble\">
            <div class=\"msg-info\">
                <div class=\"msg-info-name1\" style=\"color:blue\">
                    <h5>" . '
                        <pre> ' . $sender . '</pre>' . "
                    </h5>
                </div>
                <div class=\"msg-info-time\">$dat</div>
            </div>

            <div class=\"msg-text\">\"$ddd\"</div>
        </div>
    </div>";

                $response_from = "
     <div class=\"msg right-msg\"
        <div class=\"msg-img\" style=\"background-image: url(https://image.flaticon/icons/svg/145/145867.svg)\">

    </div>
    <div class=\"msg-bubble\">
        <div class=\"msg-info\">
            <div class=\"msg-info-name2\" style=\"color:blue\">
                <h5>" . '
                    <pre> ' . $sender . '</pre>' . "
                </h5>
            </div>
            <div class=\"msg-info-time\">\ $dat</div>
        </div>

        <div class=\"msg-text\">\$ddd</div>
    </div>
    </div>";

                $response_to2 = "<b>" . $user_id . "</b>:<br><br>";
                $response_from2 = "<b>" . $user_id . "</b>:<br><br>";

                $from->send(json_encode([
                    "type" => $type,
                    "msg" => $response_from,
                    "userid" => $response_from2,
                    "sender" => $from_id
                ]));

                // send to all clients
                foreach ($this->clients as $client) {
                    if ($from != $client) {
                        $client->send(json_encode([
                            "type" => $type,
                            "msg" =>   $response_to,
                            "userid" => $response_to2,
                            "recepient" => $from_id
                        ]));
                    }
                }
                break;
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e){
        echo "An error has occured: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = new \Ratchet\App('localhost',8080);
$server->route('/', new Chat,['*']);
$server->run();



























 