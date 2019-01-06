<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends Controller
{
    /**
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/user", name="user_list")
     */
    public function listUsers(UserRepository $repo)
    {
        $users = $repo->findAll();

        return $this->render('user/user_list.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/admin/user/add", name="user_add")
     */
    public function addUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form['img']->getData();
            if (!is_null($file))
            {
                $file->move('img/user-profil', $file->getClientOriginalName());
                $user->setImg($file->getClientOriginalName());
            }
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()))
                ->setCreated(new \DateTime('now'));
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/user_add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/admin/user/update/{id}", name="user_update")
     * @ParamConverter("user", options={"exclude": {"request", "manager"}})
     */
    public function updateUser(User $user, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            //TODO Check d'image de profil
            //TODO Password pré rempli
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/user_update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param User $user
     * @Route("/admin/user/delete/{id}", name="user_delete")
     * @ParamConverter("user", options={"exclude": {"manager"}})
     */
    public function deleteUser(User $user, ObjectManager $manager)
    {
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/admin/user/addMail", name="user_add_mail")
     */
    public function addUserMail(\Swift_Mailer $mailer, Request $request)
    {
        if($request->request->get('email'))
        {
            //TODO Finaliser l'inscription par mail
            $message = (new \Swift_Message('Hello Mail'))
                ->setFrom('eddst.webdev@gmail.com')
                ->setTo($request->request->get('email'))
                ->setBody(
                    $this->renderView('user/user_add_mail.html.twig'),
                    'text/html'
                );

            $mailer->send($message);
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/user_add_mail.html.twig');
    }
}
