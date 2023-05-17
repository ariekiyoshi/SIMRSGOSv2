<?php
namespace Kemkes\V2\Rpc\PendaftaranOnline;

class PendaftaranOnlineControllerFactory
{
    public function __invoke($controllers)
    {
        return new PendaftaranOnlineController();
    }
}
