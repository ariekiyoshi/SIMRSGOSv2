<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace DBService;

use Zend\Mvc\MvcEvent;

use DBService\DatabaseService;
use DBService\generator\Generator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $config = $this->getConfig();
        
		DatabaseService::initialize($sm);
		
		$dbs = DatabaseService::get("SIMpel");
		$adapter = $dbs->getAdapter();
		
		Generator::initialize($adapter);
    }
    
    /**
     * Retrieve autoloader configuration
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array('Zend\Loader\StandardAutoloader' => array('namespaces' => array(
            __NAMESPACE__ => __DIR__ . '/src/',
        )));
    }

    /**
     * Retrieve module configuration
     *
     * @return arrays
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
