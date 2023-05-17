<?php
namespace Plugins\V2\Rpc\TTS;

class TTSControllerFactory
{
    public function __invoke($controllers)
    {
        return new TTSController($controllers);
    }
}
