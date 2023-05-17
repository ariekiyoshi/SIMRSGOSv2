<?php
namespace INACBGService\V1\Rpc\Grouper;

class GrouperControllerFactory
{
    public function __invoke($controllers)
    {
        $inacbg = $controllers->get('INACBGService\Service');
        return new GrouperController($inacbg);
    }
}
