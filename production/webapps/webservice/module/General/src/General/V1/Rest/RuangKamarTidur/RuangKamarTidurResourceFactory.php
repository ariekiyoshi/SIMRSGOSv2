<?php
namespace General\V1\Rest\RuangKamarTidur;

class RuangKamarTidurResourceFactory
{
    public function __invoke($services)
    {
        return new RuangKamarTidurResource();
    }
}
