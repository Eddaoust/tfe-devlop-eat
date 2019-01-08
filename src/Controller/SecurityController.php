<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/log/login", name="login")
     */
    public function login(){}

    /**
     * @Route("/log/logout", name="logout")
     */
    public function logout(){}
}
