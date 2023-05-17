<?php
namespace Informasi\V1\Rest\InfromasiRuangKamarTidur;

class InfromasiRuangKamarTidurResourceFactory
{
    public function __invoke($services)
    {
        return new InfromasiRuangKamarTidurResource();
    }
}
