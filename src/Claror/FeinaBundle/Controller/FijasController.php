<?php

namespace Claror\FeinaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FijasController extends Controller
{
    public function indexAction()
    {
        return $this->render('ClarorFeinaBundle:Default:index_gestor.html.twig');
    }

	/**
	 * @Route("/posts", name="_demo_posts")
	 */
	public function postsAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$repository = $em->getRepository('ClarorFeinaBundle:BlogPost');
		// create some posts in case if there aren't any
		if (!$repository->findOneById('hello_world')) {
			$post = new \Claror\FeinaBundle\Entity\BlogPost();
			$post->setTitle('Hello world');

			$next = new \Claror\FeinaBundle\Entity\BlogPost();
			$next->setTitle('Doctrine extensions');

			$em->persist($post);
			$em->persist($next);
			$em->flush();
		}
		$posts = $em
			->createQuery('SELECT p FROM ClarorFeinaBundle:BlogPost p')
			->getArrayResult()
		;
		die(var_dump($posts));
	}
}
