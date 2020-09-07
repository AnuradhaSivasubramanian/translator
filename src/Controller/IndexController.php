<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Entity\TranslationKey;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('search_value',
                null,
                [ 'required'   => false])
            ->add('filter_domain',
                EntityType::class,
                ['class' => Domain::class,
                'label' => 'domain_name',
                'choice_label' => function ($item) {
                    return $item->getDomainName();
                },
                'mapped' => true,
                'required' => false,
                'allow_extra_fields'=>true])
            ->add(
                'empty_keys',
                CheckboxType::class, [
                    'label'    => 'Show Keys with empty messages',
                    'required' => false,]
            )
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);


        if($form->isSubmitted()){

            $data['search'] = true;
            $searchdata = $form->getData();

            if($searchdata['empty_keys'] === true){
                if($searchdata['filter_domain'] === null){
                    $result_keys = $this->getDoctrine()
                        ->getRepository(TranslationKey::class)
                        ->findKeysWithEmptyMessages();
                    $data['translation_keys'] = $result_keys;
                } else {
                    $result_keys = $this->getDoctrine()
                        ->getRepository(TranslationKey::class)
                        ->findKeysInADomainWithEmptyMessages($searchdata['filter_domain']->getDomainName());
                    $data['translation_keys'] = $result_keys;
                }
            } else {
                if($searchdata['search_value'] !== null and $searchdata['filter_domain'] !== null) {
                    $result_keys = $this->getDoctrine()
                        ->getRepository(TranslationKey::class)
                        ->FindKeyByValueInADomain($searchdata['search_value'],$searchdata['filter_domain']->getDomainName());
                    $data['translation_keys'] = $result_keys;
                } else if($searchdata['filter_domain'] !== null){
                     $result_keys = $this->getDoctrine()
                        ->getRepository(TranslationKey::class)
                        ->findDomain($searchdata['filter_domain']->getDomainName());
                     $data['translation_keys'] = $result_keys;
                } else if($searchdata['search_value'] !== null) {
                        $result_keys = $this->getDoctrine()
                        ->getRepository(TranslationKey::class)
                        ->findKeysByValue($searchdata['search_value']);
                     $data['translation_keys'] = $result_keys;
                }
                }

            $data['formdata'] = $form->createView();
            return $this->render('key/index.html.twig', $data);
        }
        else {
            $data['formdata'] = $form->createView();
        }

        return $this->render('key/index.html.twig',  $data);
    }
}