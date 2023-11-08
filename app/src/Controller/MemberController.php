<?php

namespace App\Controller;

use App\Entity\Member;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MemberController extends AbstractController
{
    #[Route('/member', methods: ['GET'], name: 'member_list')]
    public function index(MemberRepository $memberRepository): Response
    {
        $members = $memberRepository->findAll();

        return $this->render('member.html.twig', [
            'members' => $members
        ]);
    }

    #[Route('/member/create', methods: ['POST'], name: 'member_create')]
    public function create(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $member = new Member();
        $member->first_name = $request->get('firstname');
        $member->last_name = $request->get('lastname');
        $member->address = $request->get('address');

        $errors = $validator->validate($member);
        if (count($errors) > 0) {
            $this->addFlash('error', (string) $errors);

            return $this->redirectToRoute('member_list');
        }

        $entityManager->persist($member);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'New member were saved!'
        );

        return $this->redirectToRoute('member_list');
    }

    #[Route('/member/delete/{id}', methods: ['GET'], name: 'member_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        /** @var Member $member */
        $member = $entityManager->getRepository(Member::class)->find($id);
        if (!$member) {
            throw $this->createNotFoundException(
                'No member found for id ' . $id
            );
        }

        // $memberInfo = 'Member name: ' . $member->first_name . ' - '. $member->last_name . '(id=' . $member->getId() . ')';
        $memberInfo = sprintf('Member name: %s - %s (id = %s)', $member->first_name, $member->last_name, $member->getId());
        $entityManager->remove($member);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            $memberInfo . ' were deleted!'
        );

        return $this->redirectToRoute('member_list');
    }
}
