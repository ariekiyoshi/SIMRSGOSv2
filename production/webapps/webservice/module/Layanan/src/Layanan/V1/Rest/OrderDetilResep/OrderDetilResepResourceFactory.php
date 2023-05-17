<?php
namespace Layanan\V1\Rest\OrderDetilResep;

class OrderDetilResepResourceFactory
{
    public function __invoke($services)
    {
        return new OrderDetilResepResource();
    }
}
