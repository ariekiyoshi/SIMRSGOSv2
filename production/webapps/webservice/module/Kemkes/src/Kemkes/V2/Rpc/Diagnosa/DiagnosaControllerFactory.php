<?php
namespace Kemkes\V2\Rpc\Diagnosa;

class DiagnosaControllerFactory
{
    public function __invoke($controllers)
    {
        return new DiagnosaController();
    }
}
