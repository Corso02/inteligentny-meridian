<?php
require 'vendor/autoload.php';  // Adjust the path based on your project structure
require("./Database.php");

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;

$broker = '147.232.22.230';
$port = 1883;
$clientId = 'meridian_styriaPlusPes_server';
$userName = 'maker';
$password = 'this.is.mqtt';

global $mqtt;
$mqtt = new MqttClient($broker, $port, $clientId);
$conn_settings = new ConnectionSettings();
$conn_settings->setUsername($userName);
$conn_settings->setPassword($password);

try{
    $mqtt->connect($conn_settings);
    echo "connected";
}
catch (ConnectingToBrokerFailedException $e){
    echo "Nepodarilo sa pripojit\n";
    echo $e->getMessage();
}

?>