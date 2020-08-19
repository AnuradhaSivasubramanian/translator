<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TranslationMessage;

class CatalogController extends AbstractController
{

    private $catalog = [
        [
            'id' => 1,
            'key_id' => 1,
            'language' => 'nl',
            'message' => 'Hallo'
        ],
        [
            'id' => 2,
            'key_id' => 1,
            'language' => 'en',
            'message' => 'Hello'
        ],
        [
            'id' => 3,
            'key_id' => 2,
            'language' => 'nl',
            'message' => 'Tot Ziens'
        ],
        [
            'id' => 4,
            'key_id' => 2,
            'language' => 'en',
            'message' => 'See you!'
        ]
    ];

    private $translation_keys = [
        [
            'id' => 1,
            'key' => 'Hi'
        ],
        [
            'id' => 2,
            'key' => 'Bye'
        ],

    ];

    /**  
     *
     * @Route("/catalog", name="index_catalog")
     */
    public function showCatalog()


    {
        $data = $this->catalog;
        return $this->render('catalog/index.html.twig', ['catalog' => $data]);
    }

    /**
     * 
     *@Route("/catalog/view/{id_catalog}", name="view_entry")
     * 
     */
    public function showDetails(Request $request, $id_catalog)
    {
        foreach ($this->translation_keys as $key) {
            if ($key['id'] === $this->catalog[$id_catalog - 1]['key_id']) {
                $data['key'] = $key['key'];
            }
        }
        $data['formdata'] = $this->catalog[$id_catalog - 1];
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
        foreach ($this->translation_keys as $key) {
            if ($key['id'] === $this->catalog[$id_catalog - 1]['key_id']) {
                $data['key'] = $key['key'];
            }
        }

        $data['mode'] = 'modify';
        $data['languages'] = ['nl', 'en'];
        $data['translation_keys'] = $this->translation_keys;

        $form = $this->createFormBuilder()
            ->add('key')
            ->add('language')
            ->add('message')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data['formdata'] = $form->getData();
        } else {
            $data['formdata'] = $this->catalog[$id_catalog - 1];
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
        $data['key'] = '';
        $data['languages'] = ['nl', 'en'];
        $data['translation_keys'] = $this->translation_keys;
        $data['mode'] = 'new';

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
        }
        return $this->render('catalog/form.html.twig', $data);
    }

    /**
     * 
     *@Route("/catalog/delete", name="delete_entry")
     * 
     */
    public function deleteEntry()
    {
    }
}