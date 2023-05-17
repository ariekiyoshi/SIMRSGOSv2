<?php
namespace Kemkes\V1\Rpc\Diagnosa;

class DiagnosaControllerFactory
{
    public function __invoke($controllers)
    {
        return new DiagnosaController();
    }
}
