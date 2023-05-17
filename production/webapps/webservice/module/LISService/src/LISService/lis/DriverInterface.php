<?php


namespace LISService\lis;


/**
 * @author admin
 * @version 1.0
 * @created 26-Mar-2016 17.35.25
 */
interface DriverInterface
{

	public function getResult();

	/**
	 * 
	 * @param $params
	 */
	public function order($params = array());

}
?>