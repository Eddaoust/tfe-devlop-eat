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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GeneralCompanyController extends Controller
{
    /**
     * @param GeneralCompanyRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_USER")
     * @Route("/log/genCompany", name="genCompany_list")
     */
    public function listGenCompanies(GeneralCompanyRepository $repo)
    {
        $genCompanies = $repo->findBy(['deleted' => false]);

        return $this->render('general_company/genCompany_list.html.twig', [
            'genCompanies' => $genCompanies
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/genCompany/add", name="genCompany_add")
     */
    public function addGenCompany(Request $request, ObjectManager $manager)
    {
        $genCompany = new GeneralCompany();

        $form = $this->createForm(GeneralCompanyType::class, $genCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genCompany->setDeleted(false);
            $manager->persist($genCompany);
            $manager->flush();

            $this->addFlash('success', 'Entreprise générale ajouté avec succès');
            return $this->redirectToRoute('genCompany_list');
        }

        return $this->render('general_company/genCompany_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param GeneralCompany $genCompany
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/genCompany/delete/{id}", name="genCompany_delete")
     * @ParamConverter("genCompany", options={"exclude": {"manager"}})
     */
    public function deleteGenCompany(GeneralCompany $genCompany, ObjectManager $manager)
    {
        $genCompany->setDeleted(true);
        $manager->persist($genCompany);
        $manager->flush();

        $this->addFlash('success', 'Entreprise générale supprimé avec succès');
        return $this->redirectToRoute('genCompany_list');
    }

    /**
     * @param GeneralCompany $genCompany
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/genCompany/update/{id}", name="genCompany_update")
     * @ParamConverter("genCompany", options={"exclude": {"request","manager"}})
     */
    public function updateGenCompany(GeneralCompany $genCompany, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(GeneralCompanyType::class, $genCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($genCompany);
            $manager->flush();

            $this->addFlash('success', 'Entreprise générale modifié avec succès');
            return $this->render('general_company/genCompany_one.html.twig', [
                'genCompany' => $genCompany
            ]);
        }

        return $this->render('general_company/genCompany_update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
