<?php
namespace LISService\novanet\dbservice\result;
use DBService\SystemArrayObject;

class Entity extends SystemArrayObject
{
	protected $fields = array(
		"ID"=>1, "MsgID"=>1, "_PID"=>1, "_OID"=>1, "SeqNum"=>1, "UnivTestID"=>1, "UnivTestName"=>1, "UniTestIDType"=>1, "InstrTestID"=>1,
		"InstrTestName" =>1, "InstrTestMisc"=>1, "RValue"=>1, "Unit"=>1, "HL_Limit"=>1, "AHL_Limit"=>1, "ANormalFlag"=>1, "NatureAnormalTest"=>1,
		"Status"=>1, "DateChangeInstrValue"=>1, "OperatiorID"=>1, "TestStartDate"=>1, "TestEndDate"=>1, "InstrSectionID"=>1,
		"R1"=>1, "R2"=>1, "R3"=>1, "R4"=>1, "R5"=>1, "BioRFlag"=>1, "lastUpdDatetime"=>1, "queueMsgID"=>1, "refID"=>1
	);
}

