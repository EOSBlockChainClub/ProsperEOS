<?php

namespace Eos\Service;

use Zend\Mvc\Controller\AbstractActionController;

class ServiceEos extends AbstractActionController
{
  public function walletUnlock(){
    $config = $this->getServiceLocator()->get('Config');

    $command = 'cleos wallet unlock --password ' . $config['eos_private_key'];
    exec($command, $output);
    return $output;
  }

  public function walletLock(){
    $command = 'cleos wallet lock';
    exec($command, $output);
    return $output;
  }

  public function walletCreateKey(){
    $command = 'cleos wallet create_key';
    exec($command, $output);
    return $output;
  }

  public function walletList(){
    $command = 'cleos wallet list';
    exec($command, $output);
    return $output;
  }

  public function walletList($privateKey){
    $command = 'cleos wallet import --private-key ' . $privateKey;
    exec($command, $output);
    return $output;
  }

  public function getCreateAccount($account, $publicKey){
    $command = 'cleos create account eosio ' . $account . ' ' . $publicKey;
    exec($command, $output);
    return $output;
  }

  public function getAccount($account){
    $command = 'cleos get account ' . $account;
    exec($command, $output);
    return $output;
  }

  public function pushAction($contractName, $action, $jsonParams, $account){
    $command = 'cleos push action ' . $contractName . ' ' . $action . ' ' . $jsonParams . ' -p ' . $account . '@active';
    exec($command, $output);
    return $output;
  }
}
