<?php
namespace General\V1\Rest\DistribusiTarifTindakan;

class DistribusiTarifTindakanResourceFactory
{
    public function __invoke($services)
    {
        return new DistribusiTarifTindakanResource();
    }
}
