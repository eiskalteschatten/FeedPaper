<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Feed;

class AsyncController extends Controller
{
    /**
     * @Route("/{_locale}/async/add-feeds", name="asyncAddFeeds")
     */
    public function addFeedsAction(Request $request)
    {
        $date = new \DateTime("now");

        $feed = new Feed();
        $feed->setDateModified($date);
        $feed->setDateCreated($date);

        $form = $this->createFormBuilder($feed)
            ->add('feed_name', TextType::class, array('label' => $this->get('translator')->trans('add_new_feed_name')))
            ->add('feed_url', TextType::class, array('label' => $this->get('translator')->trans('add_new_feed_url')))
            ->add('save', SubmitType::class, array('label' => $this->get('translator')->trans('add_new_feed')))
            ->getForm();

        return $this->render('async/add-feed.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
