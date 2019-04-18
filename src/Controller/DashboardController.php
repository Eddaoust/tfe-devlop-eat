<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class DashboardController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/log/dashboard", name="dashboard")
     */
    public function index()
    {
        return $this->render('dashboard/dash-chart.html.twig');
    }
}
