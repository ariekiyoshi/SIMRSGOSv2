<?php
namespace Kemkes\V2\Rpc\GolonganDarah;

class GolonganDarahControllerFactory
{
    public function __invoke($controllers)
    {
        return new GolonganDarahController();
    }
}
