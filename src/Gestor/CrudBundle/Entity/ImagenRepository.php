<?php

namespace Gestor\CrudBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ImagenRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImagenRepository extends EntityRepository
{
	
	
    public function findByContarFotosFaena($id)
    {
     //   return $this->getEntityManager()
     //           ->createQuery('SELECT i FROM ClarorFeinaBundle:Imagen i WHERE i.faena=:idfaena ORDER BY i.orden')->setParameter('idfaena',$id);
    }

    public function findByTodasLasFotos($id,$entidad)
    {
        return $this->getEntityManager()
                ->createQuery('SELECT i FROM GestorCrudBundle:Imagen i WHERE i.'.$entidad.'=:identidad ORDER BY i.orden')
                ->setParameter('identidad',$id)
                ->getResult();
    }


}
