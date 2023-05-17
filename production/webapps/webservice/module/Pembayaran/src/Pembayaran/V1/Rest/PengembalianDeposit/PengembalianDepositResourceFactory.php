<?php
namespace Pembayaran\V1\Rest\PengembalianDeposit;

class PengembalianDepositResourceFactory
{
    public function __invoke($services)
    {
        return new PengembalianDepositResource();
    }
}
