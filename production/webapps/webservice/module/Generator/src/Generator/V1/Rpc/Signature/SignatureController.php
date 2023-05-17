<?php
namespace Generator\V1\Rpc\Signature;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;
use Aplikasi\Signature;

class SignatureController extends AbstractActionController
{
    public function indexAction()
    {		
		$form = new Form("signature");
		$form->setAttribute('method', 'post');
        $form->add(array(
            'name' => 'X_ID',
            'attributes' => array(
                'type'  => 'text',
            ),
			'options' => array(
                'label' => 'X ID',
            ),
        ));       
        $form->add(array(
            'name' => 'X_PASS',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'X PASS',
            ),
        ));
        $form->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Generate',
                'id' => 'submitbutton',
            ),
        ));
		
		$request = $this->getRequest();
		$result = null;
		if ($request->isPost()) {            
            $form->setData($request->getPost());			
            if ($form->isValid()) {
				$data = $form->getData();
				$sign = new Signature(null, null, null);
				$timestamp = $sign->getTimestamp();
				$sig = $sign->generateSign($data, $timestamp);
				$result = array(
					"timestamp" => $timestamp,
					"signature" => $sig
				);
                // Redirect to list of albums
                //return $this->redirect()->toRoute('generator.rpc.signature');
            }
        }
        return new ViewModel(array('name' => 'signature', 'form' => $form, 'result' => $result));
    }
}
