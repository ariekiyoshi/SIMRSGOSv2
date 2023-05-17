<?php
namespace Pendaftaran\V1\Rest\History;

class HistoryResourceFactory
{
    public function __invoke($services)
    {
        return new HistoryResource();
    }
}
