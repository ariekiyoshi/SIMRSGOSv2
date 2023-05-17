<?php
namespace INACBGService\V1\Rest\TipeINACBG;

class TipeINACBGResourceFactory
{
    public function __invoke($services)
    {
        return new TipeINACBGResource();
    }
}
