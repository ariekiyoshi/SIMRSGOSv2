<?php
namespace General\V1\Rest\TindakanPaket;

class TindakanPaketResourceFactory
{
    public function __invoke($services)
    {
        return new TindakanPaketResource();
    }
}
