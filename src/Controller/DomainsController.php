<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}