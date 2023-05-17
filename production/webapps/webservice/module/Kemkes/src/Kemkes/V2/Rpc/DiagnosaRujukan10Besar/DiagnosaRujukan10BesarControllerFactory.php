<?php
namespace Kemkes\V2\Rpc\DiagnosaRujukan10Besar;

class DiagnosaRujukan10BesarControllerFactory
{
    public function __invoke($controllers)
    {
        return new DiagnosaRujukan10BesarController();
    }
}
