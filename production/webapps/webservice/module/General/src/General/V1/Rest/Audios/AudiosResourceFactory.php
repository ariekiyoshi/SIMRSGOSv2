<?php
namespace General\V1\Rest\Audios;

class AudiosResourceFactory
{
    public function __invoke($services)
    {
        return new AudiosResource();
    }
}
