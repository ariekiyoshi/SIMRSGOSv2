<?php
namespace LISService\novanet\dbservice\patient;
use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array('patient_id'=>1, 'gender_id'=>1, 'gender_name'=>1, 'date_of_birth'=>1, 'patient_name'=>1, 'patient_address'=>1, 'city_id'=>1, 'city_name'=>1, 'phone_number'=>1, 'fax_number'=>1, 'mobile_number'=>1, 'email'=>1);
}

