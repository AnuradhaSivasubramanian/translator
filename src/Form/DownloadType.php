<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


class DownloadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add(
            'chose_file',
            ChoiceType::class, [
                'choices'  => [
                    'Nederlands' => 'nl',
                    'Engels' => 'en',

                ]]
        )->add('download', SubmitType::class);
    }
}