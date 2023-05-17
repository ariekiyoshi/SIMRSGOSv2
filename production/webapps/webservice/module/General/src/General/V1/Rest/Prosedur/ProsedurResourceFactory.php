<?php
namespace General\V1\Rest\Prosedur;

class ProsedurResourceFactory
{
    public function __invoke($services)
    {
        return new ProsedurResource();
    }
}
