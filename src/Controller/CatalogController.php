<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TranslationMessage;

class CatalogController extends AbstractController
{

    /**  
     *
     * @Route("/catalog", name="index_catalog")
     */
    public function showCatalog()
    {
        $data = [];
        $catalog = $this->getDoctrine()
            ->getRepository('App:TranslationMessage')
            ->findAll();
        $translation_keys = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->findAll();
        $data['translation_keys'] = $translation_keys;
        $data['catalog'] = $catalog;
        return $this->render('catalog/index.html.twig',  $data);
    }

    /**
     * 
     *@Route("/catalog/view/{id_catalog}", name="view_entry")
     * 
     */
    public function showDetails(Request $request, $id_catalog)
    {


        //Find the selected entry
        $catalog_entry = $this->getDoctrine()
            ->getRepository('App:TranslationMessage')
            ->find($id_catalog);

        //Find the text for the key of the entry
        $translation_key = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->find($catalog_entry->getTranslationKeyId());

        $catalog_data['id'] = $catalog_entry->getId();
        $catalog_data['text_key'] = $translation_key->getText_key();
        $catalog_data['language'] = $catalog_entry->getLanguage();
        $catalog_data['message'] = $catalog_entry->getMessage();

        $data['formdata'] = $catalog_data;
        return $this->render('catalog/view.html.twig', $data);
    }


    /**
     * 
     *@Route("/catalog/modify/{id_catalog}", name="modify_entry")
     * 
     */
    public function modifyEntry(Request $request, $id_catalog)
    {
        $data = [];

        //Find the entry to be modified
        $catalog_entry = $this->getDoctrine()
            ->getRepository('App:TranslationMessage')
            ->find($id_catalog);

        //Find the text for the key of the entry
        $translation_key = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->find($catalog_entry->getTranslationKeyId());

        $data['mode'] = 'modify';
        $data['languages'] = ['nl', 'en'];

        $translation_keys = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->findAll();
        $data['translation_keys'] = $translation_keys;

        $form = $this->createFormBuilder()
            ->add('key')
            ->add('language')
            ->add('message')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form_data = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $catalog_entry->setTranslationKeyId($form_data['key']);
            $catalog_entry->setLanguage($form_data['language']);
            $catalog_entry->setMessage($form_data['message']);

            $em->persist($catalog_entry);
            $em->flush();

            return $this->redirectToRoute('index_catalog');
        } else {

            $catalog_data['id'] = $catalog_entry->getId();
            $catalog_data['text_key'] = $translation_key->getText_key();
            $catalog_data['language'] = $catalog_entry->getLanguage();
            $catalog_data['message'] = $catalog_entry->getMessage();

            $data['formdata'] = $catalog_data;
        }
        return $this->render('catalog/form.html.twig', $data);
    }



    /**
     * 
     *@Route("/catalog/new", name="new_entry")
     * 
     */
    public function addEntry(Request $request)
    {
        $data['formdata'] = [];
        $data['formdata']['language'] = "";
        $data['formdata']['text_key'] = "";
        $data['key'] = '';
        $data['languages'] = ['nl', 'en'];
        $data['mode'] = 'new';

        $translation_keys = $this->getDoctrine()
            ->getRepository('App:TranslationKey')
            ->findAll();
        $data['translation_keys'] = $translation_keys;


        $form = $this->createFormBuilder()
            ->add('key')
            ->add('language')
            ->add('message')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form_data = $form->getData();
            $data['formdata'] = [];
            $data['formdata'] = $form_data;

            $em = $this->getDoctrine()->getManager();
            $message = new TranslationMessage;
            $message->setTranslationKeyId($form_data['key']);
            $message->setLanguage($form_data['language']);
            $message->setMessage($form_data['message']);

            $em->persist($message);

            $em->flush();

            return $this->redirectToRoute('index_catalog');
        }
        return $this->render('catalog/form.html.twig', $data);
    }

    /**
     * 
     *@Route("/catalog/delete/{id_catalog}", name="delete_entry")
     * 
     */
    public function deleteEntry(Request $request, $id_catalog)
    {
        //Find the selected entry
        $catalog_entry = $this->getDoctrine()
            ->getRepository('App:TranslationMessage')
            ->find($id_catalog);

        $catalog_data['language'] = $catalog_entry->getLanguage();
        $catalog_data['message'] = $catalog_entry->getMessage();

        $data['formdata'] = $catalog_data;
        $form = $this->createFormBuilder()
            ->add('submit')
            ->getForm();

        $form->handleRequest($request);
        $data['submitdata'] = $form->isSubmitted();
        if ($form->isSubmitted()) {


            $em = $this->getDoctrine()->getManager();

            $em->remove($catalog_entry);

            $em->flush();

            return $this->redirectToRoute('index_catalog');
        }

        return $this->render('catalog/delete.html.twig', $data);
    }
}