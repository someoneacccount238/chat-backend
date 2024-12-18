<?php
error_reporting(E_ERROR | E_PARSE);

class Message
{
    private $id;

    private $msg;


    private $avatar;

    private $createdAt;

    private $from;

    private $to;

    public function __construct($l, $m, $a, $b, $c, $d)
    {
        $this->avatar = $l;
        $this->id = $m;
        $this->msg = $a;
        $this->createdAt = $b;
        $this->from = $c;
        $this->to = $d;

        $this->toJson();
    }

    private function toJson()
    {
        $response = ([
            'id' => $this->id,
            'avatar' => $this->avatar,
            'msg' =>  $this->msg,
            'createdAt' => $this->createdAt,
            'from' => $this->from,
            'to' => $this->to
        ]);

        // $response2 = ([
        //     'id' => $this->id,
        //     'avatar' => $this->avatar,
        //     'msg' =>  $this->msg,
        //     'createdAt' => $this->createdAt,
        //     'from' => $this->to,
        //     'to' => $this->from
        // ]);


        $file = file_get_contents('../assets/messages.json', true);
        $file_json_format = json_decode($file, true);

        array_push($file_json_format, $response);
        // array_push($file_json_format, $response2);

        print_r($file_json_format);

        file_put_contents('../assets/messages.json', json_encode($file_json_format));
    }

    public static function fromJson($sender, $recepient)
    {

        $file = file_get_contents('../assets/messages.json', true);

        $file_json_format = json_decode($file, true);

        $arrWithSenderAndRecepient = [];

        for ($i = 0; $i <= count($file_json_format); $i++) {
            // print_r($file_json_format[$i]['from'] . ' ');
            if ($file_json_format[$i]['from'] == $sender && $file_json_format[$i]['to'] == $recepient ||
             $file_json_format[$i]['to'] == $sender && $file_json_format[$i]['from'] == $recepient)
                array_push($arrWithSenderAndRecepient, $file_json_format[$i]);

 
        }

        // print_r($arrWithSenderAndRecepient);

        return json_encode($arrWithSenderAndRecepient);
    }
}
