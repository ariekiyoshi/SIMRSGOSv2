<?php
namespace General\V1\Rest\StaffRuangan;

class StaffRuanganResourceFactory
{
    public function __invoke($services)
    {
        return new StaffRuanganResource();
    }
}
