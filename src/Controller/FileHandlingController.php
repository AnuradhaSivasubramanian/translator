<?php


namespace App\Controller;


use App\Entity\TranslationKey;
use App\Entity\TranslationMessage;
use App\Form\DownloadType;
use App\Form\UploadType;
use App\Service\CsvFileToArray;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FileHandlingController extends AbstractController
{
    /**
     *
     * @Route("/keys/filehandle", name="file_handle")
     * @param Request $uploadRequest
     * @param Request $downloadRequest
     * @param CsvFileToArray $csvFileToArray
     * @return Response
     */
    public function file_handle(Request $uploadRequest,Request $downloadRequest, CsvFileToArray $csvFileToArray)
    {
        $uploadForm = $this->createForm(UploadType::class);
        $uploadForm->handleRequest($uploadRequest);
        $downloadForm = $this->createForm(DownloadType::class);
        $downloadForm->handleRequest($downloadRequest);


        if ($uploadForm->isSubmitted()) {

            $csv_data_nl = [];
            $csv_data_en = [];
            $fileNL = $uploadForm['nl_file']->getData();
            $fileEN = $uploadForm['en_file']->getData();

            if($fileNL !== null){
                $csv_data_nl = $csvFileToArray->convertCsvToArray($fileNL);
            }
            if($fileEN !== null){
                $csv_data_en = $csvFileToArray->convertCsvToArray($fileEN);
            }

            if($error_in_nl_file = array_key_exists("error_message",$csv_data_nl) or array_key_exists("error_message", $csv_data_en) ){
                if($error_in_nl_file){
                    $error = new FormError($csv_data_nl['error_message']);
                    $uploadForm->get('nl_file')->addError($error);
                } else {
                    $error = new FormError($csv_data_en['error_message']);
                    $uploadForm->get('en_file')->addError($error);
                }

            } else {
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
            }

        }

        if($downloadForm->isSubmitted()){
            $result_array = [];
            $translation_keys = $this->getDoctrine()
                ->getRepository('App:TranslationKey')
                ->findAll();
            $language = $downloadForm['chose_file']->getData();
            foreach($translation_keys as $translation_key){
                $translation_messages = $translation_key->getTranslationMessages();
                foreach($translation_messages as $translation_message){
                    if($translation_message->getLanguage() === $language and $translation_message->getMessage() !== ''){
                        $result_array[$translation_key->getTextKey()]  = $translation_message->getMessage();
                    }
                }
            }
            $language .= $language === 'nl' ? "_nl" : "_gb";
            $filename = "messages.".$language.".php";
            $response = new Response();
            $response->setContent('<?php return ' . var_export($result_array, true) . ';');
            $response->headers->set('Content-Type', 'text/php');
            $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
            return $response;
        }
        $data['uploadForm'] = $uploadForm->createView();
        $data['downloadForm'] = $downloadForm->createView();
        return $this->render('key/upload.html.twig', $data);
    }

}