<?php
namespace Kemkes\V2\Rpc\Penyakit10Besar;

class Penyakit10BesarControllerFactory
{
    public function __invoke($controllers)
    {
        return new Penyakit10BesarController();
    }
}
