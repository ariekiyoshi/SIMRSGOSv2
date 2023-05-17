<?php
namespace General\V1\Rpc\Photopasien;

use Zend\Mvc\Controller\AbstractActionController;

class PhotopasienController extends AbstractActionController
{
    public function photopasienAction()
    {
		$request = $this->getRequest();
		$norm = $request->getQuery('NORM') ? $request->getQuery('NORM') : 0;		
		$jk = $request->getQuery('JENIS_KELAMIN') ? ($request->getQuery('JENIS_KELAMIN') == 1 ? 'male' : 'female') : 'male';
		$path = realpath('.').'/photos/'.$norm.'.jpg';
		if(file_exists($path)) {
			$photo = file_get_contents($path);
		} else {
			$path = realpath('.').'/photos/'.$jk.'.jpg';
			$photo = file_get_contents($path);
		}
		
		$this->response->setContent($photo);
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'image/jpg')
			->addHeaderLine('Content-Length', strlen($photo));
        
		return  $this->response;
    }
}
