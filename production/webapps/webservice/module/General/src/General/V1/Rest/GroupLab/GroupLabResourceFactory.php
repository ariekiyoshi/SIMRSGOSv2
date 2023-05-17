<?php
namespace General\V1\Rest\GroupLab;

class GroupLabResourceFactory
{
    public function __invoke($services)
    {
        return new GroupLabResource();
    }
}
