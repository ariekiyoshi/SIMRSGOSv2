<?php
namespace Aplikasi\V1\Rest\InfoTeks;

class InfoTeksResourceFactory
{
    public function __invoke($services)
    {
        return new InfoTeksResource();
    }
}
