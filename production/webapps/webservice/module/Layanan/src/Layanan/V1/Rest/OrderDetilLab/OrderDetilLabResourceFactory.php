<?php
namespace Layanan\V1\Rest\OrderDetilLab;

class OrderDetilLabResourceFactory
{
    public function __invoke($services)
    {
        return new OrderDetilLabResource();
    }
}
