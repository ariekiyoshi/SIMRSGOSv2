<?php
namespace General\V1\Rest\SMFRuangan;

class SMFRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new SMFRuanganResource();
    }
}
