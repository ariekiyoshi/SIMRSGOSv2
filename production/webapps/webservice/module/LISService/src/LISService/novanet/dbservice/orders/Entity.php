<?php
namespace LISService\novanet\dbservice\orders;
use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array('order_number'=>1, 'visit_number'=>1, 'patient_id'=>1,'order_datetime'=>1,'service_unit_id'=>1,'service_unit_name'=>1,'guarantor_id'=>1,'guarantor_name'=>1, 'agreement_id'=>1, 'agreement_name'=>1, 'doctor_id'=>1, 'doctor_name'=>1, 'class_id'=>1, 'class_name'=>1, 'ward_id'=>1, 'ward_name'=>1);
}

