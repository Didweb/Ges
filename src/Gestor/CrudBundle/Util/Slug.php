<?php
namespace Gestor\CrudBundle\Util;



class Slug
{
static public function crearSlug($String, $separador='-')
	{ 
	$conaccentos = array(".","&","á","é","í","ó","ú","à","è","ì","ò","ù","Á", "É", "Í", "Ó", "Ú", "À", "È", "Ì", "Ò", "Ù", "A", "E", "I", "O", "U", " ","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","Y","Z","Ñ","ñ","'","ä","â","Ä","Â","ë","ê","Ë","Ê","ï","î","Ï","Î","ö","ô","Ö","Ô","ü","û","Ü","Û","ç","Ç");
	$sinaccentos = array("","-and-","a","e","i","o","u","a","e","i","o","u","a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u","-","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","y","z","n","n","-","a","a","a","a","e","e","e","e","i","i","i","i","o","o","o","o","u","u","u","u","c","c");
	$String = str_replace($conaccentos, $sinaccentos, $String);

	$slug= preg_replace("/[^a-zA-Z0-9\/_|+ -]/",'',$String);
	$slug= strtolower(trim($slug, $separador));
	$slug= preg_replace("/[\/_|+ -]+/",$separador,$slug);
	return $slug;

	}



}


