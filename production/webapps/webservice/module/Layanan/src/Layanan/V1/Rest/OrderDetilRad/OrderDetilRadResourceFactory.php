<?php
namespace Layanan\V1\Rest\OrderDetilRad;

class OrderDetilRadResourceFactory
{
    public function __invoke($services)
    {
        return new OrderDetilRadResource();
    }
}
