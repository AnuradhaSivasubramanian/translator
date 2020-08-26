<?php

namespace App\Controller;

use App\Entity\TranslationKey;
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

    /**
     * 
     *@Route("/keys/modify/{id_key}", name="modify_key")
     * 
     */
    public function modifyEntry(Request $request, $id_key)
    {
        $data = [];

        //Find the entry to be modified
        $key_entry = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->find($id_key);

        $data['mode'] = 'modify';

        $form = $this->createFormBuilder()
            ->add('textkey')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $form_data = $form->getData();
            $entitymanager = $this->getDoctrine()->getManager();
            $key_entry->setTextKey($form_data['textkey']);

            $entitymanager->persist($key_entry);
            $entitymanager->flush();

            return $this->redirectToRoute('index_keys');
        } else {

            $key_data['id'] = $key_entry->getId();
            $key_data['text_key'] = $key_entry->getTextKey();
            $data['formdata'] = $key_data;
        }
        return $this->render('key/form.html.twig', $data);
    }

    /**
     * 
     *@Route("/keys/new", name="new_key")
     * 
     */
    public function addEntry(Request $request)
    {
        $data['formdata'] = [];
        $data['mode'] = 'new';

        $form = $this->createFormBuilder()
            ->add('text_key')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form_data = $form->getData();
            $data['formdata'] = [];
            $data['formdata'] = $form_data;

            $entitymanager = $this->getDoctrine()->getManager();
            $message = new TranslationKey;
            $message->setTextKey($form_data['text_key']);

            $entitymanager->persist($message);
            $entitymanager->flush();

            return $this->redirectToRoute('index_keys');
        }
        return $this->render('key/form.html.twig', $data);
    }
}