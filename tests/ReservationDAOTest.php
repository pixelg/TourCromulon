<?php

/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/15/17
 * Time: 1:47 PM
 */
class ReservationDAOTest extends PHPUnit_Framework_TestCase
{

  public function testBuildReservation()
  {
    $actual = OTE\Models\ReservationDAO::buildTourReservations(null, null);
    var_dump($actual);
  }
}
