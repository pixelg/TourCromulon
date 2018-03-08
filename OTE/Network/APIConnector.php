<?php
/**
 * Created by PhpStorm.
 * User: Brent
 * Date: 8/4/14
 * Time: 5:00 PM
 */

namespace OTE\Network;

class APIConnector
{
  const CONNECT_TIMEOUT = 10;

  private $_curlHandle = null;
  private $_responseCode = 0;
  private $_errorMessage = '';
  private $_user = null;
  private $_pwd = null;
  private $_host = null;

  public function __construct($host, $user = null, $pwd = null)
  {
    $this->_host = $host;
    $this->_user = $user;
    $this->_pwd = $pwd;
  }

  public function getMethod($url)
  {
    $this->initializeCURL();
    $response = $this->executeRequest($url);
    $this->closeCURL();

    return $response;
  }

  public function postMethod($url, $postData = null, $contentType = null)
  {
    $this->initializeCURL();
    curl_setopt($this->_curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
    $response = $this->executeRequest($url, $postData, $contentType);
    $this->closeCURL();

    return $response;
  }

  private function executeRequest($url, $postData = null, $contentType = null)
  {
    // The full URL used for the HTTP request.
    $urlRequest = $this->buildURLRequest($url);
    curl_setopt($this->_curlHandle, CURLOPT_URL, $urlRequest);

    // If a user and password is used then add them here.
    if (!is_null($this->_user) && !is_null($this->_pwd)){
      curl_setopt($this->_curlHandle, CURLOPT_USERPWD, $this->_user . ':' . $this->_pwd);
    }

    // If there is data to post then add it here.
    if (!empty($postData)){
      curl_setopt($this->_curlHandle, CURLOPT_POST, 1);
      curl_setopt($this->_curlHandle, CURLOPT_POSTFIELDS, $postData);

      // Set the HTTP content type headers if a content type was passed.
      if (!empty($contentType)){
        curl_setopt($this->_curlHandle, CURLOPT_HTTPHEADER, array(
            "Content-Type: $contentType",
            'Content-Length: ' . strlen($postData))
        );
      }
    }

    if ($this->isSecureHost()){
//      curl_setopt($this->_curlHandle, CURLOPT_SSLVERSION, 3);
      curl_setopt($this->_curlHandle, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($this->_curlHandle, CURLOPT_SSL_VERIFYHOST, FALSE);
    }

    // General options
    curl_setopt($this->_curlHandle, CURLOPT_VERBOSE, 1);
    curl_setopt($this->_curlHandle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->_curlHandle, CURLOPT_CONNECTTIMEOUT, APIConnector::CONNECT_TIMEOUT);

    // Execute the request.
    $response = curl_exec($this->_curlHandle);
    // Get the HTTP response code.
    $this->_responseCode = curl_getinfo($this->_curlHandle, CURLINFO_HTTP_CODE);

    // If the response is false then get the CURL error.
    if ($response === FALSE){
      $this->_errorMessage = curl_error($this->_curlHandle);
    }

    return $response;
  }

  private function buildURLRequest($url)
  {
    $url = $this->_host . $url;
    return $url;
  }

  private function isSecureHost()
  {
    return preg_match('/^https/', $this->_host);
  }

  private function initializeCURL()
  {
    $this->_curlHandle = curl_init();
  }

  private function closeCURL()
  {
    curl_close($this->_curlHandle);
  }

  public function getResponseCode()
  {
    return $this->_responseCode;
  }

  public function getErrorMessage()
  {
    return $this->_errorMessage;
  }
} 