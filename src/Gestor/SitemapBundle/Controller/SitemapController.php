<?php

namespace Gestor\SitemapBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class SitemapController extends Controller
{
	private $nsites;
	private $solicitud;
	private $separar;
	private $separarFinal;
	private $locales;
	private $nlocales;
	private $raiz;
	
	
	public function inicio()
	{
		$this->setSolicitud();
		$this->setLocales();
		
		$this->nlocales		=  count($this->locales);
		$this->separar		=  explode("|",$this->solicitud);
		$this->nsites		=  count($this->separar);
		$y=0;
		for ($n=0;$n<=count($this->nsites);$n++)
			{
			$suleta = explode("*",$this->separar[$n]);	
				
				for ($x=0;$x<=count($suleta)-1;$x++)
					{
					$this->separarFinal[$y]=array(
									'entidad'	=>$suleta[0],
									'ruta'		=>$suleta[1]
									);
					}
					$y++;
			}
		
		return 0;
	}
	

    public function salidaAction(Request $request)
    {
		$this->inicio();
		$links	= $this->montamos($request);
        return $this->render('GestorSitemapBundle:Default:salida.xml.twig',array(
							'links'	=> $links));
    }



	public function montamos($request)
	{
		
		$elslug=$this->get('crear_slug');
		$loslinks=array();
		$l=0;


		
		if(count($this->separarFinal)>0){
			$tadasrutas=array();
			$nt=0;
			$elid='';
			$em = $this->getDoctrine()->getManager();
			
				if ($this->nlocales==1){
					$total=1;}
					else{
					$total=$this->nlocales-1;}
					
			for($g=0;$g<=$total;$g++) // vueltas segun n idiomas
				{
					
					$componer = '';
					
				for($n=0;$n<=count($this->separarFinal)-1;$n++) // Vueltas de la url de muestra
					{
					$entidadNameSpace 		= 'GestorCrudBundle:'.ucwords($this->separarFinal[$n]['entidad']);
					$entity = $em->getRepository($entidadNameSpace)->findAll();
					
					
					
					foreach($entity as $laent ){
								$des = explode('/',$this->separarFinal[$n]['ruta']);
					
									for ($j=0;$j<=count($des)-1;$j++){
									
										if($des[$j]=='locale'){
											$componer .= '/'.$this->locales[$g];}
											
										elseif(preg_match("/^-.*-$/",$des[$j]))	{
											$limpia = preg_replace('/\-/', '', $des[$j]);
											$componer .= '/'.$limpia;}
											
										else{
											if($g==0)
											{
												$trozo = 'get'.ucwords($des[$j]);
												$slug=$elslug->crearSlug($laent->$trozo());
												$componer .= '/'.$slug;	}
											else{
												$entity2 = $em->getRepository($entidadNameSpace)->findById($laent->getId());
												$repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
												$componer2 ='';	
												foreach($entity2 as $enr){}
												$translations = $repository->findTranslations($enr);
												
												if(count($translations)>=1){			
														foreach($translations as $tt=>$val){
														
															if($this->locales[$g]==$tt){
																foreach ($translations[$tt] as $ttt=>$val){
																	
																		if($des[$j]!='id'){
																			
																			if($ttt==$des[$j]){
																				$slug=$elslug->crearSlug($translations[$tt][$des[$j]]);
																				$componer2 .= '/'.$slug; 
																				
																				
																				}
																			
																		}
																		else{
																			$elid='';
																			$id=$laent->getId();
																			$elid .= '/'.$id;	
																			}
																
																	}
																	
																}
															
															}
														
														$componer .= $componer2.$elid;		
														$elid='';		
												}// if contar trans 
												else{$componer='';}
											}
											}
										
								}	
								if($componer!=''){
								$loslinks[$l]=$componer;
								$componer='';
								$l++;
								}
							
							}
					
					}
				}	
		}	
	

	return $loslinks;
	}

	public function setSolicitud()
    {
        $this->solicitud 	= $this->container->getParameter('elsitemap');
        return $this;
    }



	public function setLocales()
    {
        $this->locales 	= explode(',',$this->container->getParameter('idiomas_crud'));
        return $this;
    }
	
}
