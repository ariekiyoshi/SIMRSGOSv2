<?php
namespace INACBGService\V1\Rest\ListProcedure;

class ListProcedureResourceFactory
{
    public function __invoke($services)
    {
        return new ListProcedureResource();
    }
}
