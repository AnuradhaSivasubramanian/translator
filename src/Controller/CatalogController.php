<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

    private $translation_key = [
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

        return $this->render('catalog/view.html.twig', ['id' => $id_catalog]);
    }


    /**
     * 
     *@Route("/catalog/modify/{id_catalog}", name="modify_entry")
     * 
     */
    public function modifyEntry(Request $request, $id_catalog)
    {
        $data = [];
        foreach ($this->translation_key as $key) {
            if ($key['id'] === $this->catalog[$id_catalog - 1]['key_id']) {
                $data['key'] = $key['key'];
            }
        }
        $data['formdata'] = $this->catalog[$id_catalog - 1];
        $data['mode'] = ['modify'];
        $data['languages'] = ['nl', 'en'];

        return $this->render('catalog/form.html.twig', $data);
    }



    /**
     * 
     *@Route("/catalog/new", name="new_entry")
     * 
     */
    public function addEntry(Request $request)
    {
        return $this->render('catalog/form.html.twig', ['mode' => 'new']);
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