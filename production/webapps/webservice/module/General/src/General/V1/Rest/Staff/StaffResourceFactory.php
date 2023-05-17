<?php
namespace General\V1\Rest\Staff;

class StaffResourceFactory
{
    public function __invoke($services)
    {
        return new StaffResource();
    }
}
