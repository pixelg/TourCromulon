<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/12/17
 * Time: 4:09 PM
 */

namespace OTE\Models;


class Reservation
{
  private $id;
  private $refNo;
  private $departureDate;
  private $reservationProducts = [];

  public function __construct(){}

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getDepartureDate()
  {
    return $this->departureDate;
  }

  /**
   * @param mixed $departureDate
   */
  public function setDepartureDate($departureDate)
  {
    $this->departureDate = $departureDate;
  }

  /**
   * @return mixed
   */
  public function getRefNo()
  {
    return $this->refNo;
  }

  /**
   * @param mixed $refNo
   */
  public function setRefNo($refNo)
  {
    $this->refNo = $refNo;
  }

  /**
   * @return array
   */
  public function getReservationProducts()
  {
    return $this->reservationProducts;
  }

  /**
   * @param array $reservationProducts
   */
  public function setReservationProducts($reservationProducts)
  {
    $this->reservationProducts = $reservationProducts;
  }

  public function toString()
  {
    return 'Id: ' . $this->id . ' - ' . 'RefNo: ' . $this->refNo;
  }
}