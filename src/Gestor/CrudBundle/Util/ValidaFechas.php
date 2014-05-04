<?php

namespace Gestor\CrudBundle\Util;

/* =====================================================================
 * Funciones Did-web.com
 * Eduard Pinuaga Linares
 * ================================================================== */




	/* =================================================================
	 * Validar fechas inicio inferior al final
	 * 29-12-2013
	 * ============================================================== */
class ValidaFechas
{
	
	

	public function Valida($Finicio,$Ffinal)
	{
		$datetime1 = new \DateTime($Finicio);
		$datetime2 = new \DateTime($Ffinal);
		$interval = $datetime1->diff($datetime2);
		return $interval->format('%R%a ');
			
		
	}


	
}



