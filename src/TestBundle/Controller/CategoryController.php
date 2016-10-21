<?php

namespace TestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use TestBundle\Entity\Category;
use TestBundle\Form\CategoryType;

use Pagerfanta\Adapter\DoctrineCollectionAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    public function categoryAction(Request $request, $name)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TestBundle:Category')->findOneByName($name);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $adapter = new DoctrineCollectionAdapter($entity->getProducts());
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(2);
        $pagerfanta->setCurrentPage($request->get('page')?:1);

        return $this->render('TestBundle:Category:index.html.twig', array(
            'category'      => $entity,
            'entities'    => $em->getRepository('TestBundle:Category')->findAll(),
            'products'    => $pagerfanta->getCurrentPageResults(),
            'pagerfanta'  => $pagerfanta,
        ));
    }

    /**
     * Lists all Category entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TestBundle:Category')->findAll();

        return $this->render('TestBundle:Category:index.html.twig', array(
            'entities' => $entities,
        ));
    }
}
