<?php
namespace INACBGService\V1\Rest\ListProcedure;
use DBService\SystemArrayObject;

class ListProcedureEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'JENIS'=>1,'PROC'=>1, 'CMG'=>1, 'GROUP_CODE'=>1);
}