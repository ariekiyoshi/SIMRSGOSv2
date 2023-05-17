<?php
namespace Aplikasi\V1\Rpc\Authentication;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Session\AbstractContainer as ConSession;
use Zend\Db\Adapter\AdapterServiceFactory;

use Zend\Json\Json;

use DBService\DatabaseService;
use Zend\Db\Sql\TableIdentifier;
use DBService\System;
use Aplikasi\Password;
use Aplikasi\V1\Rest\PenggunaLog\Service as PenggunaLogService;

use \DateTime;
//use \DateInterval;
use \DateTimeZone;

class AuthenticationController extends AbstractActionController
{
    private $manager;
	private $auth;
	private $penggunaLog;
	
	public function __construct() {
		$this->auth = new AuthenticationService();
		$this->penggunaLog = new PenggunaLogService();
	}

    public function loginAction($params = null)
    {
		$request = $this->getRequest();
		if($request->getMethod() == 'GET') {
			$this->response->setStatusCode(405);
			return array();
		}
		
		$this->manager = ConSession::getDefaultManager();
		//$this->manager->regenerateId(true);
		
		$data = json_decode($request->getContent());
		if(isset($data->INTEGRATION)) {
			$data = base64_decode($data->DATA);
			$data = json_decode($data);
			$data->PASSWORD = base64_decode($data->PASSWORD);		
		}
		
		if(isset($params)) {
			$data = json_decode($params);
		}
		
		$db = DatabaseService::get("SIMpel");
		$adapter = $db->getAdapter();
		$sysdate = System::getSysDate($adapter);
		
		$dt = new DateTime(null, new DateTimeZone('UTC'));
		$ts = $dt->getTimestamp();
		
		if(!$this->auth->hasIdentity()) {									
			$result = $this->doAuthenticate($adapter, $data, Password::TYPE_ENCRYPT_MD5_WITH_KEY);
			if(!$this->auth->hasIdentity()) $result = $this->doAuthenticate($adapter, $data, Password::TYPE_ENCRYPT_MD5_ONLY);
			if(!$this->auth->hasIdentity()) $result = $this->doAuthenticate($adapter, $data, Password::TYPE_ENCRYPT_MYSQL_PASS);
			
			$success = true;
			$message = 'login ';
			$id = $nama = $data = null;
			
			if(!$result->isValid()) {
				foreach($result->getMessages() as $msg) {
					$message .= $msg;
				}
				$success = false;
			} else {				
				if($this->auth->hasIdentity()) {				
					$id = $this->auth->getIdentity();	
					
					$result = $db->get(new TableIdentifier("pengguna", "aplikasi"))->select(array('LOGIN' => $id))->toArray();
					$privilages = array();
					$integrasi = array();
					if(count($result) > 0) {
						//$profile = Master::get('pegawai')->select(array('NIP' => $result[0]['NIP']))->toArray();
						//if(count($profile) > 0) $nama = $profile[0]['NAMA'];
						$nama = $result[0]['NAMA'];
						$login = $result[0]['LOGIN'];
						$id = $result[0]['ID'];
						$jenis = $result[0]['JENIS'];
						$nik = $result[0]['NIK'];
						$privilages = $this->getUserPrivilages($adapter, $id);						
						$propertyConfig = $this->getPropertyConfig($adapter);
						$integrasi = $this->getIntegrasi($adapter);
						$nomor = $this->getTransaksiKasir($adapter, $id);
						$penggunaRuangan = $this->getRuanganPengguna($adapter, $id);
						$privilagesPencarian = $this->getUserPrivilagesPencarian($adapter, $id);
						$uam = $this->getUserAksesModule($adapter, $id);
					}									
					
					$storage = $this->auth->getStorage();										
			
					$data = Json::decode(Json::encode(array('ID' => $id, 'NAME' => $nama, 'LGN' => $login, 'JNS' => $jenis, 'NIK' => $nik, 'TIMESTAMP' => $ts, 'XPRIV' => $privilages, 'XITR'=>$integrasi, 'PC'=>$propertyConfig, 'NO_TRX_KSR' => $nomor, 'RUANGANS' => $penggunaRuangan, 'XPRIVPENCARIAN' => $privilagesPencarian, 'XUAM' => $uam, 'SYSDATE' => $sysdate)));					
					$storage->write($data);
					$data = array('ID' => $id, 'NAME' => $nama, 'LGN' => $login, 'JNS' => $jenis, 'NIK' => $nik, 'TIMESTAMP' => $ts, 'XPRIV' => $privilages, 'XITR' => $integrasi, 'PC'=>$propertyConfig, 'NO_TRX_KSR' => $nomor, 'RUANGANS' => $penggunaRuangan, 'XPRIVPENCARIAN' => $privilagesPencarian, 'XUAM' => $uam, 'SYSDATE' => $sysdate);
					//$idSession = session_id(); or 				
					/*$idSession = base64_encode($manager->getId().'00ff'.$data->TIMESTAMP);
					
					$rst = $authAdapter->getResultRowObject(array('ID'));
					$idUsr = $rst->ID;
					
					$forwardHost = $request->getServer('HTTP_X_FORWARDED_FOR');
					$location = $forwardHost ? $forwardHost : $request->getServer('REMOTE_ADDR');
					$userAgent = $request->getServer('HTTP_USER_AGENT');
					$this->writeLog('login', $idUsr, $idSession, $location, $userAgent);*/
				}
			}
		} else {
			$message = 'logged';
			$success = true;
			$data = $this->auth->getIdentity();
			$uam = isset($data->XUAM) ? $data->XUAM : []; 
			$data = array('ID' => $data->ID, 'NAME' => $data->NAME, 'LGN' => $data->LGN, 'JNS' => $data->JNS, 'NIK' => isset($data->NIK) ? $data->NIK : null, 'TIMESTAMP' => $ts, 'XPRIV' => $data->XPRIV, 'XITR' => $data->XITR, 'PC'=>$data->PC, 'NO_TRX_KSR' => $data->NO_TRX_KSR, 'RUANGANS' => $data->RUANGANS, 'XUAM' => $uam, 'SYSDATE' => $sysdate);
		}
		
		if($success) {
			$forwardHost = $request->getServer('HTTP_X_FORWARDED_FOR');
			$location = $forwardHost ? $forwardHost : $request->getServer('REMOTE_ADDR');
			$userAgent = $request->getServer('HTTP_USER_AGENT');
			$this->penggunaLog->simpan(
				array(
					"PENGGUNA" => $data["ID"],
					"TANGGAL_AKSES" => new \Zend\Db\Sql\Expression('NOW()'),
					"LOKASI" => $location,
					"AGENT" => $userAgent
				), true, false
			);
		}
		
		return array(
            'success' => $success,
			'message' => $message,
            'data' => $data
        );
    }
	
	private function doAuthenticate($adapter, $data, $passwordType) {		
	    $authAdapter = new AuthAdapter(
	        $adapter,
	        new TableIdentifier("pengguna", "aplikasi"),
	        'LOGIN',
	        'PASSWORD'
	        //'STATUS = 1'
	        //$passwordType == Password::TYPE_ENCRYPT_MYSQL_PASS ? 'PASSWORD(?) AND STATUS = 1' : 'STATUS = 1'
	        );
	    
	    $pass = Password::encrypt($data->PASSWORD, $passwordType);
	    if(isset($data->ENCRYPTED)) $pass = $data->PASSWORD;
	    $select = $authAdapter->getDbSelect();
	    $authAdapter->setIdentity($data->LOGIN)
	    ->setCredential($pass);
	    $select->where('STATUS = 1');
	    $result = $this->auth->authenticate($authAdapter);
	    return $result;
	}
	
	private function getUserPrivilages($adapter, $pengguna) {
		$stmt = $adapter->query('
			SELECT gpam.MODUL
			  FROM aplikasi.pengguna_akses pa, aplikasi.group_pengguna_akses_module gpam
			 WHERE pa.GROUP_PENGGUNA_AKSES_MODULE = gpam.ID
		       AND pa.STATUS = 1 AND gpam.STATUS = 1
			   AND pa.PENGGUNA = ?');
		$result = $stmt->execute(array($pengguna));
		$data = array();
		foreach($result as $rst) {
			$val = $rst['MODUL'];
			$data[$val] = $val;
		}
		return $data;
	}
	
	private function getUserAksesModule($adapter, $pengguna) {
	    $stmt = $adapter->query('
			SELECT DISTINCT m.ID, m.NAMA, m.LEVEL, m.DESKRIPSI, m.CLASS, m.ICON_CLS, m.HAVE_CHILD
              FROM aplikasi.pengguna_akses pa
              		 , aplikasi.group_pengguna_akses_module gpam
              		 , aplikasi.modules m
             WHERE pa.GROUP_PENGGUNA_AKSES_MODULE = gpam.ID
               AND pa.STATUS = 1 AND gpam.STATUS = 1
               AND m.ID = gpam.MODUL AND m.`STATUS` = 1
               AND pa.PENGGUNA = ?
             ORDER BY m.ID');
	    $result = $stmt->execute(array($pengguna));
	    $data = array();
	    foreach($result as $rst) {
	        $data[] = base64_encode(json_encode($rst));
	    }
	    return base64_encode(json_encode($data));
	}
	
	private function getUserPrivilagesPencarian($adapter, $pengguna) {
		$stmt = $adapter->query('
			SELECT md.*
			  FROM aplikasi.pengguna_akses pa, aplikasi.group_pengguna_akses_module gpam, aplikasi.modules md
			 WHERE pa.GROUP_PENGGUNA_AKSES_MODULE = gpam.ID
		       AND pa.STATUS = 1 AND gpam.STATUS = 1
			   AND pa.PENGGUNA = ? AND gpam.MODUL LIKE "2401010%"
			   AND md.ID = gpam.MODUL');
		$result = $stmt->execute(array($pengguna));
		$data = array();
		foreach($result as $rst) {
			$data[] = $rst;
		}
		return $data;
	}
	
	private function getRuanganPengguna($adapter, $pengguna) {
		$stmt = $adapter->query("
			SELECT * FROM (
				SELECT IF(NOT dr.ID IS NULL, dr.RUANGAN, 
								IF(NOT sr.ID IS NULL, sr.RUANGAN,
										IF(NOT pru.ID IS NULL, pru.RUANGAN, ''))) RUANGAN
				  FROM aplikasi.pengguna p
						 LEFT JOIN master.dokter d ON d.NIP = p.NIP
						 LEFT JOIN master.dokter_ruangan dr ON dr.DOKTER = d.ID AND dr.STATUS = 1
						 LEFT JOIN master.staff s ON s.NIP = p.NIP
						 LEFT JOIN master.staff_ruangan sr ON sr.STAFF = s.ID AND sr.STATUS = 1
						 LEFT JOIN master.perawat pr ON pr.NIP = p.NIP
						 LEFT JOIN master.perawat_ruangan pru ON pru.PERAWAT = pr.ID AND pru.STATUS = 1
				 WHERE p.ID = ?
				) pr, master.ruangan r 
			WHERE pr.RUANGAN = r.ID
			  AND r.STATUS = 1");
		$result = $stmt->execute(array($pengguna));
		$data = array();
		foreach($result as $rst) {
			$data[] = $rst;
		}
		return $data;
	}
	
	private function getPropertyConfig($adapter) {
		$stmt = $adapter->query("
			SELECT ID, VALUE FROM aplikasi.properti_config");
		$result = $stmt->execute();
		$data = array();
		foreach($result as $rst) {
			$data[] = $rst;
		}
		return $data;
	}
	
	private function getIntegrasi($adapter) {
		$stmt = $adapter->query('
			SELECT * FROM aplikasi.integrasi WHERE STATUS = 1');
		$result = $stmt->execute();
		$data = array();
		foreach($result as $rst) {
			$data[] = $rst;
		}
		return $data;
	}
	
	public function isAuthenticateAction() 
	{        
		$request = $this->getRequest();
		
		$message = 'logged';
		$success = true;
		$data = null;
		$data = $this->auth->getIdentity();		
		
		if($this->auth->hasIdentity()) {
			$data = $this->auth->getIdentity();
			$db = DatabaseService::get("SIMpel");
			$adapter = $db->getAdapter();			
			$nomor = $this->getTransaksiKasir($adapter, $data->ID);
			$integrasi = $this->getIntegrasi($adapter);
			$sysdate = System::getSysDate($adapter);
			$penggunaRuangan = $this->getRuanganPengguna($adapter, $data->ID);
			$privilagesPencarian = $this->getUserPrivilagesPencarian($adapter, $data->ID);
			$uam = $this->getUserAksesModule($adapter, $data->ID);
			
			$data = array('ID' => $data->ID, 'NAME' => $data->NAME, 'LGN' => $data->LGN, 'JNS' => $data->JNS, 'NIK' => $data->NIK, 'XPRIV' => $data->XPRIV, 'XITR' => $integrasi, 'PC' => $data->PC, 'RUANGANS' => $penggunaRuangan, 'XPRIVPENCARIAN' => $privilagesPencarian, 'XUAM' => $uam, 'NO_TRX_KSR' => $nomor, 'SYSDATE' => $sysdate);
		} else {			
			$success = false;
			$message = 'not login';
			//$this->response->setStatusCode(401);
		}
				
        return array(
			'success' => $success,
			'message' => $message,
            'data' => $data,
        );
	}
	
	private function getTransaksiKasir($adapter, $pengguna) {
		$stmt = $adapter->query('
			SELECT NOMOR
			  FROM pembayaran.transaksi_kasir
			 WHERE KASIR = ?
			   AND STATUS = 1
			 ORDER BY BUKA DESC LIMIT 1');
		$results = $stmt->execute(array($pengguna));
		$row = $results->current();
		$nomor = null;
		if($row) {
			$nomor = $row['NOMOR'];
		}
		return $nomor;
	}
	
	public function logoutAction() {
		$this->auth->clearIdentity();
		//$data = $this->auth->getIdentity();
		//var_dump('hai');
		//$this->response->setStatusCode(401);
		
		return array(
            'success' => true,
			'message' => 'logout',
            'data' => null
        );
	}
}