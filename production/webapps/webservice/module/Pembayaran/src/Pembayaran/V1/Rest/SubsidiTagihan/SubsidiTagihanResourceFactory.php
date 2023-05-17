<?php
namespace Pembayaran\V1\Rest\SubsidiTagihan;

class SubsidiTagihanResourceFactory
{
    public function __invoke($services)
    {
        return new SubsidiTagihanResource();
    }
}
