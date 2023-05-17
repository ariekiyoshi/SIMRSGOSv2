<?php
namespace General\V1\Rest\ParameterTindakanLab;

class ParameterTindakanLabResourceFactory
{
    public function __invoke($services)
    {
        return new ParameterTindakanLabResource();
    }
}
