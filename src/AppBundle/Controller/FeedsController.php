<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Folder;

class FeedsController extends Controller
{
    /**
     * @Route("/{_locale}/feeds/add-feed-form", name="feedsAddFeedForm")
     */
    public function getAddFeedForm(Request $request)
    {
        $translator = $this->get('translator');

        $queryResult = $this->getDoctrine()
            ->getRepository('AppBundle:Folder')
            ->findBy(array(), array('folderName' => 'ASC'));

        $folderDropdown = array(
            '0' => $translator->trans('all_feeds')
        );

        foreach ($queryResult as $result) {
            $folderDropdown[$result->getId()] = $result->getFolderName();
        }

        $date = new \DateTime("now");

        $feed = new Feed();
        $feed->setDateModified($date);
        $feed->setDateCreated($date);

        $form = $this->createFormBuilder($feed)
            ->setAction($this->generateUrl('feedsAddFeedForm'), array('locale' => $request->getLocale()))
            ->setMethod('POST')
            ->add('feed_name', TextType::class, array('label' => $translator->trans('add_new_feed_name')))
            ->add('feed_url', UrlType::class, array('label' => $translator->trans('add_new_feed_url')))
            ->add('folder', ChoiceType::class, array('label' => $translator->trans('add_folder_name'), 'choices' => $folderDropdown))
            ->add('save', SubmitType::class, array('label' => $translator->trans('add_new_feed')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feed);
            $em->flush();

            return $this->redirectToRoute('home');
        }


        return $this->render('popups/add-feed.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
