<?php
namespace General\V1\Rest\TindakanRuangan;

class TindakanRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new TindakanRuanganResource();
    }
}
