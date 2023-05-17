<?php
namespace Kemkes\V2\Rpc\Kematian;

class KematianControllerFactory
{
    public function __invoke($controllers)
    {
        return new KematianController();
    }
}
