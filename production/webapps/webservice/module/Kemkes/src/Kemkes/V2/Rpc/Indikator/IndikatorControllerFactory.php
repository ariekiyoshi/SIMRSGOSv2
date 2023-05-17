<?php
namespace Kemkes\V2\Rpc\Indikator;

class IndikatorControllerFactory
{
    public function __invoke($controllers)
    {
        return new IndikatorController();
    }
}
