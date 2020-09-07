<?php


namespace App\Controller;

use App\Entity\Domain;
use App\Entity\TranslationKey;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DomainsController extends AbstractController
{
    /**
     * @Route ("/domains", name="index_domains")
     */
    public function showDomains()
    {
        $domains = $this->getDoctrine()
            ->getRepository('App:Domain')
            ->findAll();

        $data['domains'] = $domains;
        return $this->render('key/domain/index.html.twig', $data);
    }

    /**
     * @Route ("/domains/view/{id_domain}", name="view_domain")
     * @param $id_domain
     * @return Response
     */
    public function showDomainDetail(int $id_domain)
    {
        //Find the selected domain
        $domain_entry = $this->getDoctrine()
            ->getRepository('App:Domain')
            ->find($id_domain);

        $data['formdata'] = $domain_entry;
        return $this->render('key/domain/view.html.twig', $data);
    }

    /**
     * @Route ("/domains/edit/{domain}", name="edit_domain")
     * @param Request $request
     * @param Domain $domain
     * @return RedirectResponse|Response
     */
    public function editDomain(Request $request,Domain $domain){
        $data = [];
        $data['mode'] = 'modify';
        $form = $this->createFormBuilder($domain)
            ->add('domain_name')
            ->add('submit',
                SubmitType::class
            )
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($domain);
            $entitymanager->flush();
            return $this->redirectToRoute('index_domains');
        } else {
            $data['formdata'] = $form->createView();
        }

        return $this->render('key/domain/form.html.twig', $data);
    }

    /**
     * @Route ("/domains/new", name="new_domain")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newDomain(Request $request){
        $data = [];
        $data['mode'] = 'new';
        $domain = new Domain;
        $form = $this->createFormBuilder($domain)
            ->add('domain_name')
            ->add('submit',
                SubmitType::class
            )
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->persist($domain);
            $entitymanager->flush();
            return $this->redirectToRoute('index_domains');
        } else {
            $data['formdata'] = $form->createView();
        }

        return $this->render('key/domain/form.html.twig', $data);
    }

    /**
     *
     * @Route("/domains/delete/{domain}", name="delete_domain")
     * @param Request $request
     * @param Domain $domain
     * @return Response
     */
    public function deleteDomian(Request $request, Domain $domain)
    {
        $findKey = $this->getDoctrine()
            ->getRepository(TranslationKey::class)
            ->findDomain($domain->getDomainName());

        $data['keys_found'] = $findKey;
        $form = $this->createFormBuilder($domain)
            ->add('domain_name')
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $entitymanager = $this->getDoctrine()->getManager();
            $entitymanager->remove($domain);
            $entitymanager->flush();
            return $this->redirectToRoute('index_domains');
        } else {
            $data['formdata'] = $form->createView();
        }
        return $this->render('key/domain/delete.html.twig', $data);
    }


}