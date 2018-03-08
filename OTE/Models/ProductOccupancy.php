<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/15/17
 * Time: 12:26 PM
 */

namespace OTE\Models;


class ProductOccupancy
{
  private $productId;
  private $departDate;
  private $totalOccupied;
  private $minClients;

  public function __construct($productId, $departDate, $totalOccupied, $minClients)
  {
    $this->departDate = $departDate;
    $this->productId = $productId;
    $this->totalOccupied = $totalOccupied;
    $this->minClients = $minClients;
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
  public function getTotalOccupied()
  {
    return $this->totalOccupied;
  }

  /**
   * @param mixed $totalOccupied
   */
  public function setTotalOccupied($totalOccupied)
  {
    $this->totalOccupied = $totalOccupied;
  }

  /**
   * @return mixed
   */
  public function getMinClients()
  {
    return $this->minClients;
  }

  /**
   * @param mixed $minClients
   */
  public function setMinClients($minClients)
  {
    $this->minClients = $minClients;
  }

  public function meetsMinClientRequirement()
  {
    return (bool)($this->totalOccupied >= $this->minClients);
  }

  public function toString()
  {
    return 'total-occupied: ' . $this->totalOccupied . ' - ' . 'min-clients: ' . $this->minClients;
  }
}