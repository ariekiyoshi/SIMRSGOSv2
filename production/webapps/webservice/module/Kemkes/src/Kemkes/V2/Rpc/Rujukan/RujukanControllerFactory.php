<?php
namespace Kemkes\V2\Rpc\Rujukan;

class RujukanControllerFactory
{
    public function __invoke($controllers)
    {
        return new RujukanController();
    }
}
