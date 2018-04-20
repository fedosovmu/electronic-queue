<?php
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ .'/../vendor/autoload.php';

use Workerman\Worker;
use app\models\Ticket;

// массив для связи соединения пользователя и необходимого нам параметра
$clients = [];
$lastdata = "";

function abc(){
    return 3;
}


// создаём ws-сервер, к которому будут подключаться все наши пользователи
$client_worker = new Worker("websocket://0.0.0.0:8008");

$client_worker->onWorkerStart = function() use (&$clients, &$lastdata)
{
    // создаём локальный tcp-сервер, чтобы отправлять на него сообщения из кода нашего сайта
    $inner_tcp_worker = new Worker("tcp://127.0.0.1:1234");
    // создаём обработчик сообщений, который будет срабатывать,
    // когда на локальный tcp-сокет приходит сообщение
    $inner_tcp_worker->onMessage = function($connection, $data) use (&$clients, &$lastdata)  {
        $lastdata = $data;
        foreach ($clients as $client){
            $webconnection = $client;
            $webconnection->send($data);
        }
    };
    $inner_tcp_worker->listen();
};

$client_worker->onConnect = function($connection) use (&$clients, &$client_worker, &$lastdata)
{
    $connection->onWebSocketConnect = function($connection) use (&$clients, &$client_worker)
    {
        // при подключении нового пользователя сохраняем get-параметр, который же сами и передали со страницы сайта
        $clients[] = $connection;

//        $tickets = json_encode(Ticket::workingTicketsWindow());
//        $connection->send($tickets);
        // вместо get-параметра можно также использовать параметр из cookie, например $_COOKIE['PHPSESSID']
    };
    $connection->send($lastdata);

};

$ws_worker->onClose = function($connection) use(&$clients)
{
    // удаляем параметр при отключении пользователя
    $client = array_search($connection, $clients);
    unset($clients[$client]);
};

$ws_worker->onMessage = function ($connection) use(&$clients)
{

};


// Run worker
Worker::runAll();