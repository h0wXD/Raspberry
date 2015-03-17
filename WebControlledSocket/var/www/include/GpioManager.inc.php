<?php

class GpioManager
{
	private static $toggleValues;
	private $count;

	public function __construct($count)
	{
		self::$toggleValues = array(STATUS_ON => STATUS_OFF, STATUS_OFF => STATUS_ON);
		$this->count = $count;
		for ($pin = 0; $pin < $count; $pin++)
		{
			system("gpio mode ".$pin." out");
		}
	}

	public function __destruct()
	{
	}
	
	private function validatePin($pin)
	{
		if ($pin < 0 && 
			$pin >= $this->count ||
			!is_numeric($pin))
		{
			throw new Exception('Invalid pin provided.');
		}
	}
	
	private function validateStatus($status)
	{
		if (!in_array($status, self::$toggleValues))
		{
			throw new Exception('Invalid status provided.');
		}
	}
	
	private function internalRead($pin)
	{
		$outputStatus = null;
		exec("gpio read ".$pin, $outputStatus);
		
		return $outputStatus[0];
	}
	
	public function read($pin)
	{
		$this->validatePin($pin);
		
		return $this->internalRead($pin);
	}
	
	public function internalWrite($pin, $status, &$return)
	{
		system("gpio write ".$pin." ".$status);
		$newStatus = $this->read($pin);
		
		$return = $newStatus;
		
		return $newStatus == $status;
	}
	
	public function write($pin, $status, &$return = null)
	{
		$this->validatePin($pin);
		$this->validateStatus($status);
		
		return $this->internalWrite($pin, $status, $return);
	}
	
	public function toggle($pin, &$return)
	{
		$this->validatePin($pin);
		
		$currentStatus = $this->internalRead($pin);
		
		return $this->internalWrite($pin, self::$toggleValues[$currentStatus], $return);
	}
}

?>