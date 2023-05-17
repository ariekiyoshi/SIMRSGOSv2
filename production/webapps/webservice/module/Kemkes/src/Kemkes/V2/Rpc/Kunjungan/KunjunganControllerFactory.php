<?php
namespace Kemkes\V2\Rpc\Kunjungan;

class KunjunganControllerFactory
{
    public function __invoke($controllers)
    {
        return new KunjunganController();
    }
}
