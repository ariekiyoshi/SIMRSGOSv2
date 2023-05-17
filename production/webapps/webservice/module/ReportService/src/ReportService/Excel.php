<?php
namespace ReportService;

class Excel extends ReportType
{
	/* report vars */
	private $jrExcelExportParm;
	
	protected function reportInit() {
	    $this->export = new \Java("net.sf.jasperreports.engine.export.ooxml.JRXlsxExporter");
	    //$this->jrExcelExportParm = java("net.sf.jasperreports.engine.export.JExcelApiExporterParameter");
	    //$this->excelExport = new Java("net.sf.jasperreports.engine.export.JRXlsExporter");
	    //$this->export = new \Java("net.sf.jasperreports.engine.export.JExcelApiExporter");
	}
	
	protected function reportSetting() {
	    $this->sei = new \Java("net.sf.jasperreports.export.SimpleExporterInput", $this->jasperPrint);
	    $this->outputPath = $this->rootPath."/output/".$this->rptName.".".$this->rptExt;
	    $this->file = new \Java("java.io.File", $this->outputPath);
	    $this->soseo = new \Java("net.sf.jasperreports.export.SimpleOutputStreamExporterOutput", $this->file);
	    $this->sxrc = new \Java("net.sf.jasperreports.export.SimpleXlsxReportConfiguration");
	    $this->export->setExporterInput($this->sei);
	    $this->export->setExporterOutput($this->soseo);
	    $this->sxrc->setDetectCellType(true);
	    //$this->sxrc->setCollapseRowSpan(false);
	    $this->export->setConfiguration($this->sxrc);
	    /*$this->export->setParameter($this->jrExcelExportParm->JASPER_PRINT, $this->jasperPrint);
	     $this->export->setParameter($this->jrExcelExportParm->IS_REMOVE_EMPTY_SPACE_BETWEEN_ROWS, false);
	     $this->export->setParameter($this->jrExcelExportParm->IS_REMOVE_EMPTY_SPACE_BETWEEN_COLUMNS, false);
	     $this->export->setParameter($this->jrExcelExportParm->IS_DETECT_CELL_TYPE, true);
	     $this->export->setParameter($this->jrExcelExportParm->IS_FONT_SIZE_FIX_ENABLED, true);
	     $this->export->setParameter($this->jrExcelExportParm->IS_IMAGE_BORDER_FIX_ENABLED, true);
	     
	     $this->outputPath = $this->rootPath."/output/".$this->rptName.".".$this->rptExt;
	     $this->export->setParameter($this->jrExcelExportParm->OUTPUT_FILE_NAME, $this->outputPath);*/
	}
	
	protected function download() {
		exec("sudo chmod 775 -Rf ".$this->outputPath);
		$data = file_get_contents($this->outputPath);
		$this->response->setContent($data);
		$headers = $this->response->getHeaders();
		$headers->clearHeaders()
			->addHeaderLine('Content-Type', 'application/excel')
			->addHeaderLine('Content-Disposition', 'attachment; filename="' . $this->rptName . '.'.$this->rptExt.'"')
			->addHeaderLine('Content-Length', strlen($data));

        unlink($this->outputPath);
	}
}
