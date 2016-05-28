<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $currentUrl = $request->getUri();

        if (!strstr($currentUrl, "/en/") && !strstr($currentUrl, "/de/")) {
            $prefLang = $request->getPreferredLanguage(array('de', 'en'));

            if ($prefLang == "de") {
                $urlLang = "de";
            }
            else {
                $urlLang = "en";
            }

            return $this->redirect($this->generateUrl('home', array('_locale' => $urlLang)));
        }

        return $this->render('default/index.html.twig');
    }
}
