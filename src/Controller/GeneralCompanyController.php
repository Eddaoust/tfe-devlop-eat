<?php

namespace App\Controller;

use App\Entity\GeneralCompany;
use App\Form\GeneralCompanyType;
use App\Repository\GeneralCompanyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ProjectRepository;

class GeneralCompanyController extends Controller
{
    /**
     * @param GeneralCompanyRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/log/genCompany", name="genCompany_list")
     */
    public function listGenCompanies(GeneralCompanyRepository $repo)
    {
        $genCompanies = $repo->findAll();

        return $this->render('general_company/genCompany_list.html.twig', [
            'genCompanies' => $genCompanies
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/admin/genCompany/add", name="genCompany_add")
     */
    public function addArGenCompany(Request $request, ObjectManager $manager)
    {
        $genCompany = new GeneralCompany();

        $form = $this->createForm(GeneralCompanyType::class, $genCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($genCompany);
            $manager->flush();

            return $this->redirectToRoute('genCompany_list');
        }

        return $this->render('general_company/genCompany_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param GeneralCompany $genCompany
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/log/genCompany/{id}", name="genCompany_one")
     */
    public function oneGenCompany(GeneralCompany $genCompany)
    {
        return $this->render('general_company/genCompany_one.html.twig', [
            'genCompany' => $genCompany
        ]);
    }

    /**
     * @param GeneralCompany $genCompany
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/genCompany/delete/{id}", name="genCompany_delete")
     * @ParamConverter("genCompany", options={"exclude": {"manager"}})
     */
    public function deleteGenCompany(GeneralCompany $genCompany, ObjectManager $manager)
    {
        $manager->remove($genCompany);
        $manager->flush();

        return $this->redirectToRoute('genCompany_list');
    }

    /**
     * @param GeneralCompany $genCompany
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/genCompany/update/{id}", name="genCompany_update")
     * @ParamConverter("genCompany", options={"exclude": {"request","manager"}})
     */
    public function updateGenCompany(GeneralCompany $genCompany, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(GeneralCompanyType::class, $genCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($genCompany);
            $manager->flush();

            return $this->render('general_company/genCompany_one.html.twig', [
                'genCompany' => $genCompany
            ]);
        }

        return $this->render('general_company/genCompany_update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @return JsonResponse
     * @Route("/admin/general-company/get-projects/ajax", name="genCompany_project_name_ajax", methods={"GET"})
     */
    public function getCompaniesGenCompanyName(ProjectRepository $repo)
    {
        $genCompanyProjects = $repo->getCompaniesGenCompanyName();

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($genCompanyProjects, 'json');

        return new JsonResponse($data, 200, [], true);
    }
}
