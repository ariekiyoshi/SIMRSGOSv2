<?php
namespace ReportService;

use ReportService\Html;
use ReportService\Pdf;
use ReportService\Word;
use ReportService\Excel;

class Report
{	
	/* report vars */
	private $jasperLoader;
	private $jasperReport;
	private $jrParameter;
	private $params;
	private $file;
	private $fillManager;
	private $jasperPrint;	
    
    public function __construct($response, $conn, $rptname, $rpttype, $rptext, $rptparams, $locale, $localeCode, $requestPrint) {		
		$this->reportInit();
		$path = $this->getReportRoot();
		$this->file = new \Java("java.io.File", $path."/".$this->replace($rptname, ".", "/").".jasper");
        $this->jasperReport = $this->jasperLoader->loadObject($this->file);
		
		/* set Parameters */
		foreach($rptparams as $name => $val) {
			$this->params->put($name, $val);
		}
        $subPath = $this->getSubReportRoot($rptname);
        $this->params->put("SUBREPORT_DIR", $subPath);
		$imagePath = $path.'/images/';
		if($rpttype == 'Html') $imagePath = '../reports/images/';
		$this->params->put("IMAGES_PATH", $imagePath);
        $this->params->put("RPT_TYPE", $rpttype);
        $this->params->put($this->jrParameter->REPORT_LOCALE, $locale);
		
		$this->jasperPrint = $this->fillManager->fillReport($this->jasperReport, $this->params, $conn);        
        $this->jasperPrint->setLocaleCode($localeCode);
		try {
			$clsName = 'ReportService\\'.$rpttype;
			$obj = new $clsName($response, $rptname, $rptext, $this->jasperPrint, $path, $requestPrint);			
			$obj->toReport();
		} catch(\Exception $e) {
			return null;
		}
    }
		
	private function reportInit() {
		$this->jasperLoader = java("net.sf.jasperreports.engine.util.JRLoader");
        $this->jasperReport = java("net.sf.jasperreports.engine.JasperReport");
		
		$this->jrParameter = java("net.sf.jasperreports.engine.JRParameter");
        
        $this->params = new \Java("java.util.HashMap");
		
		$this->fillManager = java("net.sf.jasperreports.engine.JasperFillManager");
	}
	
	private function getReportRoot() {
        return realpath(".")."/reports";
    }
	
	private function getSubReportRoot($rptnm) {
        $path = $this->getReportRoot();
        $subPath = explode(".", $rptnm);
		array_pop($subPath);
        $subPath = implode("/", $subPath);        
        $path = explode("/", $path.(isset($subPath) && strlen($subPath) != 0 ? "/".$subPath : ""));
        $path = implode("/", $path);
        return $path."/";
    }
	
	private function replace($data, $search, $replace) {
        $data = explode($search, $data);
        return $data = implode($replace, $data);
    }
}
