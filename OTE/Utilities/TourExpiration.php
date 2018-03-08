<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/18/17
 * Time: 10:45 AM
 */

namespace OTE\Utilities;

use Monolog\Logger;
use OTE\Models\ReservationDAO;
use OTE\Models\ReservationProductDAO;
use OTE\Network\APIConnector;
use OTE\Models\Reservation;

class TourExpiration
{
  private $_apiConnector;
  private $_logger;

  public function __construct(APIConnector $apiConnector, Logger $logger)
  {
    $this->_apiConnector = $apiConnector;
    $this->_logger = $logger;
  }

  public function checkTourExpiration()
  {
    $pendingTourReservations = ReservationProductDAO::buildPendingTourReservations();

    if (empty($pendingTourReservations)){
      return;
    }

    foreach($pendingTourReservations as $pendingTourReservation){
      if ($pendingTourReservation->isExpired()){
        $this->_logger->addInfo("Pending tour found that has expired.", [
          'tour' => $pendingTourReservation->toString(),
          'expire_time' => $pendingTourReservation->getExpireTime(),
          'expire_time_utc' => $pendingTourReservation->getExpireTimeUTC(),
          'utc_offset' => $pendingTourReservation->getUtcOffSet()
        ]);

        $tourReservations = ReservationDAO::buildTourReservations($pendingTourReservation->getProductId(), $pendingTourReservation->getDepartDate());
        $this->cancelReservations($tourReservations);
      }
    }

  }

  /**
   * @param Reservation[] $tourReservations
   */
  public function cancelReservations($tourReservations)
  {
    foreach($tourReservations as $tourReservation){
      $this->_logger->addInfo("Cancelling tour reservation.", [
        'reservation' => $tourReservation->toString()
      ]);

      $sendEmailResponse = $this->_apiConnector->getMethod("/reservations/send_cancel_expire_tour_email/{$tourReservation->getRefNo()}");

      $this->_logger->addInfo("Cancellation e-mail API response.", [
        'email-response' => $sendEmailResponse
      ]);

      $cancelReservationResponse = $this->_apiConnector->getMethod("/reservations/cancel_pending/{$tourReservation->getId()}.json");

      $this->_logger->addInfo("Cancel reservation API response.", [
        'cancel-response' => $cancelReservationResponse
      ]);
    }
  }
}