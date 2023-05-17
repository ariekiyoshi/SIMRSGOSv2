<?php
namespace Layanan\V1\Rest\OrderRad;

class OrderRadResourceFactory
{
    public function __invoke($services)
    {
        return new OrderRadResource();
    }
}
