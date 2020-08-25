<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class KeyController extends AbstractController
{

    /**  
     *
     * @Route("/keys", name="index_keys")
     */
    public function showKeys()
    {
        $data = [];

        //find all translation keys    
        $translation_keys = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->findAll();

        $data['translation_keys'] = $translation_keys;

        return $this->render('key/index.html.twig',  $data);
    }

    /**
     * 
     *@Route("/keys/view/{id_key}", name="view_key")
     * 
     */
    public function showDetails(Request $request, $id_key)
    {


        //Find the selected key
        $key_entry = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->find($id_key);


        $data['formdata'] = $key_entry;
        return $this->render('key/view.html.twig', $data);
    }
}