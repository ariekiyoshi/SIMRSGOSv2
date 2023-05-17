<?php
namespace ReportService;

class ReportType
{	
	/* report vars */
	protected $export;
	protected $rptName;
	protected $rptExt;
	protected $jasperPrint;
	protected $rootPath;
	protected $print;
	protected $outputPath;
	protected $response;
    
    function __construct($response, $rptName, $rptExt, $jasperPrint, $rootPath, $print = false) {		
		$this->rptName = $rptName;
		$this->rptExt = $rptExt;
		$this->jasperPrint = $jasperPrint;
		$this->rootPath = $rootPath;
		$this->print = $print;
		$this->response = $response;
		$this->reportInit();
		$this->reportSetting();
    }
		
	protected function reportInit() {
	}
	
	protected function reportSetting() {
	}
	
	public function toReport() {
        $this->export->exportReport();
		if(!$this->print) $this->download();
		else $this->requestForPrint();
    }
	
	protected function download() {
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'text/html');
        
		//header('Content-type: text/html');
		exec("sudo chmod 775 -Rf ".$this->outputPath);
        readfile($this->outputPath);

        unlink($this->outputPath);
	}
	
	protected function requestForPrint() {
		exec("sudo chmod 775 -Rf ".$this->outputPath);
		$data = file_get_contents($this->outputPath);
		$content = base64_encode($data);
					
        unlink($this->outputPath);
		
		echo json_encode(array(
			'content'=>$content
		));
	}
	
}
