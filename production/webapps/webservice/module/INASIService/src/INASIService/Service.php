<?php
/**
 * INASIService
 * @author hariansyah
 * 
 */
 
namespace INASIService;
	
/* kode error
	500: Error Query Select / Tidak Konek ke Database
	501: Peserta tidak terdaftar di table bpjs.peserta
	502: Error Request Service
*/

use \DateTime;
use \DateInterval;
use \DateTimeZone;
use Zend\Json\Json;
use Zend\Db\Adapter\Adapter;
use DBService\DatabaseService;

class Service {
	private $config;
	private $adapter;
	
	CONST RESULT_IS_NULL = "Silahkan hubungi BPJS respon data nul