<?php
namespace LISService\winacom\dbservice\resultbridgelis;
use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array('lis_reg_no'=>1, 'lis_test_id'=>1, 'his_reg_no'=>1, 'test_name'=>1, 'result'=>1, 'result_comment'=>1, 'reference_value'=>1, 'reference_note'=>1, 'test_flag_sign'=>1, 'test_unit_name'=>1, 'instrument_name'=>1, 'authorization_date'=>1, 'authorization_user'=>1, 'greaterthan_value'=>1, 'lessthan_value'=>1, 'company_id'=>1, 'agreement_id'=>1, 'age_year'=>1, 'age_month'=>1, 'age_days'=>1, 'his_test_id_order'=>1, 'transfer_flag'=>1);
}

