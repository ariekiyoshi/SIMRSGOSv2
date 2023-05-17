<?php
namespace Aplikasi\V1\Rest\Sinkronisasi;

class SinkronisasiResourceFactory
{
    public function __invoke($services)
    {
        return new SinkronisasiResource();
    }
}
