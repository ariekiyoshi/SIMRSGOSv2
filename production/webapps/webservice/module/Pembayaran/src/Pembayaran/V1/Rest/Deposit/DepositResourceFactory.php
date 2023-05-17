<?php
namespace Pembayaran\V1\Rest\Deposit;

class DepositResourceFactory
{
    public function __invoke($services)
    {
        return new DepositResource();
    }
}
