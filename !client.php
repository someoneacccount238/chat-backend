<?php $session=1 ?>
 jQuery(function($) {
// Websocket
var websocket_server = new WebSocket("ws://localhost:8080/");
websocket_server.onopen = function(e) {
websocket_server.send(
JSON.stringify({
'type': 'socket',
'user_id': <?= $session ?>
})
);
};
websocket_server.onmessage = function(e) {
//в начале выполняется функция на сервере
var json = JSON.parse(e.data);
switch (json.type) {
case 'chat':
//получаем результат
$('#chat_output').append(json.msg);
break;
}
}
function date_now() {
var date = new Date();
return date.getTime();
}
// Events
$('#btn').on('click', function(e) {
var chat_msg = $('#chat_input').val();
var f = $('#hid').html();
var k = $('#btn_user').html();
websocket_server.send(
JSON.stringify({
'type': 'chat',
'user_id': <?= $session ?>,
'chat_msg': chat_msg + '±' + f + '↔' + k
})
);
$('#chat_input').val('');
});
//gives an array of users
//then, when the user is chosen reloads page to get to the page with user's
chat
$('#btn2').on('click', function(e) {
// Create a temporary <div> to load into
    var arr = <?php echo json_encode($_SESSION['emails']); ?>;
    for (let i = 0; i < arr.length; i++) {
        var div=document.createElement('div');
        div.setAttribute('class', 'buttn' );
        div.innerHTML=document.getElementById('blockOfStuff2').innerHTML;
        // You could optionally even do a little bit of string templating
        div.innerHTML=div.innerHTML.replace(/{VENDOR2}/g, arr[i][0])
        var btn=document.createElement('button');
        var link=document.createElement('a');
        btn.id=arr[i][0];
        btn.style.width='150px' ;
        btn.onclick=function(event) {
        document.getElementById('btn_user').innerHTML=this.id;
        var ff=$('#btn_user').html();
        var win=window.open('chatroom.php?hist=' + ff, ' _self')
        }
        link.appendChild(btn);
        btn.appendChild(div);
        // Write the <div> to the HTML container
        document.getElementById('targetElement2').appendChild(btn);
        var number = document.createElement('button');
        btn.appendChild(number);
        var users = <?php echo json_encode($userArray); ?>;
        let hhh = $('#hid').html() + ''
        let test = {};
        ;
        }
        });
        });