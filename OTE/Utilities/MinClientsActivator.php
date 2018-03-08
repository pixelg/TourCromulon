<?php
/**
 * Created by PhpStorm.
 * User: blee
 * Date: 1/12/17
 * Time: 3:00 PM
 */

namespace OTE\Utilities;

use OTE\Network\APIConnector;
use OTE\Models\Reservation;
use OTE\Models\ProductOccupancyDAO;
use OTE\Models\ReservationDAO;
use OTE\Models\ReservationProductDAO;
use Monolog\Logger;

class MinClientsActivator
{
  private $_text;
  private $_apiConnector;
  private $_logger;

  public function __construct(APIConnector $apiConnector, Logger $logger)
  {
    $this->_apiConnector = $apiConnector;
    $this->_logger = $logger;
  }

  public function checkMinClients()
  {
    $pendingTourReservations = ReservationProductDAO::buildPendingTourReservations();

    if (is_null($pendingTourReservations)){
      return;
    }

    foreach($pendingTourReservations as $pendingTourReservation){
      $productOccupancy = ProductOccupancyDAO::buildProductOccupancy($pendingTourReservation->getProductId(), $pendingTourReservation->getDepartDate());

      if ($productOccupancy->meetsMinClientRequirement()){
        $this->_logger->addInfo("Pending tour found that meets min clients.", [
          'tour' => $pendingTourReservation->toString(),
          'occupancy' => $productOccupancy->toString()
        ]);

        $tourReservations = ReservationDAO::buildTourReservations($pendingTourReservation->getProductId(), $pendingTourReservation->getDepartDate());
        $this->confirmReservations($tourReservations);
      }
    }
  }

  /**
   * @param Reservation[] $tourReservations
   */
  public function confirmReservations($tourReservations)
  {
    foreach($tourReservations as $tourReservation){
      $this->_logger->addInfo("Confirming tour reservation.", [
        'reservation' => $tourReservation->toString()
      ]);

      $sendEmailResponse = $this->_apiConnector->getMethod("/reservations/send_receipt_email/{$tourReservation->getRefNo()}");

      $this->_logger->addInfo("Confirmation e-mail API response.", [
        'email-response' => $sendEmailResponse
      ]);

      $confirmReservationResponse = $this->_apiConnector->getMethod("/reservations/confirm_product_reservation/{$tourReservation->getId()}.json");

      $this->_logger->addInfo("Confirm reservation API response.", [
        'confirm-response' => $confirmReservationResponse
      ]);
    }
  }

  public function getText()
  {
    return $this->_text;
  }
}