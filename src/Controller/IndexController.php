<?php

namespace App\Controller;

use App\Entity\TranslationKey;
use App\Entity\TranslationMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index_keys")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(Request $request)
    {
        $data = [];
        $data['search'] = false;

        //find all translation keys
        $translation_keys = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->findAll();
        $data['translation_keys'] = $translation_keys;
        $form = $this->createFormBuilder()
            ->add('search_value')
            ->add('submit', SubmitType::class)
            ->getForm();


        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data['search'] = true;
            $searchdata = $form->getData();
            $result_keys = $this->getDoctrine()
                ->getRepository(TranslationKey::class)
                ->findKeyByTextKey($searchdata['search_value']);

            $data['formdata'] = $form->createView();
            $data['result_keys'] = $result_keys;
            $data['translation_keys'] = $result_keys;
            return $this->render('key/index.html.twig', $data);
        }
        else {
            $data['formdata'] = $form->createView();
        }

        return $this->render('key/index.html.twig',  $data);
    }
}