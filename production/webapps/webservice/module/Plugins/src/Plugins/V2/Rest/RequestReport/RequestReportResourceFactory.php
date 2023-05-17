<?php
namespace Plugins\V2\Rest\RequestReport;

class RequestReportResourceFactory
{
    public function __invoke($services)
    {
        return new RequestReportResource($services);
    }
}
