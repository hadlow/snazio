<?php

class File
{
	private $file;

	private $file_path;

	public function __construct($file_path = '', $mode = 'r')
	{
		$this->file_path = $file_path;
		$this->file = fopen($file_path, $mode);
	}

	public function write_yaml($data = array())
	{
		$stringyaml = json_encode($data);

		return fwrite($this->file, $stringyaml);
	}

	public function write($data = '')
	{
		return fwrite($this->file, $data);
	}

	public function read()
	{
		return fread($this->file, filesize($this->file_path));
	}

	public function read_yaml()
	{
		$yaml = json_decode(file_get_contents($this->file_path), true);

		return $yaml;
	}

	public function delete()
	{

	}

	public function close()
	{
		fclose($this->file);
	}
}