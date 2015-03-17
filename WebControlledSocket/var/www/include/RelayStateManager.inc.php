<?php

class RelayStateManager implements JsonSerializable
{
	private $filename;
	private $data;
	private $modified;

	public function __construct($filename)
	{
		$this->filename = $filename;

		if (file_exists($filename))
		{
			$this->data = json_decode(file_get_contents($filename), true);
		}
		
		if ($this->data == null)
		{
			$this->data = array();
		}
		if (!array_key_exists('state', $this->data))
		{
			$this->data['state'] = array();
		}
		
		$this->modified = false;
	}
	
	public function __destruct()
	{
		if ($this->modified)
		{
			file_put_contents($this->filename, json_encode($this));
		}
	}
	
	public function setState($pin, $value)
	{
		$this->data['state'][$pin] = $value;
		$this->modified = true;
	}
	
	public function getState($pin)
	{
		if (array_key_exists($pin, $this->data['state']))
		{
			return $this->data['state'][$pin];
		}
		
		return $this->data['state'][$pin] = STATUS_OFF;
	}
	
	public function jsonSerialize()
	{
		return (array)$this->data;
	}
}

?>