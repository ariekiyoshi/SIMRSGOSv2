<?php
namespace Layanan\V1\Rest\CatatanHasilLab;

class CatatanHasilLabResourceFactory
{
    public function __invoke($services)
    {
        return new CatatanHasilLabResource();
    }
}
