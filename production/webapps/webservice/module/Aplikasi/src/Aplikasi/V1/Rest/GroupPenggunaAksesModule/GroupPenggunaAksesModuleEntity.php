<?php
namespace Aplikasi\V1\Rest\GroupPenggunaAksesModule;
use DBService\SystemArrayObject;

class GroupPenggunaAksesModuleEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'GROUP_PENGGUNA'=>1, 'MODUL'=>1, 'STATUS'=>1);
}
