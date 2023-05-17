<?php
namespace Kemkes\V1\Rpc\Kunjungan;

class KunjunganControllerFactory
{
    public function __invoke($controllers)
    {
        return new KunjunganController();
    }
}
