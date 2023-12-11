<?php
require_once '../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'brooks_sub', 'babka123');
$channel = $connection->channel();

$channel->exchange_declare('topic_logs', 'topic', false, false, false);

$routing_key = $argv[1];
if(empty($routing_key)) $routing_key = "anonymous.info";
$data = implode(' ', array_slice($argv, 2));
if(empty($data)) $data = "Hello World!";

$msg = new AMQPMessage($data);

$channel->basic_publish($msg, 'topic_logs', $routing_key);

echo " [x] Sent ",$routing_key,':',$data," \n";

$channel->close();
$connection->close();
