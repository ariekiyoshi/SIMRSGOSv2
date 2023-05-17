<?php
namespace General\V1\Rest\GroupTindakanLab;

class GroupTindakanLabResourceFactory
{
    public function __invoke($services)
    {
        return new GroupTindakanLabResource();
    }
}
