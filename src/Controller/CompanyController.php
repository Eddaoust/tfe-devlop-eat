<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyCategoryRepository;
use App\Repository\CompanyRepository;
use App\Repository\ProjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;



class CompanyController extends Controller
{
    /**
     * @param CompanyRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/log/company", name="company_list")
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
    public function addCompany(Request $request, ObjectManager $manager, CompanyCategoryRepository $repo)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            //TODO Pas d'enregistrement, probleme de nom d'input en JS
            foreach ($company->getShareholders() as $shareholder)
            {
                $shareholder->setCompany($company);
                $manager->persist($shareholder);
            }

            $this->addFlash('success', 'Société ajouté avec succès');
            $manager->persist($company);
            $manager->flush();

            return $this->redirectToRoute('company_list');
        }

        return $this->render('company/company_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Company $company
     * @return \Symfony\Component\HttpFoundation\Response
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
     * @Route("/admin/company/delete/{id}", name="company_delete")
     * @ParamConverter("company", options={"exclude": {"manager"}})
     */
    public function deleteCompany(Company $company, ObjectManager $manager)
    {
        foreach ($company->getShareholders() as $shareholder)
        {
            $manager->remove($shareholder);
        }
        $manager->remove($company);
        $manager->flush();

        $this->addFlash('success', 'Société supprimé avec succès');
        return $this->redirectToRoute('company_list');
    }

    /**
     * @param Company $company
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/company/update/{id}", name="company_update")
     * @ParamConverter("company", options={"exclude": {"request","manager"}})
     */
    public function updateCompany(Company $company, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach ($company->getShareholders() as $shareholder)
            {
                if (!$shareholder->getPart())
                {
                    $manager->remove($shareholder);
                }
                else
                {
                    $shareholder->setCompany($company);
                    $manager->persist($shareholder);
                }
            }

            $manager->persist($company);
            $manager->flush();

            $this->addFlash('success', 'Société modifié avec succès');
            return $this->render('company/company_one.html.twig', [
                'company' => $company
            ]);
        }

        return $this->render('company/company_update.html.twig', [
            'form' => $form->createView(),
            'company' => $company
        ]);
    }

    /**
     * @param $countryId
     * @param CompanyCategoryRepository $repo
     * @return JsonResponse
     * @Route("/admin/company/category/ajax/{countryId}", name="company_category_ajax", methods={"GET"})
     */
    public function getCompanyCategory($countryId, CompanyCategoryRepository $repo)
    {
        $compCat = $repo->getCompanyCat($countryId);

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($compCat, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param CompanyRepository $repo
     * @return JsonResponse
     * @Route("/admin/company/get-projects/ajax", name="company_project_name_ajax", methods={"GET"})
     */
    public function getCompanyProjectName(ProjectRepository $repo)
    {
        $companyProjects = $repo->getCompanyProjectName();

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($companyProjects, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param CompanyRepository $repo
     * @return JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     * @Route("/admin/company/dashboard/ajax", name="company_by_country_ajax", methods={"GET"})
     */
    public function getCompanyByCountry(CompanyRepository $repo)
    {
        $companyCount = $repo->getCompanyByCountry();

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($companyCount, 'json');

        return new JsonResponse($data, 200, [], true);
    }
}
