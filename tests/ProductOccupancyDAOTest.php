<?php

class ProductOccupancyDAOTest extends PHPUnit_Framework_TestCase
{
  public function testGetMinMaxDatesForPendingTourReservations()
  {
    $actual = OTE\Models\ProductOccupancyDAO::getDateRangeForPendingTourReservations();
    var_dump($actual);
  }

  public function testBuildProductOccupancyDay()
  {
    $minDate = '2017-01-10';
    $maxDate = '2017-01-12';

    $actual = OTE\Models\ProductOccupancyDAO::buildProductOccupancy($minDate, $maxDate);
//    print_r($actual);

    foreach($actual as $productOccupancyDay){
      var_dump($productOccupancyDay->meetsMinClientRequirement());
    }
  }
}