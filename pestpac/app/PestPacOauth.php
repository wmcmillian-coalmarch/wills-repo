<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 6/20/18
 * Time: 4:53 PM
 */

namespace PestPac;

class PestPacOauth{

  protected $grantType;
  protected $wwidUser;
  protected $wwidPass;
  protected $clientId;
  protected $secret;
  protected $cookie_name='pp_token';

  public function __construct($wwidUser,$wwidPass,$clientId,$secret,$grantType){
    $this->wwidUser = $wwidUser;
    $this->wwidPass = $wwidPass;
    $this->clientId = $clientId;
    $this->secret   = $secret;
    $this->grantType = $grantType;
  }

  function encodeToken(){
    $clientId = $this->clientId;
    $secret = $this->secret;
    return base64_encode("$clientId:$secret");
  }

  function getGrantType(){
    return $this->grantType;
  }

  function getData(){
    return
      $data = array(
      'grant_type' => $this->getGrantType(),
      'username' => $this->wwidUser,
      'password' => $this->wwidPass
    );
  }

  function authenticate($url){

    $headers = array(
      'Content-Type' =>  'application/x-www-form-urlencoded; charset=utf-8',
      'Authorization' => 'Bearer ' . $this->encodeToken()
    );
    $pp = new PestPac();

    $response = $pp->curl($url,$this->getData(),$headers,'post');
    if($response['code'] === 200){
      $jsonOutput = json_decode($response['response'],true);
      $token = $jsonOutput["access_token"];
      setcookie($this->cookie_name, $token, time() + (86400 * 30), "/");
      return true; //authenticated
    }

    return false;
  }


}