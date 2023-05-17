<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZF\Apigility\Admin\Module as AdminModule;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        if (class_exists('\ZF\Apigility\Admin\Module', false)) {
          return $this->redirect()->toRoute('zf-apigility/ui');
        } else {
			return $this->redirect()->toRoute('zf-apigility/documentation');
		}
        return new ViewModel();
    }
}
