<?php
namespace ReportService;

class Pdf extends ReportType
{
	protected function reportInit() {
		$this->export = java("net.sf.jasperreports.engine.JasperExportManager");
	}
	
	protected function reportSetting() {		
        $this->outputPath = $this->rootPath."/output/".$this->rptName.".".$this->rptExt;
	}
	
	public function toReport() {
		$this->export->exportReportToPdfFile($this->jasperPrint, $this->outputPath);
		if(!$this->print) $this->download();
		else $this->requestForPrint();
    }
	
	protected function download() {		
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/pdf');
			
		header("Content-type: application/pdf");
        
		exec("sudo chmod 775 -Rf ".$this->outputPath);
        readfile($this->outputPath);

        unlink($this->outputPath);
	}
}
