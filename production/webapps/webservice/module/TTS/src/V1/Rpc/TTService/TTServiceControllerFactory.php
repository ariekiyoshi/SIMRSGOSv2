<?php
namespace TTS\V1\Rpc\TTService;

class TTServiceControllerFactory
{
    public function __invoke($controllers)
    {
        return new TTServiceController($controllers);
    }
}
