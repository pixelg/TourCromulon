<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/14/17
 * Time: 6:33 PM
 */

namespace OTE\Models;

class ProductOccupancyDAO extends DAOCore
{
  public static function getDateRangeForPendingTourReservations()
  {
    $results = null;

    try{
      $pdo = new \PDO("mysql:host=localhost;dbname=" . \Settings::DB, \Settings::DB_USER, \Settings::DB_PWD);
      $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

      $statement = $pdo->prepare("
        SELECT
          MIN(ReservationProduct.depart_date) AS min_date,
          MAX(ReservationProduct.depart_date) AS max_date
        FROM reservation_products ReservationProduct
          JOIN reservations Reservation ON ReservationProduct.reservation_id = Reservation.id
        WHERE Reservation.booking_type = 'TOUR'
        AND Reservation.status = 2;
      ");

      $statement->execute();
      $statement->setFetchMode(\PDO::FETCH_ASSOC);

      while($row = $statement->fetch()){
        $results['min_date'] = $row['min_date'];
        $results['max_date'] = $row['max_date'];
      }

      $statement->closeCursor();
    } catch (\PDOException $e){
      $e->getMessage();
    }

    return $results;
  }

  /**
   * @param $productId
   * @param $departDate
   * @return ProductOccupancy|null
   */
  public static function buildProductOccupancy($productId, $departDate)
  {
    $productOccupancy = null;

    try{
      $pdo = new \PDO("mysql:host=localhost;dbname=" . \Settings::DB, \Settings::DB_USER, \Settings::DB_PWD);
      $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

      $statement = $pdo->prepare("
        SELECT 
          ProductOccupancy.date AS departDate,
          ProductOccupancy.product_id AS productId,
          SUM(ProductOccupancy.quantity) AS totalOccupied,
          Product.min_clients AS minClients
        FROM product_occupancies ProductOccupancy
          JOIN products Product ON Product.id = ProductOccupancy.product_id
        WHERE ProductOccupancy.status IN (1,2)
          AND ProductOccupancy.product_id = $productId
          AND ProductOccupancy.date = '$departDate'
          AND Product.min_clients > 0
      ");

      $statement->execute();
      $statement->setFetchMode(\PDO::FETCH_ASSOC);

      while($row = $statement->fetch()){
        $productOccupancy = new ProductOccupancy($row['productId'], $row['departDate'], $row['totalOccupied'], $row['minClients']);
      }

      $statement->closeCursor();
    } catch (\PDOException $e){
      $e->getMessage();
    }

    return $productOccupancy;
  }
}