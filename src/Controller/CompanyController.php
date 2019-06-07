<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyCategoryRepository;
use App\Repository\CompanyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CompanyController extends Controller
{
    /**
     * @param CompanyRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_USER")
     * @Route("/log/company", name="company_list")
     */
    public function listCompany(CompanyRepository $repo)
    {
        $companies = $repo->findBy(['deleted' => false]);

        return $this->render('company/company_list.html.twig', [
            'companies' => $companies
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/company/add", name="company_add")
     */
    public function addCompany(Request $request, ObjectManager $manager, CompanyCategoryRepository $repo)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Société ajouté avec succès');
            $company->setDeleted(false);
            $manager->persist($company);
            $manager->flush();

            return $this->redirectToRoute('company_one', [
                'id' => $company->getId()
            ]);
        }
        return $this->render('company/company_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_USER")
     * @Route("/log/company/{id}", name="company_one")
     */
    public function oneCompany(Company $company)
    {
        return $this->render('company/company_one.html.twig', [
            'company' => $company
        ]);
    }

    /**
     * @param Company $company
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/company/delete/{id}", name="company_delete")
     * @ParamConverter("company", options={"exclude": {"manager"}})
     */
    public function deleteCompany(Company $company, ObjectManager $manager)
    {
        $company->setDeleted(true);
        $manager->persist($company);
        $manager->flush();

        $this->addFlash('success', 'Société supprimé avec succès');
        return $this->redirectToRoute('company_list');
    }

    /**
     * @param Company $company
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/company/update/{id}", name="company_update")
     * @ParamConverter("company", options={"exclude": {"request","manager"}})
     */
    public function updateCompany(Company $company, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($company->getShareholders() as $shareholder) {
                if (is_null($shareholder->getPart())) {
                    $company->removeShareholder($shareholder);
                    $manager->remove($shareholder);
                }
            }
            $manager->persist($company);
            $manager->flush();

            $this->addFlash('success', 'Société modifié avec succès');
            return $this->redirectToRoute('company_one', [
                'id' => $company->getId()
            ]);
        }
        return $this->render('company/company_update.html.twig', [
            'form' => $form->createView(),
            'company' => $company
        ]);
    }
}
