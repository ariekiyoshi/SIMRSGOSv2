<?php
namespace Layanan\V1\Rest\OrderLab;

class OrderLabResourceFactory
{
    public function __invoke($services)
    {
        return new OrderLabResource();
    }
}
