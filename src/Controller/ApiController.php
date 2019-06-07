<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ProjectRepository;
use App\Repository\CompanyCategoryRepository;
use App\Repository\CompanyRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends Controller
{
    /**
     * @Route("/api/project/stat/{id}", name="internal_api_project_stat", methods={"GET"})
     */
    public function getProjectStat($id, ProjectRepository $repo)
    {
        $state = $repo->getProjectState($id);
        $step = $repo->getProjectStep($id);

        $project = [
            'lots' => array_shift($step[0]),
            'state' => $state,
            'step' => $step[0]
        ];

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($project, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param                           $countryId
     * @param CompanyCategoryRepository $repo
     * @return JsonResponse
     * @IsGranted("ROLE_USER")
     * @Route("/log/api/company/category/{countryId}", name="api_company_category", methods={"GET"})
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
     * @IsGranted("ROLE_USER")
     * @Route("/log/api/company/get-projects", name="api_company_project_name_ajax", methods={"GET"})
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
     * @return JsonResponse
     * @IsGranted("ROLE_USER")
     * @Route("/log/api/archi-projects", name="api_archi_projects", methods={"GET"})
     */
    public function getArchitectProjectName(ProjectRepository $repo)
    {
        $architectProjects = $repo->getArchitectCompanyName();

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($architectProjects, 'json');

        return new JsonResponse($data, 200, [], true);
    }


    /**
     * @return JsonResponse
     * @IsGranted("ROLE_USER")
     * @Route("/log/api/genComp-projects", name="api_genComp_projects", methods={"GET"})
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

    /////////////////// DASHBOARD CHARTS DATA /////////////////////

    /**
     * @param CompanyRepository $repo
     * @return JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     * @IsGranted("ROLE_USER")
     * @Route("/log/api/company/country", name="api_company_by_country", methods={"GET"})
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

    /**
     * @param ProjectRepository $repo
     * @IsGranted("ROLE_USER")
     * @Route("/log/api/project/year", name="api_project_by_year", methods={"GET"})
     */
    public function getProjectByYear(ProjectRepository $repo)
    {
        $count = $repo->getProjectCountByYears();

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($count, 'json');

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @param ProjectRepository $repo
     * @return JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     * @IsGranted("ROLE_USER")
     * @Route("/log/api/project/turnover", name="api_project_by_turnover", methods={"GET"})
     */
    public function getProjectTurnover(ProjectRepository $repo)
    {
        $turnover = $repo->getProjectTurnover();

        $encoders = [new JsonEncoder()];
        $normalizers = [(new ObjectNormalizer())];
        $serializer = new Serializer($normalizers, $encoders);
        $data = $serializer->serialize($turnover, 'json');

        return new JsonResponse($data, 200, [], true);
    }
}
