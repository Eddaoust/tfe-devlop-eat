<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/admin/user", name="user_admin")
     */
    public function listUsers(UserRepository $repository)
    {
        $users = $repository->findAll();

        return $this->render('user/user_list.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/user/add", name="user_add")
     *
     */
    public function addUser(Request $request, ObjectManager $manager)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        return $this->render('user/add_user.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
