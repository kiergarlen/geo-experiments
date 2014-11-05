<?php
require_once('DALCtrlDoc.php');

class CtrlDocService
{
	public function CtrlDocService()
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
		return DALCtrlDoc::getInstance()->getEjercicios();
	}

	/**
	* Get Programas
	* @access remote
	*/
	public function getProgramas()
	{
		return DALCtrlDoc::getInstance()->getProgramas();
	}

	/**
	* Get Programas Status
	* @access remote
	*/
	public function getProgramasStatus()
	{
		return DALCtrlDoc::getInstance()->getProgramasStatus();
	}

	/**
	* Get Contratos
	* @access remote
	*/
	public function getContratosStatus()
	{
		return DALCtrlDoc::getInstance()->getContratosStatus();
	}

	/**
	* Get Empleados vs Documentos Total
	* @access remote
	*/
	public function getEmpleadosDocumentosTotal()
	{
		return DALCtrlDoc::getInstance()->getEmpleadosDocumentosTotal();
	}

	/**
	* Get Empleados vs Documentos Programa
	* @access remote
	*/
	public function getEmpleadosDocumentosProg()
	{
		return DALCtrlDoc::getInstance()->getEmpleadosDocumentosProg();
	}
}