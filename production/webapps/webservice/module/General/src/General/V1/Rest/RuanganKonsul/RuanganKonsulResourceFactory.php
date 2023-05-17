<?php
namespace General\V1\Rest\RuanganKonsul;

class RuanganKonsulResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganKonsulResource();
    }
}
