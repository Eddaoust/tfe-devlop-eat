<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CompanyController extends Controller
{
    /**
     * @param CompanyRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/company", name="company_list")
     */
    public function listCompany(CompanyRepository $repo)
    {
        $companies = $repo->findAll();

        return $this->render('company/company_list.html.twig', [
            'companies' => $companies
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/admin/company/add", name="company_add")
     */
    public function addCompany(Request $request, ObjectManager $manager)
    {
        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($company);
            $manager->flush();

            return $this->redirectToRoute('company_list');
        }

        return $this->render('company/company_add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
