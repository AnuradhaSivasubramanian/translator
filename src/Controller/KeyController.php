<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Entity\TranslationKey;
use App\Entity\TranslationMessage;
use App\Form\DomainsType;
use App\Form\MessageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class KeyController extends AbstractController
{

    /**
     *
     * @Route("/keys/view/{id_key}", name="view_key")
     * @param int $id_key
     * @return Response
     */
    public function showDetails(int $id_key)
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
     * @Route("/keys/modify/{translation_key}", name="modify_key")
     * @param Request $request
     * @param TranslationKey $translation_key
     * @return RedirectResponse|Response
     */
    public function modifyEntry(Request $request, TranslationKey $translation_key)
    {
        $data = [];
        $data['mode'] = 'modify';
        $domains = $this->getDoctrine()
            ->getRepository('App:Domain')
            ->findAll();

        $data['domains'] = $domains;
        $form = $this->createFormBuilder($translation_key)
            ->add('text_key')
            ->add(
                'translationmessages',
                CollectionType::class,
                array(
                    'entry_type' => MessageType::class,
                    'allow_add' => true,
                )
            )
            ->add(
                'domains',
                EntityType::class,
                ['class' => Domain::class,
                    'label' => 'domain_name',
                    'choice_label' => function ($item) {
                        return $item->getDomainName();
                    },
                    'mapped'=>true,
                    'expanded' => true,
                    'multiple'=>true,
                    'allow_extra_fields'=>true]

            )
            ->add('submit',
                SubmitType::class
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($translation_key);
            $entitymanager->flush();
            return $this->redirectToRoute('index_keys');
        } else {
            $data['formdata'] = $form->createView();
        }

        return $this->render('key/form.html.twig', $data);
    }

    /**
     *
     * @Route("/keys/new", name="new_key")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addEntry(Request $request)
    {
        $data['formdata'] = [];
        $data['mode'] = 'new';

        $translation_key = new TranslationKey;
        $translation_message_nl = new TranslationMessage;
        $translation_message_en = new TranslationMessage;

        $translation_message_nl->setLanguage('nl');
        $translation_message_nl->setMessage("");
        $translation_message_en->setLanguage('en');
        $translation_message_en->setMessage("");
        $translation_key->addTranslationMessage($translation_message_nl);
        $translation_key->addTranslationMessage($translation_message_en);

        $form = $this->createFormBuilder($translation_key)
            ->add('text_key')
            ->add(
                'translationmessages',
                CollectionType::class,
                array(
                    'entry_type' => MessageType::class,
                    'allow_add' => true,
                )
            )
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($translation_key);
            $entitymanager->persist($translation_message_en);
            $entitymanager->persist($translation_message_nl);
            $entitymanager->flush();

            return $this->redirectToRoute('index_keys');
        } else {
            $data['formdata'] = $form->createView();
        }
        return $this->render('key/form.html.twig', $data);
    }

    /**
     *
     * @Route("/keys/delete/{translation_key}", name="delete_key")
     * @param Request $request
     * @param TranslationKey $translation_key
     * @return Response
     */
    public function deleteKey(Request $request, TranslationKey $translation_key)
    {
        $form = $this->createFormBuilder($translation_key)
            ->add('text_key')
            ->add(
                'translationmessages',
                CollectionType::class,
                array(
                    'entry_type' => MessageType::class,
                    'allow_add' => true,
                )
            )
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->remove($translation_key);
            $entitymanager->flush();
            return $this->redirectToRoute('index_keys');
        } else {
            $data['formdata'] = $form->createView();
        }
        return $this->render('key/delete.html.twig', $data);
    }


}