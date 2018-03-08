<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/15/17
 * Time: 1:31 PM
 */

namespace OTE\Models;


class ReservationDAO extends DAOCore
{
  /**
   * @param $productId
   * @param $departDate
   * @return Reservation[]|null
   */
  public static function buildTourReservations($productId, $departDate)
  {
    $pendingTourReservations = null;

    try{
      $pdo = new \PDO("mysql:host=localhost;dbname=" . \Settings::DB, \Settings::DB_USER, \Settings::DB_PWD);
      $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $statement = $pdo->prepare("
        SELECT 
          Reservation.id, 
          Reservation.ref_no
        FROM reservation_products ReservationProduct
          JOIN reservations Reservation ON ReservationProduct.reservation_id = Reservation.id
        WHERE Reservation.booking_type = 'TOUR'
          AND Reservation.status = 2
          AND ReservationProduct.product_id = $productId
          AND ReservationProduct.depart_date = '$departDate'
      ");

      $statement->execute();
      $statement->setFetchMode(\PDO::FETCH_ASSOC);

      while($row = $statement->fetch()){
        $reservation = new Reservation();
        $reservation->setId($row['id']);
        $reservation->setRefNo($row['ref_no']);
        $pendingTourReservations[] = $reservation;
      }

      $statement->closeCursor();

    } catch (\PDOException $e){
      $e->getMessage();
    }

    return $pendingTourReservations;
  }
}