<?php
namespace INACBGService\V1\Rest\HasilGrouping;

class HasilGroupingResourceFactory
{
    public function __invoke($services)
    {
        return new HasilGroupingResource();
    }
}
