<?php
namespace DBService;

use ZF\Rest\AbstractResourceListener;
use ZF\Rest\ResourceEvent;
use ZF\ApiProblem\ApiProblem;

use SimpleXMLElement;

use Zend\Json\Json;
use ZendXml\Security as XmlSecurity;

use Zend\Authentication\AuthenticationService;
use DBService\DatabaseService;
use Aplikasi\Signature;
use \Exception;

use Aplikasi\V1\Rest\PenggunaAksesLog\Service as PenggunaAksesLog;
use Aplikasi\V1\Rest\Objek\Service as Objek;
use Aplikasi\db\bridge_log\Service as BridgeLogService;
use DBService\generator\Generator;

use Zend\Mail;

class Resource extends AbstractResourceListener
{    
	const AUTH_TYPE_LOGIN = 0;
	const AUTH_TYPE_SIGNATURE = 1;
	const AUTH_TYPE_NOT_SECURE = 2;
	const AUTH_TYPE_SIGNATURE_OR_LOGIN = 3;
	const AUTH_TYPE_IP = 4;
	const AUTH_TYPE_IP_OR_LOGIN = 5;
	const AUTH_TYPE_IP_AND_LOGIN = 6;
	const AUTH_TYPE_SIGNATURE_OR_IP = 7;
	const AUTH_TYPE_SIGNATURE_AND_IP = 8;
	
	const CONTENT_TYPE_JSON = 'json';
		
	protected $auth;
	protected $penggunaAksesLog;
	protected $objek;
	protected $service;
	protected $privilages = [];
	protected $user;
	protected $serviceManager;
	protected $reponse;
	protected $request;
	protected $dataAkses;
	protected $integrasi = [];
	protected $crud = array(
		"create" => "C",
		"read" => "R",
		"update" => "U",
		"delete" => "D"
	);
	protected $bridgeLog;
	protected $jenisBridge = 0;
	
	private $signature = false;
	
	protected $authType = self::AUTH_TYPE_LOGIN;
	
	public static $maxRecursionDepthAllowed = 25;
	
	public function __construct() {
		$this->auth = new AuthenticationService();
		$this->penggunaAksesLog = new PenggunaAksesLog();
		$this->bridgeLog = new BridgeLogService();
		$this->objek = new Objek();
	}

	public function setServiceManager($serviceManager) {
		$this->serviceManager = $serviceManager;
		$this->response = $this->serviceManager->get("response");
		$this->request = $this->serviceManager->get("request");	
	}
	
	public function getSignature() {
		return $this->signature;
	}

	protected function writeBridgeLog($data=[]) {
		$isCreate = isset($data["ID"]) ? false : true;
		if($isCreate) $data["ID"] = Generator::generateIdBridgeLog();
		$data["JENIS"] = $this->jenisBridge;
		$this->bridgeLog->simpanData($data, $isCreate);
		return $data["ID"];
	}
    /**
     * Dispatch an incoming event to the appropriate method
     *
     * Marshals arguments from the event parameters.
     *
     * @param  ResourceEvent $event
     * @return mixed
     */
    public function dispatch(ResourceEvent $event)
    {
		if($this->authType == self::AUTH_TYPE_LOGIN) {
			$result = $this->doAuthLogin();
			if(is_array($result)) return $result;
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE) {
			$result = $this->doAuthSignature();
			if($result instanceof ApiProblem) return $result;
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_OR_LOGIN) {
			$result = $this->doAuthLogin();
			if(is_array($result)) {
				$result = $this->doAuthSignature();
				if($result instanceof ApiProblem) return $result;
			}
		} else if($this->authType == self::AUTH_TYPE_IP) {
			$result = $this->doAuthIP();
			if($result instanceof ApiProblem) return $result;
		} else if($this->authType == self::AUTH_TYPE_IP_OR_LOGIN) {
		    $result = $this->doAuthIP();
		    if($result instanceof ApiProblem) {
		        $result = $this->doAuthLogin();
		        if(is_array($result)) {
		            $this->response->setStatusCode(405);
		            $e->setResult($result);
		            return $result;
		        }
		    }
		    if($result instanceof ApiProblem) return $result;
		} else if($this->authType == self::AUTH_TYPE_IP_AND_LOGIN) {
		    $result = $this->doAuthIP();
		    if(!($result instanceof ApiProblem)) {
		        $result = $this->doAuthLogin();
		        if(is_array($result)) {
		            $this->response->setStatusCode(405);
		            $e->setResult($result);
		            return $result;
		        }
		    }
		    if($result instanceof ApiProblem) return $result;
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_OR_IP) {
		    $headers = $this->request->getHeaders();
		    if($headers->get("X-Signature")) {
		        $result = $this->doAuthSignature();
		    } else {
		        $result = $this->doAuthIP();
		    }
		    if($result instanceof ApiProblem) return $result;
		} else if($this->authType == self::AUTH_TYPE_SIGNATURE_AND_IP) {
		    $result = $this->doAuthSignature();
		    if(!($result instanceof ApiProblem)) {
		        $result = $this->doAuthIP();
		    }
		    if($result instanceof ApiProblem) return $result;
		}
				
		$eventName = $event->getName();
		$id = $event->getParam('id', null);
        $data = (array) $event->getParam('data', []);
		$dataBeforeUpdate = array();
		$dataBefore = array();
		$deleteData = array();
		$keys = [];
		$service = $this->service instanceof Service ? $this->service : null;
		$entity = null;
		if($service) {
			$entity = $service->getEntity();
			if($entity) $keys = $entity->getIdKeys();
		}
		
		if(count($keys) == 0) return parent::dispatch($event);
		
		if($this->isWriteLog()) {
			if($this->isCUD($event)) {
				/* load data sebelum di cud */
				if($eventName == "update" || $eventName == "delete") {
					$result = $service->load(array(
						$keys[0] => $id
					));
					
					if(count($result) > 0) {
						if($eventName == "update") {
							foreach($data as $key => $val) {
								if(isset($result[0][$key])) {							
									if($result[0][$key] != $val) {
										$dataBeforeUpdate[$key] = $result[0][$key];
									} else {
										$deleteData[$key] = $val;
									}
								}
							}
							
							if(isset($dataBeforeUpdate["OLEH"])) unset($dataBeforeUpdate["OLEH"]);
							if(isset($dataBeforeUpdate[$keys[0]])) unset($dataBeforeUpdate[$keys[0]]);
						}
						$dataBefore = $result[0];
					}
				}
			}
		}
		
        $return = parent::dispatch($event);
		if(!isset($return)) return $return;
		if($return instanceof ApiProblem) return $return;
		
		if($this->isWriteLog()) {
			if($this->isCUD($event)) {
				$tipe = $this->crud[$eventName];
				$usrId = $this->user;
				$tableIdentifier = $service->getTable()->getTable();
				$table = is_object($tableIdentifier) ? $tableIdentifier->getTable() : $tableIdentifier;
				$schema = is_object($tableIdentifier) ? ($tableIdentifier->hasSchema() ? $tableIdentifier->getSchema() : "") : "";
				$objek = $schema == "" ? $table : $schema.".".$table;
				$ref = $id;
				$sebelum = $sesudah = "";
				$dt = null;
				
				/* get key value after insert */
				if($eventName == "create") {
					if(isset($return["success"])) {
						if($return["success"]) {
							if(isset($return["data"])) {
								$dt = $return["data"];								
								if(is_array($dt)) {
									if(count($dt) > 0) {
										if(!isset($dt[$keys[0]])) {
											if(isset($dt[0])) {
												if(is_string($dt[0]) || is_integer($dt[0])) $dt = $dt[0];												
											}
										}
									}
								} 
								if($dt) {
									if(isset($dt["success"])) $dt = null;
								}
							}
						}
					} else {
						if(is_array($return)) {
							if(count($return) > 0) {
								$dt = $return[0];
							}
						} else {
							if(is_string($return) || is_integer($return)) $ref = $return;
							else return $return;
						}
					}
					
					if($dt) {
						if(!isset($dt[$keys[0]])) {
							if(count($dt) > 0) {
								$dt = $dt[0];
								if(!isset($dt[$keys[0]])) return $return;
							}
						}
						$ref = $dt[$keys[0]];				
						
						if($ref == "" || $ref == null) {
							try {
								$ref = $service->getTable()->getLastInsertValue();
								if($ref == 0) return $return;
							} catch(\Exception $e) {
								return $return;
							}
						}
					} else return $return;
				}
				
				if($eventName == "update") {				
					if(isset($data["OLEH"])) unset($data["OLEH"]);
					if(isset($data[$keys[0]])) unset($data[$keys[0]]);
					foreach($deleteData as $key => $val) {
						if(isset($data[$key])) unset($data[$key]);
					}
					$sebelum = count($dataBeforeUpdate) > 0 ? $dataBeforeUpdate : null;
					$sesudah = $data;	
				}
				
				if($eventName == "delete") {
					$sebelum = count($dataBefore) > 0 ? $dataBefore : null;
				}
				
				$ref = is_object($ref) || is_array($ref) ? json_encode($ref) : $ref;
				$sebelum = is_object($sebelum) || is_array($sebelum) ? json_encode($sebelum) : $sebelum;
				$sesudah = is_object($sesudah) || is_array($sesudah) ? json_encode($sesudah) : $sesudah;							
				
				$obj = array(
					"TABEL" => $objek
				);
				$findObject = $this->objek->load($obj);
				if(count($findObject) > 0) {
					if($findObject[0]["ENTITY"] == '' || $findObject[0]["SERVICE"] == '') {
						$findObject[0]["ENTITY"] = get_class($entity);
						$findObject[0]["SERVICE"] = get_class($service);
						$this->objek->simpan($findObject[0], false, false);
					}
					
					$this->penggunaAksesLog->simpan(array(
						"TANGGAL" => new \Zend\Db\Sql\Expression("NOW()"),
						"PENGGUNA" => $usrId,
						"AKSI" => $tipe,
						"OBJEK" => $findObject[0]["ID"],
						"REF" => $ref,
						"SEBELUM" => $sebelum ? $sebelum : "",
						"SESUDAH" => $sesudah
					), true, false);
				}
			}
		}
		
		return $return;
    }
	
	private function isCUD($event) {
		$name = $event->getName();
		return $name == "create" || $name == "update" || $name == "delete"; 
	}
	
	private function isWriteLog() {
		if($this->service) {
			if($this->service instanceof Service) return $this->service->isWriteLog();
			else return false;
		} else return false;
	}
	
	private function doAuthLogin() {
		if(!$this->auth->hasIdentity()) {
			return array(
				'success' => false,
				'message' => 'not login',
				'data' => null
			);
		} else {
			$data = $this->auth->getIdentity();
			$this->privilages = (array) $data->XPRIV;
			if(isset($data->XITR)) $this->integrasi = $data->XITR;
			$this->user = $data->ID;
			$this->dataAkses = $data;
		}
	}
	
	private function doAuthSignature() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: request is null');
		}
		
		$headers = $this->request->getHeaders();					
		$this->signature = new Signature(
			$headers->get("X-Id"),
			$headers->get("X-Timestamp"),
			$headers->get("X-Signature")
		);
		
		try {
			$this->signature->isValidSignature();
		} catch(\Exception $e) {
			return new ApiProblem($e->getCode(), $e->getMessage());
		}
	}
	
	private function doAuthIP() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: request is null');
		}
		
		$ip = $this->request->getServer('REMOTE_ADDR');
		
		$db = DatabaseService::get("SIMpel");
		$adapter = $db->getAdapter();
		
		$stmt = $adapter->query('
			SELECT *
			  FROM aplikasi.allow_ip_authentication
			 WHERE NOMOR = ?');
		$results = $stmt->execute(array($ip));
		$result = $results->current();
		$allow = false;
		if($result) {
			$allow = $result["STATUS"] == 1;
		}
		
		if(!$allow) {
			return new ApiProblem(401, "IP $ip is not allowed / registered");
		}
		
		return true;
	}
	
	/*
		Examples:
		array(
			'123' => '123'
		);
	*/
	public function isAllowPrivilage($id) {
		$allow = false;
		foreach($this->privilages as $key=>$val) {
			if($key == $id) {
				$allow = true;
				break;
			}
		}
		return $allow;
	}
	
	/*
		Examples:
		array(
			'0' => array(
				'ID' => '1'
			)
		);
	*/
	public function isIntegrasi($field, $val) {
		$allow = false;
		foreach($this->integrasi as $data) {
			$data = (array) $data;
			if($data[$field] == $val) {
				$allow = true;
				break;
			}
		}
		return $allow;
	}
	
	public function toResponse($data = []) {
		if($this->request->getHeaders()->get("accept")->getFieldValue() == "application/json") $this->toJson($data);
		return $this->toXML($data);
	}
	
	public function toJson($data = []) {
		$json = json_encode($data);
		//$json = str_replace("]", "", str_replace("[", "", $json));		
		$this->response->setContent($json);
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/json')
			->addHeaderLine('Content-Length', strlen($json));
        
		return  $this->response;
	}
	
	public function toXML($data = []) {
		$doc = new SimpleXMLElement("<xml version='1.0'></xml>");
		foreach($data as $entity) {
			$dt = $doc->addChild("data");
			foreach($entity as $key => $val) {
				$dt->addChild($key, htmlspecialchars($val));
			}
		}
		$xmlString = $doc->asXML();
		$xmlString = str_replace(array('<?xml version="1.0"?>', "\n", "\r"), "", $xmlString);
		$this->response->setContent($xmlString);
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/xml')
			->addHeaderLine('Content-Length', strlen($xmlString));
        
		return  $this->response;
	}
	
	public function downloadDocument($content, $tipe, $ext, $id, $attachment = true) {
		$this->response->setContent($content);
		$headers = $this->response->getHeaders();
		$filename = $id.".".$ext;
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', $tipe)
			->addHeaderLine('Content-Length', strlen($content));
			
		if($attachment) $headers->addHeaderLine('Content-Disposition', 'attachment; filename="'.$filename.'"');
        
		return $this->response;
	}
	
	protected function getXMLPostData() {
		if(!isset($this->request)) {
			return new ApiProblem(500, 'Error Script: request is null');
		}
						
        return new SimpleXMLElement($this->request->getContent());
	}
	
	/**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
		if(isset($params['REFERENSI'])) {
			$this->service->setReferences((array) json_decode($params['REFERENSI']), true);
			unset($params['REFERENSI']);
		}
		
		if(isset($params['COLUMNS'])) {	
			$this->service->setColumns((array) json_decode($params['COLUMNS']));
			unset($params['COLUMNS']);
		}
		
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }
	
	/*
	 * array(
	 *		url => "Uniform Resource Locator",
	 *		action => "Web Service Name",
	 *		method => "{GET|POST|PUT|DELETE}",
	 *		data => "JSON String",	
	 *		header => []
	 * )
	*/
	protected function sendRequest($options = []) {
		$curl = curl_init();
				
		$action = isset($options["action"]) ? (trim($options["action"]) != "" ? "/".$option["action"] : "") : "";
		$headers = array(
			"Content-type: application/json"
		);
		
		$data = isset($options["data"]) ? json_encode($options["data"]) : "";
		
		$headers[] = "Content-length: ".strlen($data);
		
		if(isset($options["header"])) $headers = array_merge($headers, $options["header"]);	
		
		$id = $this->writeBridgeLog([
			"URL" => $options["url"].$action,
			"REQUEST" => $data,
			"ACCESS_FROM_IP" => $_SERVER['REMOTE_ADDR']
		]);
		
		curl_setopt($curl, CURLOPT_URL, $options["url"].$action);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $options["method"]);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);		
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$result = curl_exec($curl);		
		curl_close($curl);

		$this->writeBridgeLog([
			"ID" => $id,
			"RESPONSE" => $result
		]);
			
		return json_decode($result);
	}
	
	protected function getPostData() {
		$request = $this->request;
		if ($this->requestHasContentType($request, self::CONTENT_TYPE_JSON)) {
			$data = Json::decode($request->getContent(), $this->jsonDecodeType);
        } else {
            $data = $request->getPost()->toArray();
        }
		
		return $data;
	}
	
	/* Send Mail */
	public function sendMail($from = null, $to, $subject, $body) {
	    $mail = new Mail\Message();
	    
	    $mail->setBody($body);
	    $mail->setFrom(isset($from) ? $from : $this->instansi[0]["EMAIL"]);
	    $mail->addTo($to);
	    $mail->setSubject($subject);
	    
	    $smtpOption = new Mail\Transport\SmtpOptions(array(
	        'name' => 'mail.simpel.web.id',
	        'host'  => '49.50.8.190',
	        'port' => 587,
	        'connection_class' => 'login',
	        'connection_config' => array(
	            'username' => 'admin@simpel.web.id',
	            'password' => 's1mp3l_bismillah'
	        )
	    ));
	    
	    $transport = new Mail\Transport\Smtp($smtpOption);
	    
	    try {
	        $transport->send($mail);
	    } catch(\Exception $e) {
	        return new ApiProblem(500, 'Error: '.($e->message));
	    }
	}
}
