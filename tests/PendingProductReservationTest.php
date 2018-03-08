<?php

require_once dirname(dirname(__FILE__)) . '/settings.php';

class PendingProductReservationTest extends PHPUnit_Framework_TestCase
{
  public function testBuildPendingReservations()
  {
    $actual = OTE\Models\Reservation::buildPendingOccupancies();
    print_r($actual);

//    foreach($actual as $pendingReservation){
//      echo $pendingReservation->getProductId() . "\n";
//    }
  }
}