<?php
namespace Kemkes\V1\Rpc\PendaftaranOnline;

class PendaftaranOnlineControllerFactory
{
    public function __invoke($controllers)
    {
        return new PendaftaranOnlineController();
    }
}
