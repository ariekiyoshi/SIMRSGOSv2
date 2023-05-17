<?php
namespace ReportService;

use DBService\Crypto;

class ReportService
{
	/**
     * @var ReportServiceOption
     */
    private $config;
	private $conns = array();
		
	private $driver;
	private $driverManager;
	private $locale;
	
	private $crypt;
    
    public function __construct($config) {
        $this->config = $config;		
		
		$this->crypt = new Crypto(array(
			"key" => $this->config['key']
		));
		
		$this->loadJava();
		$this->connectionInit();
		$this->locale = new \Java("java.util.Locale", $this->config['db']['locale'][0], $this->config['db']['locale'][1]);
    }
	
	private function connectionInit() {
		$conns = $this->config['db']['connectionStrings'];
		foreach($conns as $conn) {		
			$this->conns[] = $this->createConnection($conn);
		}		
	}
	
	private function createConnection($connString) {
		try {
			$this->driver = new \Java($this->config['db']['driver']);	
			$this->driverManager = \Java($this->config['db']['driverManager']);			
			$this->driverManager->registerDriver($this->driver);			
			$conn = $this->driverManager->getConnection($connString);			
			return $conn;
		} catch(\JavaException $e) {
			echo "</br>Error: can't connect db";
			return array(
				'error' => $e->getMessage()
			);
		}
	}
	
	private function closeConnections() {
		foreach($this->conns as $conn) {
			if($conn) $conn->close();
		}
		$this->conns = array();
	}
	
	private function loadJava() {
		if(!(@include_once("serial/Java.inc"))) {
			if(!file_exists("serial/Java.Inc")) {
				$curl = curl_init($this->config['javaBridge']['location']);
				curl_setopt($curl, CURLOPT_FAILONERROR, true); 
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$result = curl_exec($curl);
				file_put_contents("serial/Java.inc", $result);
			}
			
			require_once("serial/Java.inc");
		}

		return true;
	}
	
    public function generate($response, $requestReport) {
		$data = base64_decode($requestReport);
		$rs = $this->crypt->decrypt($data);
		if($rs) {
			file_put_contents("logs/rpt.txt", $rs);
			$rs = json_decode($rs);
			
			new Report(
				$response,
				$this->conns[$rs->CONNECTION_NUMBER], 
				$rs->NAME,
				$rs->TYPE,
				$rs->EXT,
				$rs->PARAMETER, $this->locale, $this->config['db']['locale'][0].'_'.$this->config['db']['locale'][1], $rs->REQUEST_FOR_PRINT);			
		}
		return $response;
	}
}
