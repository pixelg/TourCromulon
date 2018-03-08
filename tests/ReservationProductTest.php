<?php

/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/18/17
 * Time: 11:45 AM
 */
class ReservationProductTest extends PHPUnit_Framework_TestCase
{
  public function testGetExpireTimeUTC()
  {
    $reservationProduct = new \OTE\Models\ReservationProduct();
    $reservationProduct->setDepartDate('2017-02-16');
    $reservationProduct->setExpireTime('19:00');
    $reservationProduct->setUtcOffSet('-5:00');

    $actual = $reservationProduct->getExpireTimeUTC();
    $this->assertEquals("00:00", $actual->format('H:i'));

    $reservationProduct->setUtcOffSet('+6:00');
    $actual = $reservationProduct->getExpireTimeUTC();
    $this->assertEquals("13:00", $actual->format('H:i'));
  }

  public function testIsExpired()
  {
    $reservationProduct = new \OTE\Models\ReservationProduct();
    $reservationProduct->setExpireTime(null);
    $actual = $reservationProduct->isExpired();
    $this->assertFalse($actual);

    $reservationProduct->setDepartDate('2017-02-16');
    $reservationProduct->setExpireTime('13:00');
    $reservationProduct->setUtcOffSet('-5:00');
    $actual = $reservationProduct->isExpired(new DateTime('2017-02-17 01:00', new DateTimeZone('UTC')));
    $this->assertTrue($actual);

    $reservationProduct->setExpireTime('19:00');
    $reservationProduct->setUtcOffSet('-1:00');
    $actual = $reservationProduct->isExpired(new DateTime('2017-02-16 18:30', new DateTimeZone('UTC')));
    $this->assertFalse($actual);
  }
}
