<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/16/17
 * Time: 2:28 PM
 */

namespace OTE\Models;


class ReservationProduct
{
  private $productId;
  private $departDate;
  private $name;
  private $expireTime;
  private $utcOffSet;

  /**
   * @return mixed
   */
  public function getProductId()
  {
    return $this->productId;
  }

  /**
   * @param mixed $productId
   */
  public function setProductId($productId)
  {
    $this->productId = $productId;
  }

  /**
   * @return mixed
   */
  public function getDepartDate()
  {
    return $this->departDate;
  }

  /**
   * @param mixed $departDate
   */
  public function setDepartDate($departDate)
  {
    $this->departDate = $departDate;
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  public function toString()
  {
    return $this->name . ' - ' . $this->departDate . ' - ' . $this->productId;
  }

  /**
   * @return mixed
   */
  public function getExpireTime()
  {
    return $this->expireTime;
  }

  /**
   * @param mixed $expireTime
   */
  public function setExpireTime($expireTime)
  {
    $this->expireTime = $expireTime;
  }

  /**
   * @return mixed
   */
  public function getUtcOffSet()
  {
    return $this->utcOffSet;
  }

  /**
   * @param mixed $utcOffSet
   */
  public function setUtcOffSet($utcOffSet)
  {
    $this->utcOffSet = $utcOffSet;
  }

  public function getExpireTimeUTC()
  {
    if (empty($this->expireTime) || empty($this->departDate)){
      return null;
    }

    $expireTimeString = $this->departDate.'T'.$this->expireTime.$this->utcOffSet;
    $expireDateTime = new \DateTime($expireTimeString);
    $utcExpireDateTime = clone $expireDateTime;
    $utcExpireDateTime->setTimezone(new \DateTimeZone('UTC'));

    return $utcExpireDateTime;
  }

  public function isExpired(\DateTime $now = null)
  {
    if (is_null($now)){
      $now = new \DateTime();
    }

    $expireTimeUTC = $this->getExpireTimeUTC();
    $isExpired = !empty($expireTimeUTC) && ($now > $expireTimeUTC);

    return $isExpired;
  }
}