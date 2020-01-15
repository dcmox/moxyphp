<?php
namespace Moxy;

class Benchmark
{
    private $_start = -1;
    private $_end     = -1;
    private $_func    = "";
    private $_times    = 1;
    
    public function __construct()
    {
    }
    
    public function run($times, $func, $args)
    {
        $this->_func = $func;
        $this->_times = $times;
    
        $args = array($args);
        
        $this->_start = microtime(true);
        for ($i = 0; $i < $times; $i++)
        {
            call_user_func_array($func, $args);
        }
        $this->_end = microtime(true);
    }

	public function start()
	{
		  $this->_start = microtime(true);
	}

	public function stop()
	{
		 $this->_end = microtime(true);
	}
    
    public function getAverage()
    {
        return ($this->_end - $this->_start) / $this->_times;
    }
    
    public function getEnd()
    {
        return $this->_end;
    }
    
    public function getStart()
    {
        return $this->_start;
    }
    
    
    public function get($dec = 6)
    {
        return number_format($this->_end - $this->_start, $dec);
    }
}

