<?php
require_once('DALBancoProy.php');

class BancoProy
{
	public function BancoProy()
	{
		$this->methodTable = array(
			"getEjercicios" => array(
				"arguments" =>array(),
				"access" => "remote"
			)
		);
	}

	/**
	* Get Ejercicios
	* @access remote
	*/
	public function getEjercicios()
	{
		return DALBancoProy::getInstance()->getEjercicios();
	}

	/**
	* Get Programas
	* @access remote
	*/
	public function getProgramas()
	{
		return DALBancoProy::getInstance()->getProgramas();
	}

	/**
	* Get Programas Status
	* @access remote
	*/
	public function getProgramasStatus()
	{
		return DALBancoProy::getInstance()->getProgramasStatus();
	}

	/**
	* Get Contratos
	* @access remote
	*/
	public function getContratosStatus()
	{
		return DALBancoProy::getInstance()->getContratosStatus();
	}

	/**
	* Get Empleados vs Documentos Total
	* @access remote
	*/
	public function getEmpleadosDocumentosTotal()
	{
		return DALBancoProy::getInstance()->getEmpleadosDocumentosTotal();
	}

	/**
	* Get Empleados vs Documentos Programa
	* @access remote
	*/
	public function getEmpleadosDocumentosProg()
	{
		return DALBancoProy::getInstance()->getEmpleadosDocumentosProg();
	}
}