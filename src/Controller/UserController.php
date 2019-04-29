<?php

namespace App\Controller;

use App\Entity\User;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends Controller
{
    /**
     * Create a new user secure by token
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param InvitationRepository $invitationRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("user/create", name="user_create")
     */
    public function createUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, InvitationRepository $invitationRepository)
    {
        // Check if token in request url
        if ($request->query->get('token')) {

            // Check if invitation relative to token send
            $invitation = $invitationRepository->findOneBy(['token' => $request->query->get('token')]);

            if (!empty($invitation)) {

                // Set the invitation timeout
                $now = new \DateTime();
                $sendDate = clone($invitation->getSendDate());
                $timeOut = new \DateInterval('PT24H');

                // Check if timeout
                if ($now->sub($timeOut) < $sendDate) {
                    $user = new User();

                    $form = $this->createForm(UserAddType::class, $user);
                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid()) {

                        // File management
                        $user = $form->getData();
                        if (!is_null($user->getImage())) {
                            // Récupération de l'image
                            $image = $user->getImage();
                            // Récupération du fichier
                            $file = $image->getFile();
                            // Transformation du nom du fichier
                            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                            // Déplacement vers le répertoire cible
                            $file->move(
                                $this->getParameter('user_images_directory'),
                                $fileName);
                            // Persist du nom
                            $image->setName($fileName);
                        }
                        // Set user
                        $user->setPassword($encoder->encodePassword($user, $user->getPassword()))
                            ->setRoles(['ROLE_USER'])
                            ->setCreated(new \DateTime('now'));

                        // Change the invitation data
                        $invitation->setStatus($invitation::CONFIRMED)
                            ->setToken('')
                            ->setEmail($user->getEmail())
                            ->setUser($user)
                            ->setConfirmedAt(new \DateTime());

                        $manager->persist($user);
                        $manager->persist($invitation);
                        $manager->flush();

                        $this->addFlash('success', 'Compte créé avec succès');
                        return $this->redirectToRoute('login');
                    }

                    return $this->render('user/user_add.html.twig', [
                        'form' => $form->createView()
                    ]);
                } else {
                    $invitation->setStatus($invitation::TIMEOUT);
                    $invitation->setToken('');
                    $manager->persist($invitation);
                    $manager->flush();

                    $this->addFlash('danger', 'Votre invitation n\'est plus valide');
                    return $this->redirectToRoute('login');
                }
            } else {
                throw new \Exception('Accès refusé');
            }
        } else {
            throw new \Exception('Accès refusé');
        }
    }

    /**
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_ADMIN")
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
     * @param User $user
     * @return Response
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/user/update/{id}", name="user_update")
     * @ParamConverter("user", options={"exclude": {"request", "manager"}})
     */
    public function updateUser(User $user, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if (!array_key_exists('roles', $request->request->get('user_update'))) {
                $user->setRoles(['ROLE_USER']);
            }

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
     * @IsGranted("ROLE_ADMIN")
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
}
