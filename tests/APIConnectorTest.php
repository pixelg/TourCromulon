<?php

//require dirname(__FILE__, 2) . 'settings.php';

class APIConnectorTest extends PHPUnit_Framework_TestCase
{
  public function testGetMethod()
  {
    $apiConnector = new OTE\Network\APIConnector(Settings::API_HOST, Settings::API_USER, Settings::API_PWD);
    $actual = $apiConnector->getMethod('/users/get_properties.json');
    $this->assertNotEmpty($actual);
    echo $actual;
  }
}