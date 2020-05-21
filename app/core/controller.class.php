<?php

class Controller
{
	/*
		@view
		@input string
		@output object
	*/
	protected function view($view, $d = array())
	{
		// include the view
		require_once '../app/views/' . $view . '.php';
	}
	
	/*
		@model
		@input string
		@output object
	*/
	protected function model($model, $construct = null)
	{
		// include the model
		require_once '../app/models/' . strtolower($model) . '.class.php';
		
		// return the mdoel object
		return new $model($construct);
	}
}
