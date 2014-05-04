<?php

namespace Gestor\CrudBundle\Util;

/* =====================================================================
 * Funciones Did-web.com
 * Eduard Pinuaga Linares
 * ================================================================== */


	/* =================================================================
	 * Dame Plurales
	 * 5-1-2014
	 * ============================================================== */
class Plurales
{
	
	

	public function DamePlural($valor,$txtsingular,$txtplural)
	{
		if($valor>1){
		return $txtplural;
		}elseif($valor<=1){
		return $txtsingular;
		}
			
		
	}


	
}
