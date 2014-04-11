<?php

namespace Gestor\FeinaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FijasController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestorFeinaBundle:Default:index_gestor.html.twig');
    }

	/**
	 * @Route("/posts", name="_demo_posts")
	 */
	public function postsAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$repository = $em->getRepository('GestorFeinaBundle:BlogPost');
		// create some posts in case if there aren't any
		if (!$repository->findOneById('hello_world')) {
			$post = new \Gestor\FeinaBundle\Entity\BlogPost();
			$post->setTitle('Hello world');

			$next = new \Gestor\FeinaBundle\Entity\BlogPost();
			$next->setTitle('Doctrine extensions');

			$em->persist($post);
			$em->persist($next);
			$em->flush();
		}
		$posts = $em
			->createQuery('SELECT p FROM GestorFeinaBundle:BlogPost p')
			->getArrayResult()
		;
		die(var_dump($posts));
	}
}
