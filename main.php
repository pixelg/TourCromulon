<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/12/17
 * Time: 3:04 PM
 */

require 'vendor/autoload.php';
require 'settings.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$apiConnector = new OTE\Network\APIConnector(Settings::API_HOST, Settings::API_USER, Settings::API_PWD);

$minClientsLog = new Logger('MinClientsActivator');
$minClientsLog->pushHandler(new StreamHandler('logs/min-clients.log', Logger::DEBUG));
$activator = new OTE\Utilities\MinClientsActivator($apiConnector, $minClientsLog);
$activator->checkMinClients();

$tourExpirationLog = new Logger('TourExpiration');
$tourExpirationLog->pushHandler(new StreamHandler('logs/tour-expiration.log', Logger::DEBUG));
$tourExpiration = new OTE\Utilities\TourExpiration($apiConnector, $tourExpirationLog);
$tourExpiration->checkTourExpiration();