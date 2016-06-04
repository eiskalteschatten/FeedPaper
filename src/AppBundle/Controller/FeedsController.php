<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            ->add('feed_url', UrlType::class, array('label' => $translator->trans('add_new_feed_url')))
            ->add('folder', ChoiceType::class, array('label' => $translator->trans('add_folder_name'), 'choices' => $folderDropdown))
            ->add('save', SubmitType::class, array('label' => $translator->trans('add_new_feed'), 'disabled' => true))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $feed->getFeedUrl();

            $feed->setFeedName($this->getFeedName($url));
            $feed->setIconUrl($this->getFeedIcon($url));

            $em = $this->getDoctrine()->getManager();
            $em->persist($feed);
            $em->flush();

            return $this->redirectToRoute('home');
        }


        return $this->render('popups/add-feed.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{_locale}/feeds/validate-feed", name="feedsValidateFeed")
     * @Method("POST")
     */
    public function isFeedValid(Request $request) {
        $url = $request->request->get('url');
        $urlParts = parse_url($url);

        if (!isset($urlParts['scheme'])) {
            $url = 'http://' . $url;
        }

        try {
            $content = file_get_contents($url);
            $rss = new \SimpleXmlElement($content);

            if (isset($rss->channel->item)) {
                return new Response('true');
            }
        }
        catch(\Exception $e) {
            return new Response('false');
        }

        return new Response('false');
    }

    private function getFeedName($url) {
        try {
            $content = file_get_contents($url);
            $rss = new \SimpleXmlElement($content);

            return $rss->channel->title;
        }
        catch(\Exception $e) {
            return $url;
        }
    }

    private function getFeedIcon($url) {
        try {
            $urlParts = parse_url($url);
            $baseUrl = $urlParts['scheme'] . '://' . $urlParts['host'];

            $doc = new \DOMDocument();

            libxml_use_internal_errors(true);
            $doc->strictErrorChecking = FALSE;

            $doc->loadHTML(file_get_contents($baseUrl));
            $xml = simplexml_import_dom($doc);

            $array = $xml->xpath('//link[@rel="shortcut icon"]');

            if (empty($array)) {
                $array = $xml->xpath('//link[@rel="icon"]');
            }

            if (!empty($array)) {
                $icon = $array[0]['href'];
                $iconParts = parse_url($icon);

                if (!isset($iconParts['scheme'])) {
                    $icon = $baseUrl . '/' . $icon;
                }

                return $icon;
            }
        }
        catch(\Exception $e) {
            //throw $e;
        }

        return "";
    }
}
