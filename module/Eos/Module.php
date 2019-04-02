<?php
namespace Eos;

use Eos\Service\ServiceEos;

class Module
{
    public function getConfig()
    {
      return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
      return array(
        'Zend\Loader\StandardAutoloader' => array(
          'namespaces' => array(
            __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
          ),
        ),
      );
    }

    public function getServiceConfig()
    {
        return array(
          'factories' => array(
            'EosForm' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $form = new EosForm($dbAdapter);
              return $form;
            },
            'ServiceEos' => function ($sm) {
              $service = new ServiceEos();
              return $service;
            },
          ),
        );
    }
}
