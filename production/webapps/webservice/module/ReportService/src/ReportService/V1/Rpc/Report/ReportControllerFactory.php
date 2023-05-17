<?php
namespace ReportService\V1\Rpc\Report;

class ReportControllerFactory
{
    public function __invoke($controllers)
    {
		$rs = $controllers->get('ReportService\ReportService');
        return new ReportController($rs);
    }
}
