<?php
namespace General\V1\Rest\DistribusiTarifPaket;

class DistribusiTarifPaketResourceFactory
{
    public function __invoke($services)
    {
        return new DistribusiTarifPaketResource();
    }
}
