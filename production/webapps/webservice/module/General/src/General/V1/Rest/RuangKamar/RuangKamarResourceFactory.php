<?php
namespace General\V1\Rest\RuangKamar;

class RuangKamarResourceFactory
{
    public function __invoke($services)
    {
        return new RuangKamarResource();
    }
}
