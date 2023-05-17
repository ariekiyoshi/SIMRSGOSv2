<?php
namespace Plugins\V1\Rest\RequestReport;

class RequestReportResourceFactory
{
    public function __invoke($services)
    {
        return new RequestReportResource($services);
    }
}
