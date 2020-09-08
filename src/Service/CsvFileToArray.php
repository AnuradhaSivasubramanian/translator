<?php


namespace App\Service;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CsvFileToArray extends AbstractController
{

    public function convertCsvToArray($file){

        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
        $data_array = array();
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$file->getClientOriginalExtension();
        $file->move($destination, $newFilename);
        ini_set('auto_detect_line_endings',TRUE);
        if (($handle = fopen($this->getParameter('kernel.project_dir').'/public/uploads/' . $newFilename, "r")) !== FALSE) {
            $isFirstEntry = true;
            $language = '';
            while (($data = fgetcsv($handle)) !== FALSE and sizeof($data_array) <= 35) {
                if($isFirstEntry === true){
                    if(substr($data[0], strpos($data[0] , ';') +1) === 'UK'){
                        $language = 'en';
                    }
                    else {
                        $language = 'nl';
                    }
                    $isFirstEntry = false;
                }
                $data_array[] = array('key' => substr($data[0], 0, strpos($data[0], ';')), $language => substr($data[0], strpos($data[0] , ';') +1) );

            }
            ini_set('auto_detect_line_endings',FALSE);
            fclose($handle);
        }

        array_shift($data_array);

        return $data_array;
    }


}