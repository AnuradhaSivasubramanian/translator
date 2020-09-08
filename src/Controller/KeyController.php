<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Entity\TranslationKey;
use App\Entity\TranslationMessage;
use App\Form\MessageType;
use App\Service\CsvFileToArray;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        $form = $this->createFormBuilder($translation_key)
            ->add('text_key')
            ->add(
                'translationmessages',
                CollectionType::class,
                ['entry_type' => MessageType::class,
                  'allow_add' => true,
                   ]
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
                [
                    'entry_type' => MessageType::class,
                    'allow_add' => true,
                    ]
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

    /**
     *
     * @Route("/keys/uploadcsv", name="upload_csv")
     * @param Request $request
     * @return Response
     */
    public function uploadCsv(Request $request, CsvFileToArray $csvFileToArray)
    {
        $form = $this->createFormBuilder()
            ->add('nl_file', FileType::class,
                ['required' => false])
            ->add('en_file', FileType::class,
                ['required' => false])
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);



        if ($form->isSubmitted()) {

            $csv_data_nl = [];
            $csv_data_en = [];
            $fileNL = $form['nl_file']->getData();
            $fileEN = $form['en_file']->getData();
               if($fileNL !== null){
                   $csv_data_nl = $csvFileToArray->convertCsvToArray($fileNL);
               }
            if($fileEN !== null){
                $csv_data_en = $csvFileToArray->convertCsvToArray($fileEN);
            }

            $array1 = array_column($csv_data_en, null, 'key');
            $array2 = array_column($csv_data_nl, null, 'key');

            $csv_data = array_values(array_replace_recursive($array1, $array2));

            $translation_keys = $this->getDoctrine()
                ->getRepository('App:TranslationKey')
                ->findAll();

            foreach($csv_data as $message) {
               $isNewKey = true;
               $entityManager = $this->getDoctrine()->getManager();
               foreach($translation_keys  as $key){
                   if($key->getTextKey() === $message['key'] ){
                    $translation_messages = $key->getTranslationMessages();
                    foreach($translation_messages as $translation_message){
                        if(array_key_exists('nl', $message) and $translation_message->getLanguage() === 'nl'){
                            $translation_message->setMessage($message['nl']);
                        }
                        if($translation_message->getLanguage() === 'en' and array_key_exists('en', $message)){
                            $translation_message->setMessage($message['en']);
                        }
                        $key->addTranslationMessage($translation_message);
                    }

                    $key_to_update = $key;
                    $entityManager->persist($key_to_update);
                    $isNewKey = false;
                   }
               }

               if($isNewKey === true){
                   $key = new TranslationKey;
                   $key->setTextKey($message['key']);
                   $translation_message_nl = new TranslationMessage;
                   $translation_message_en = new TranslationMessage;
                   $translation_message_en->setLanguage('en');
                   $translation_message_nl->setLanguage('nl');
                    if(array_key_exists('en', $message)) {
                       $translation_message_en->setMessage($message['en']);
                    } else {
                        $translation_message_en->setMessage("");
                    }
                    if(array_key_exists('nl', $message)){
                        $translation_message_nl->setMessage($message['nl']);
                    }else {
                        $translation_message_nl->setMessage("");
                    }
                   $key->addTranslationMessage($translation_message_en);
                   $key->addTranslationMessage($translation_message_nl);
                   $key_to_update = $key;
                   $entityManager->persist($key_to_update);
                   $entityManager->persist($translation_message_en);
                   $entityManager->persist($translation_message_nl);


               }
               $entityManager->flush();

            }

           return $this->redirectToRoute('index_keys');
        } else {
            $data['formdata'] = $form->createView();
        }
        return $this->render('key/upload.html.twig', $data);
    }




}