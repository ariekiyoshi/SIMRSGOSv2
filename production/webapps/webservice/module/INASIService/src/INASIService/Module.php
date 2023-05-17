<?php
namespace INASIService;

use ZF\Apigility\Provider\ApigilityProviderInterface;

class Module implements ApigilityProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'ZF\Apigility\Autoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
	
	public function getServiceConfig() {
		return array(
			'factories' => array(
				'INASIService\Service' => function($sm) {
					$config = $sm->get('Config');
					$config = $config['services']['INASIService'];
					return new \INASIService\Service($config);
				},
			)
		);
	}
}
