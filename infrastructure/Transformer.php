<?php
namespace Infrastructure;
abstract class Transformer {

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */ 
	
	public function transformCollection(array $data)
	{
		return array_map([$this,'transform'],$data);
	}	

}
