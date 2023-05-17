<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Uri\UriFactory;

class Module
{
    const VERSION = '3.0.3-dev';

	public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_FINISH, [$this, 'onFinish']);
		UriFactory::registerScheme('chrome-extension', 'Zend\Uri\Uri');
    }
	
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function onFinish(MvcEvent $e) {
        $response = $e->getResponse();
        $headers = $response->getHeaders();
        $contentType = $headers->get('Content-Type');
        if($contentType) {
            if (strpos($contentType->getFieldValue(), 'application/json') !== false) {
                $content = json_decode($response->getContent());
                if(isset($content->status)) {
                    if($content->status == 404) {
                        if(isset($content->reason)) unset($content->reason);
                        if(isset($content->message)) unset($content->message);
                        if(isset($content->display_exceptions)) unset($content->display_exceptions);
                        if(isset($content->controller)) unset($content->controller);
                        if(isset($content->controller_class)) unset($content->controller_class);                        
                        $content = json_encode($content);
                        $response->setContent($content);
                    }
                }
            }
        }
    }
}
