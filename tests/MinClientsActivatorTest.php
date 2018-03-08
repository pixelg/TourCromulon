<?php

class MinClientsActivatorTest extends PHPUnit_Framework_TestCase
{
  public function testGetText()
  {
    $activator = new OTE\Utilities\MinClientsActivator("Hello");
    $actual = $activator->getText();
    $this->assertEquals("Hello", $actual);
  }
}