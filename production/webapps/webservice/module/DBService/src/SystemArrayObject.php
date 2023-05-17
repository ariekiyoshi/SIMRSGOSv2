<?php
namespace DBService;

use Zend\Stdlib\ArrayObject;

class SystemArrayObject extends ArrayObject
{
	protected $fields = array();
	private $allowFirstKey = array(
		"ID" => 1, "NOMOR" => 1, "NOPEN" => 1, "NORM" => 1, "NIP" => 1, "KODE"
	);
	
	public function getID() {
		return $this->get('ID');
	}
	
	public function get($name) {
		return isset($this->storage[$name]) ? $this->storage[$name] : null;
	}
	
	public function set($name, $val) {
		$this->storage[$name] = $val;
	}
	
	/**
     * Exchange the array for another one.
     *
     * @param  array|ArrayObject $data
     * @return array
     */
    public function exchangeArray($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new Exception\InvalidArgumentException('Passed variable is not an array or object, using empty array instead');
        }

        if (is_object($data) && ($data instanceof self || $data instanceof \ArrayObject)) {
            $data = $data->getArrayCopy();
        }
        if (!is_array($data)) {
            $data = (array) $data;
        }

        $storage = $this->storage;
		
		foreach($data as $key => $val) {
			if(count($this->fields) == 0) return $storage;
			if(isset($this->fields[$key])) {
				if(!is_null($val)) {
					$this->storage[$key] = $val;
				}
			}
		}

        return $storage;
    }

	public function getFilterFields($fields) {
		$filterFields = array();
		if (!is_array($fields) && !is_object($fields)) {
            throw new Exception\InvalidArgumentException('Passed variable is not an array or object, using empty array instead');
        }
		if (!is_array($fields)) {
            $fields = (array) $fields;
        }
		foreach($fields as $f) {
			if(isset($this->fields[$f])) {
				$filterFields[] = $f;
			}
		}		
        return $filterFields;
	}
	
	public function getIdKeys() {
		$keys = [];
		$keyFirstName = "";
		
		foreach($this->fields as $key => $val) {
			if($keyFirstName == "") {
				$keyFirstName = $key;				
			}
			if(is_array($val)) {
				if(isset($val["isKey"])) {
					$keys[] = $key;
				}
			}
		}
		
		if(count($keys) == 0) {
			if(isset($this->allowFirstKey[$keyFirstName])) $keys[] = $keyFirstName;
		}
		
		return $keys;
	}
	
	public function getDataWithDescription($data) {
		$data = is_array($data) ? $data : (array) $data;
		$desciptions = array();
		
		foreach($data as $key => $val) {
			if(isset($this->fields[$key])) {
				if(is_array($this->fields[$key])) {
					$desciptions[$key] = $this->fields[$key];
					$desciptions[$key]["NILAI"] = $val;
				} else {				
					$desciptions[$key]["NAMA"] = ucwords(strtolower($key));
					$desciptions[$key]["NILAI"] = $val;
				}
			}
		}
		
		return $desciptions;
	}
}
