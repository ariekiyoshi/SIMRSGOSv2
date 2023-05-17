<?php
namespace General\V1\Rest\TempatLahir;

class TempatLahirResourceFactory
{
    public function __invoke($services)
    {
        return new TempatLahirResource();
    }
}
