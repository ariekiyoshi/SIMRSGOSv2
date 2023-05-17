<?php
namespace General\V1\Rest\PaketDetil;

class PaketDetilResourceFactory
{
    public function __invoke($services)
    {
        return new PaketDetilResource();
    }
}
