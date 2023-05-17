<?php
namespace General\V1\Rpc\Photopasien;

class PhotopasienControllerFactory
{
    public function __invoke($controllers)
    {
        return new PhotopasienController();
    }
}
