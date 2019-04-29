<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Form\InvitationType;
use App\Repository\InvitationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class InvitationController
 * @package App\Controller
 */
class InvitationController extends Controller
{
    //TODO Envoyer un mail lors de la suppression de compte
    /**
     * Send an invitation
     *
     * @param \Swift_Mailer $mailer
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/invitation/make", name="invitation_make")
     * @IsGranted("ROLE_ADMIN")
     */
    public function makeInvitation(\Swift_Mailer $mailer, Request $request, ObjectManager $manager)
    {
        $invitation = new Invitation();

        $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $token = bin2hex(random_bytes(24));

            $invitation->setToken($token)
                ->setSendDate(new \DateTime())
                ->setStatus($invitation::WAITING);
            $manager->persist($invitation);
            $manager->flush();

            $message = (new \Swift_Message('Invitation à Devlop-Eat'))
                ->setFrom('noreply@devlop-eat.com')
                ->setTo($invitation->getEmail())
                ->setBody(
                    $this->renderView('invitation/invitation_mail.html.twig', [
                        'token' => $token,
                        'id' => $invitation->getId()
                    ]),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash('success', 'Invitation envoyé à l\'utilisateur');
            return $this->redirectToRoute('invitation_list');
        }

        return $this->render('invitation/invitation_make.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Invitation refuse
     *
     * @param Invitation $invitation
     * @param ObjectManager $manager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/invitation/refuse", name="invitation_refuse")
     */
    public function invitationRefuse(ObjectManager $manager, Request $request, InvitationRepository $repo)
    {
        if ($request->query->get('id')) {
            $invitation = $repo->find($request->query->get('id'));

            if ($request->query->get('token') === $invitation->getToken()) {
                $invitation->setToken('')
                    ->setStatus($invitation::REFUSED);
                $manager->persist($invitation);
                $manager->flush();

                $this->addFlash('info', 'Vous avez refusé l\'invitation.');
                return $this->redirectToRoute('login');
            } else {
                throw new \Exception('Accès refusé');
            }
        } else {
            throw new \Exception('Accès refusé');
        }


    }

    /**
     * @param InvitationRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/invitation/list", name="invitation_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function invitationList(InvitationRepository $repo)
    {
        $invitations = $repo->findAll();

        return $this->render('invitation/invitation_list.html.twig', [
            'invitations' => $invitations
        ]);
    }

    /**
     * @param Invitation $invitation
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/invitation/delete/{id}", name="invitation_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function invitationDelete(Invitation $invitation, ObjectManager $manager)
    {
        $manager->remove($invitation);
        $manager->flush();

        $this->addFlash('success', 'Invitation supprimée');
        return $this->redirectToRoute('invitation_list');
    }
}
