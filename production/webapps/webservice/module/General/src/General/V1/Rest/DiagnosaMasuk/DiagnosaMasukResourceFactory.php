<?php
namespace General\V1\Rest\DiagnosaMasuk;

class DiagnosaMasukResourceFactory
{
    public function __invoke($services)
    {
        return new DiagnosaMasukResource();
    }
}
