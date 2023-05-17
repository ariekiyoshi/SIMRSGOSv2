<?php
namespace INACBGService\V1\Rest\Grouping;

class GroupingResourceFactory
{
    public function __invoke($services)
    {
        return new GroupingResource();
    }
}
