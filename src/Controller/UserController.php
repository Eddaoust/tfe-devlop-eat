<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

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
     * @Route("/user/add", name="user_add")
     */
    public function addUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        // Todo Afiner la méthode avec le token
        if($request->query->get('token'))
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

                return $this->redirectToRoute('home');
            }

            return $this->render('user/user_add.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return new Response('Accès refusé');
        }
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
        $form = $this->createForm(UserUpdateType::class, $user);
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
        $user = $this->getUser();
        $generator = new UriSafeTokenGenerator(256);
        $token = $generator->generateToken();
        if($request->request->get('email'))
        {
            //TODO Finaliser l'inscription par mail
            $message = (new \Swift_Message('Hello Mail'))
                ->setFrom('eddst.webdev@gmail.com')
                ->setTo($request->request->get('email'))
                ->setBody(
                    $this->renderView('user/user_add_link.html.twig', [
                        'user' => $user,
                        'token' => $token
                    ]),
                    'text/html'
                );

            $mailer->send($message);
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/user_add_mail.html.twig');
    }
}
