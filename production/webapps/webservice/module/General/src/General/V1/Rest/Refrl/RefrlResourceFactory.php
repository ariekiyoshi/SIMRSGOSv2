<?php
namespace General\V1\Rest\Refrl;

class RefrlResourceFactory
{
    public function __invoke($services)
    {
        return new RefrlResource();
    }
}
