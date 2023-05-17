<?php
namespace General\V1\Rest\RuanganLaboratorium;

class RuanganLaboratoriumResourceFactory
{
    public function __invoke($services)
    {
        return new RuanganLaboratoriumResource();
    }
}
