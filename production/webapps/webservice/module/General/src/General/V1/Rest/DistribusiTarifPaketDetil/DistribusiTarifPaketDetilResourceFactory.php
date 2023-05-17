<?php
namespace General\V1\Rest\DistribusiTarifPaketDetil;

class DistribusiTarifPaketDetilResourceFactory
{
    public function __invoke($services)
    {
        return new DistribusiTarifPaketDetilResource();
    }
}
