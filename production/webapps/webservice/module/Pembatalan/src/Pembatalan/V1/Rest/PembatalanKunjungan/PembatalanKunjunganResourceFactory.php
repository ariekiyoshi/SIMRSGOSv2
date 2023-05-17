<?php
namespace Pembatalan\V1\Rest\PembatalanKunjungan;

class PembatalanKunjunganResourceFactory
{
    public function __invoke($services)
    {
        return new PembatalanKunjunganResource();
    }
}
