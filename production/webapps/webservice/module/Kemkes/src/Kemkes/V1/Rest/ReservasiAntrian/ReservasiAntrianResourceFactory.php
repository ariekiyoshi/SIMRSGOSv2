<?php
namespace Kemkes\V1\Rest\ReservasiAntrian;

class ReservasiAntrianResourceFactory
{
    public function __invoke($services)
    {
        return new ReservasiAntrianResource();
    }
}
