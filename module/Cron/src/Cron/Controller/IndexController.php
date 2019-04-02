<?php

namespace Cron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
  /*
  *
  * /Applications/MAMP/bin/php/php7.0.32/bin/php /Users/michaelgerardgalon/Sites/hackathons/dish2019.gigamike.net/public_html/index.php cron-test
  * /usr/bin/php /var/www/dish2019.gigamike.net/public_html/index.php cron-test
  */
  public function indexAction()
  {
    //$command = 'cleos wallet list';

    /*
    $command = 'cleos wallet unlock --password PW5Jupi9gNMDifTEicvcXPRJWNm41GqaBxRHCBZWBA3y7r9fsK2AK';
    exec($command, $output);
    print_r($output);
    $command = 'cleos wallet create_key';
    exec($command, $output);
    print_r($output);
    */

    $serviceEos = $this->getServiceLocator()->get('ServiceEos');
    $result = $serviceEos->walletUnlock();
    $result = $serviceEos->walletCreateKey();
    print_r($result);
	}
}
