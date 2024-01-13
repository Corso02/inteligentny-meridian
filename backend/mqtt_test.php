<?php
require 'vendor/autoload.php';  // Adjust the path based on your project structure
require("./Database.php");

use PhpMqtt\Client\MqttClient;

$broker = 'broker.hivemq.com';
$port = 1883;
$clientId = 'meridian_styriaPlusPes_server';

global $mqtt;
$mqtt = new MqttClient($broker, $port, $clientId);

$mqtt->connect();

echo "connected";

$topic = 'kpi/iot/meridian/styriaPlusPes/server';
$mqtt->subscribe($topic, function ($topic, $message) {
    // Handle incoming MQTT message
    // You can execute a PHP script based on the message content
    $message = json_decode($message);
    if ($message->command === 'handle_card') {
        handle_card($message->card_id);
    }
});


function handle_card($card_id){
    global $mqtt;
    $db = new Database("./database.db");
    $res = $db->get_preferences($card_id);
    $topic = "kpi/iot/meridian/styriaPlusPes/prefs";
    $msg = json_encode($res);
    $mqtt->publish($topic, $msg, 0, false);
    $db->close();
}

$mqtt->loop(true);

$mqtt->disconnect();