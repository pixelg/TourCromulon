<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/15/17
 * Time: 1:31 PM
 */

namespace OTE\Models;


class ReservationProductDAO extends DAOCore
{
  /**
   * @return ReservationProduct[]|null
   */
  public static function buildPendingTourReservations()
  {
    $pendingTourReservations = null;

    try{
      $pdo = new \PDO("mysql:host=localhost;dbname=" . \Settings::DB, \Settings::DB_USER, \Settings::DB_PWD);
      $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $statement = $pdo->prepare("
        SELECT
          ReservationProduct.product_id,
          ReservationProduct.depart_date,
          Product.name,
          Product.expire_time,
          Timezone.utc_offset
        FROM reservation_products ReservationProduct
          JOIN reservations Reservation ON ReservationProduct.reservation_id = Reservation.id
          JOIN products Product ON ReservationProduct.product_id = Product.id
          JOIN properties Property ON Product.property_id = Property.id
          JOIN time_zones Timezone ON Property.time_zone_id = Timezone.id
        WHERE Reservation.booking_type = 'TOUR'
        AND Reservation.status = 2
        GROUP BY
          ReservationProduct.product_id,
          ReservationProduct.depart_date,
          Product.name
      ");

      $statement->execute();
      $statement->setFetchMode(\PDO::FETCH_ASSOC);

      while($row = $statement->fetch()){
        $reservationProduct = new ReservationProduct();
        $reservationProduct->setProductId($row['product_id']);
        $reservationProduct->setDepartDate($row['depart_date']);
        $reservationProduct->setName($row['name']);
        $reservationProduct->setExpireTime($row['expire_time']);
        $reservationProduct->setUtcOffSet($row['utc_offset']);
        $pendingTourReservations[] = $reservationProduct;
      }

      $statement->closeCursor();

    } catch (\PDOException $e){
      $e->getMessage();
    }

    return $pendingTourReservations;
  }
}