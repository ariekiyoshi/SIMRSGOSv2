<?php
namespace Aplikasi\V1\Rest\GroupPenggunaAksesModule;

class GroupPenggunaAksesModuleResourceFactory
{
    public function __invoke($services)
    {
        return new GroupPenggunaAksesModuleResource();
    }
}
