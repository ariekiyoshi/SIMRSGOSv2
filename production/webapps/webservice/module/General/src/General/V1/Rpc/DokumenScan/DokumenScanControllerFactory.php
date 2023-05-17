<?php
namespace General\V1\Rpc\DokumenScan;

class DokumenScanControllerFactory
{
    public function __invoke($controllers)
    {
        return new DokumenScanController();
    }
}
