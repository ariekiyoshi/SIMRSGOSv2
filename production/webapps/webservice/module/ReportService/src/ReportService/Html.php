<?php
namespace ReportService;

class Html extends ReportType
{
	private $jrHtmlExportParm;    
		
	protected function reportInit() {
		$this->jrHtmlExportParm = java("net.sf.jasperreports.engine.export.JRHtmlExporterParameter");
        $this->export = new \Java("net.sf.jasperreports.engine.export.JRHtmlExporter");
	}
	
	protected function reportSetting() {
		$this->export->setParameter($this->jrHtmlExportParm->JASPER_PRINT, $this->jasperPrint);
        $this->export->setParameter($this->jrHtmlExportParm->SIZE_UNIT, $this->jrHtmlExportParm->SIZE_UNIT_POINT);
        $this->export->setParameter($this->jrHtmlExportParm->IS_USING_IMAGES_TO_ALIGN, false);
        $Float = new \Java("java.lang.Float", 1.0);        
        $this->export->setParameter($this->jrHtmlExportParm->ZOOM_RATIO, $Float);
        $this->outputPath = $this->rootPath."/output/".$this->rptName.".".$this->rptExt;          
        $this->export->setParameter($this->jrHtmlExportParm->OUTPUT_FILE_NAME, $this->outputPath);
	}
}
