<?php
namespace Kemkes\V2\Rest\ReservasiAntrian;

class ReservasiAntrianResourceFactory
{
    public function __invoke($services)
    {
        return new ReservasiAntrianResource();
    }
}
