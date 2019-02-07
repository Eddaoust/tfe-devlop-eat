<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
