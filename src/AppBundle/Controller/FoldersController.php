<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Folder;

class FoldersController extends Controller
{
    /**
     * @Route("/{_locale}/folders/add-folder-form", name="foldersAddFolderForm")
     */
    public function getAddFeedForm(Request $request)
    {
        $date = new \DateTime("now");

        $folder = new Folder();
        $folder->setDateModified($date);
        $folder->setDateCreated($date);

        $form = $this->createFormBuilder($folder)
            ->setAction($this->generateUrl('foldersAddFolderForm'), array('locale' => $request->getLocale()))
            ->setMethod('POST')
            ->add('folder_name', TextType::class, array('label' => $this->get('translator')->trans('add_folder_name')))
            ->add('save', SubmitType::class, array('label' => $this->get('translator')->trans('add_folder')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($folder);
            $em->flush();

            return $this->redirectToRoute('home');
        }


        return $this->render('popups/add-folder.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
