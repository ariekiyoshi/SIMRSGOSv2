<?php
namespace INACBGService\V1\Rest\Inacbg;

class InacbgResourceFactory
{
    public function __invoke($services)
    {
        return new InacbgResource();
    }
}
