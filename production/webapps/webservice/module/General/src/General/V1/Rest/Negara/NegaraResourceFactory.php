<?php
namespace General\V1\Rest\Negara;

class NegaraResourceFactory
{
    public function __invoke($services)
    {
        return new NegaraResource();
    }
}
