<?php


namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;

class CsvFileToArray extends AbstractController
{

    public function convertCsvToArray($file){


        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
        $data_array = array();
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        if($originalFilename === 'messages.nl_nl' && $file->getClientOriginalExtension() === 'csv'){
            $language = 'nl';
        } else if ($originalFilename === 'messages.en_gb' && $file->getClientOriginalExtension() === 'csv'){
            $language = 'en';
        } else {

            return $error = ['error_message' => 'Please upload a file with valid name'];
        }
        $newFilename = $originalFilename.".".$file->getClientOriginalExtension();
        $file->move($destination, $newFilename);
        ini_set('auto_detect_line_endings',TRUE);
        if (($handle = fopen($this->getParameter('kernel.project_dir').'/public/uploads/' . $newFilename, "r")) !== FALSE) {

            while (($data = fgetcsv($handle, 0, ';')) !== FALSE and sizeof($data_array) <= 35) {
                if(count($data) !== 2) {
                    return $error = ['error_message' => 'Please upload a file with consistent data'];
                }
                $data_array[] = array('key' => $data[0], $language => $data[1] );

            }
            ini_set('auto_detect_line_endings',FALSE);
            fclose($handle);
        }
        $filesystem = new Filesystem;
        $filesystem->remove($this->getParameter('kernel.project_dir').'/public/uploads/' . $newFilename);
       return $data_array;
    }


}