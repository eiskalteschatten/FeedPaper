<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Folder;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $queryResult = $this->getDoctrine()
            ->getRepository('AppBundle:Folder')
            ->findBy(array(), array('folderName' => 'ASC'));

        $folders = array();

        foreach ($queryResult as $result) {
            $folders[] = array(
                'id' => $result->getId(),
                'name' => $result->getFolderName()
            );
        }

        return $this->render('default/index.html.twig', array('folders' => $folders));
    }
}
