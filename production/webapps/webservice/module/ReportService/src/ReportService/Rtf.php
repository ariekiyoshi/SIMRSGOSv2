<?php
namespace ReportService;

use \DateTime;
use \DateInterval;
use \DateTimeZone;

class Rtf extends ReportType
{
	private $jrWordExportParm;
	private $outputName;
	
	protected function reportInit() {
		$this->jrWordExportParm = java("net.sf.jasperreports.engine.export.ooxml.JRDocxExporterParameter");
        $this->export = new \Java("net.sf.jasperreports.engine.export.JRRtfExporter");
	}
	
	protected function reportSetting() {
        $this->export->setParameter($this->jrWordExportParm->JASPER_PRINT, $this->jasperPrint);
        $dt = new DateTime(null, new DateTimeZone('UTC'));
		$ts = $dt->getTimestamp();
		
		$this->outputName = $this->rptName.$ts.".".$this->rptExt;
		
        $this->outputPath = $this->rootPath."/output/".$this->outputName;
        $this->export->setParameter($this->jrWordExportParm->OUTPUT_FILE_NAME, $this->outputPath);
	}
		
	protected function download() {
		exec("sudo chmod 775 -Rf ".$this->outputPath);
		$data = file_get_contents($this->outputPath);
		$this->response->setContent($data);
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/rtf')
			->addHeaderLine('Content-Disposition', 'attachment; filename="' . $this->outputName.'"')
			->addHeaderLine('Content-Length', strlen($data));

        unlink($this->outputPath);
	}
}
