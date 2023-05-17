<?php
namespace Pembatalan\V1\Rest\PembatalanRetur;

class PembatalanReturResourceFactory
{
    public function __invoke($services)
    {
        return new PembatalanReturResource();
    }
}
