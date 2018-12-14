<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class CompanyController extends Controller
{
    /**
     * @param CompanyRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/company", name="company_list")
     */
    public function companiesList(CompanyRepository $repo)
    {
        $companies = $repo->findAll();

        return $this->render('company/company_list.html.twig', [
            'companies' => $companies
        ]);
    }
}
