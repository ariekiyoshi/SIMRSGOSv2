<?php
namespace General\V1\Rest\Videos;
use DBService\SystemArrayObject;

class VideosEntity extends SystemArrayObject
{
	protected $fields = array('ID'=>1, 'JENIS'=>1, 'NAMA'=>1, 'DURASI'=>1, 'PUBLISH'=>1, 'IDX'=>1, 'STATUS'=>1);
}
