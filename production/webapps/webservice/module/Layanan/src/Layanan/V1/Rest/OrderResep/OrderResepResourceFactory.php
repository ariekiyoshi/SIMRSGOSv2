<?php
namespace Layanan\V1\Rest\OrderResep;

class OrderResepResourceFactory
{
    public function __invoke($services)
    {
        return new OrderResepResource();
    }
}
