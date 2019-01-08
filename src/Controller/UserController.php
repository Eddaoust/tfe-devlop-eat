<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\PasswordResetType;
use App\Form\UserUpdateType;
use App\Repository\InvitationRepository;
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
     * @Route("/log/user", name="user_list")
     */
    public function listUsers(UserRepository $repo)
    {
        $users = $repo->findAll();

        return $this->render('user/user_list.html.twig',[
            'users' => $users
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
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
     * @param \Swift_Mailer $mailer
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/admin/user/add", name="user_add")
     */
    public function addUserMail(\Swift_Mailer $mailer, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $form['img']->getData();
            if (!is_null($file))
            {
                $file->move('img/user-profil', $file->getClientOriginalName());
                $user->setImg($file->getClientOriginalName());
            }

            $generator = new UriSafeTokenGenerator(256);
            $token = $generator->generateToken();

            $user->setPassword($encoder->encodePassword($user, $token.'fakePassword'))
                ->setRoles(['ROLE_USER'])
                ->setCreated(new \DateTime('now'));

            $invitation = new Invitation();
            $invitation->setUser($user);
            $invitation->setToken($token);
            $invitation->setSendDate(new \DateTime('now'));

            $manager->persist($user);
            $manager->persist($invitation);
            $manager->flush();

            $message = (new \Swift_Message('Invitation pour Devlop Eat'))
                ->setFrom('eddst.webdev@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('user/user_add_link.html.twig', [
                        'token' => $token,
                        'id' => $user->getId()
                    ]),
                    'text/html'
                );

            $mailer->send($message);
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/user_add.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @param UserRepository $userRepo
     * @param InvitationRepository $invitRepo
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param $id
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/reset-password/{id}/{token}", name="user_reset_password")
     */
    public function resetPassword(UserRepository $userRepo, InvitationRepository $invitRepo, Request $request,ObjectManager $manager, UserPasswordEncoderInterface $encoder ,$id, $token)
    {
        $user = $userRepo->find($id);
        $invitation = $invitRepo->findOneBy(['user' => $id]);
        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $invitation->getToken() === $token)
        {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $manager->remove($invitation);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('user/user_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
