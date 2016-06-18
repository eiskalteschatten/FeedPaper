<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Folder;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $feedsResult = $this->getDoctrine()
            ->getRepository('AppBundle:Feed')
            ->findBy(array(), array('feedName' => 'ASC'));

        $feeds = array();

        foreach ($feedsResult as $result) {
            $feeds[] = array(
                'id' => $result->getId(),
                'name' => $result->getFeedName(),
                'icon' => $result->getIconUrl(),
                'folder' => $result->getFolder()
            );
        }

        $foldersResult = $this->getDoctrine()
            ->getRepository('AppBundle:Folder')
            ->findBy(array(), array('folderName' => 'ASC'));

        $folders = array();

        foreach ($foldersResult as $result) {
            $id = $result->getId();
            $feedsInFolder = array();

            foreach ($feeds as $feed) {
                if ($feed['folder'] == $id) {
                    $feedsInFolder[] = $feed;
                }
            }

            $folders[] = array(
                'id' => $id,
                'name' => $result->getFolderName(),
                'feeds' => $feedsInFolder
            );
        }

        $getFeedPosts = $this->get('app.services.getFeedPosts');
        $posts = $getFeedPosts->getAllPosts();

        return $this->render('default/index.html.twig', array('folders' => $folders, 'posts' => $posts));
    }
}
