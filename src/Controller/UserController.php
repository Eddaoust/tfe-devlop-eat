<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\PasswordResetType;
use App\Form\UserAddType;
use App\Form\UserUpdateType;
use App\Repository\InvitationRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

class UserController extends Controller
{
    /**
     * @param \Swift_Mailer $mailer
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/admin/user/add", name="user_add")
     */
    public function addUser(\Swift_Mailer $mailer, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(UserAddType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // Récupération de l'user dans le form
            $user = $form->getData();
            // Récupération de l'image
            $image = $user->getImage();
            // Récupération du fichier
            $file = $image->getFile();
            // Transformation du nom du fichier
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            // Déplacement vers le répertoire cible
            $file->move(
                $this->getParameter('user_images_directory'),
                $fileName);
            // Persist du nom
            $image->setName($fileName);
            // Création du Token
            $generator = new UriSafeTokenGenerator(256);
            $token = $generator->generateToken();
            // Mise en place d'un faux pw
            $user->setPassword($encoder->encodePassword($user, $token.'fakePassword'))
                ->setRoles(['ROLE_USER'])
                ->setCreated(new \DateTime('now'));
            // Création d'une invitation
            $invitation = new Invitation();
            $invitation->setUser($user);
            $invitation->setToken($token);
            $invitation->setSendDate(new \DateTime('now'));

            $manager->persist($user);
            $manager->persist($invitation);
            $manager->flush();
            // Envoi du mail d'invitation
            $message = (new \Swift_Message('Invitation pour Devlop Eat'))
                ->setFrom('eddst.webdev@gmail.com')// devlopeat@eddaoust.com "Changer lors Push en ligne"
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView('user/user_add_link.html.twig', [
                        'token' => $token,
                        'id' => $user->getId()
                    ]),
                    'text/html'
                );

            $mailer->send($message);
            $this->addFlash('success', 'Invitation envoyé à l\'utilisateur');
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/user_add.html.twig', [
            'form' => $form->createView()
        ]);

    }

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
     * @return Response
     * @Route("/admin/user/{id}", name="user_one")
     */
    public function oneUser(User $user)
    {
        return $this->render('user/user_one.html.twig', [
            'user' => $user
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
            $data = $form->getData();
            $image = $data->getImage();
            // Test de la présence d'une image envoyé via le form
            if (!is_null($image) && !is_null($image->getFile()))
            {
                $file = $image->getFile();
                // Test si l'utilisateur contient déja une image
                if (!is_null($user->getImage()->getName()))
                {
                    $path = $this->getParameter('user_images_directory').'/'.$user->getImage()->getName();
                    // Suppression de l'ancienne image
                    unlink($path);
                }
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('user_images_directory'),
                    $fileName);
                $image->setName($fileName);
            }
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Informations de l\'utilisateur mise à jour');
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/user_update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param User $user
     * @Route("/admin/user/delete/{id}", name="user_delete")
     * @ParamConverter("user", options={"exclude": {"manager", "filesystem"}})
     */
    public function deleteUser(User $user, ObjectManager $manager, Filesystem $filesystem)
    {
        if (!is_null($user->getImage()))
        {
            // Si image, on supprime l'ancienne
            $path = $this->getParameter('user_images_directory').'/'.$user->getImage()->getName();
            $filesystem->remove($path);
        }
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('user_list');
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
     * @throws \Exception
     * @Route("/reset-password/{id}/{token}", name="user_reset_password")
     */
    public function resetPassword(UserRepository $userRepo, InvitationRepository $invitRepo, Request $request,ObjectManager $manager, UserPasswordEncoderInterface $encoder ,$id, $token)
    {
        // Sélection de l'user via l'id du mail
        $user = $userRepo->find($id);
        // Sélection de l'invitation de l'user
        $invitation = $invitRepo->findOneBy(['user' => $id]);
        // Test de la présence d'un token
        if(!empty($invitation) && $invitation->getToken() === $token)
        {
            // Définition du temps de validité du token
            $invitTimeOut = date('Y-m-d H:i:s', strtotime("+10 minute", strtotime($invitation->getSendDate()->format('Y-m-d H:i:s'))));
            $dateNow = date('Y-m-d H:i:s');

            // Si le token est valide dans le timing
            if(strtotime($dateNow) < strtotime($invitTimeOut))
            {
                $form = $this->createForm(PasswordResetType::class);
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid())
                {
                    // Enregistrement du nouveau pass
                    $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                    // Suppression de l'invitation
                    $manager->remove($invitation);
                    $manager->persist($user);
                    $manager->flush();

                    $this->addFlash('success', 'Votre password a été modifié avec succès');
                    return $this->redirectToRoute('login');
                }

                return $this->render('user/user_password.html.twig', [
                    'form' => $form->createView()
                ]);
            }
            else
            {
                // Si le token n'est plus valide on efface l'user et l'invitation
                $manager->remove($user);
                $manager->remove($invitation);
                $manager->flush();
                $this->addFlash('danger', 'Votre invitation n\'est plus valide');
                return $this->redirectToRoute('login');
            }
        }
        else
        {
            return $this->redirectToRoute('login');
        }
    }
}
