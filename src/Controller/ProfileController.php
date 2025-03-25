<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/profile/change-password', name: 'app_profile_change_password')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordUserType::class, $user, ['passwordHasher' => $passwordHasher]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'Your password have been updated'
            );
        }

        return $this->render('profile/password.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/profile/addresses', name: 'app_profile_addresses')]
    public function addresses(): Response
    {
        return $this->render('profile/addresses.html.twig');
    }

    #[Route('/profile/address/add/{id}', name: 'app_profile_address_form', defaults: ['id' => null])]
    public function addressForm(Request $request, $id, AddressRepository $addressRepository): Response
    {
        if ($id) {
            $address = $addressRepository->findOneBy(['id' => $id]);
            if (!$address OR $address->getUser() != $this->getUser()) {
                return $this->redirectToRoute('app_profile_addresses');
            }
        } else {
            $address = new Address();
            $address->setUser($this->getUser());
        }

        $form = $this->createForm(AddressUserType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            $this->addFlash(
                'success',
                'New address added'
            );
            return $this->redirectToRoute('app_profile_addresses');
        }
        return $this->render('profile/addressForm.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/profile/address/delete/{id}', name: 'app_profile_address_delete')]
    public function addressDelete($id, AddressRepository $addressRepository): Response
    {
        $address = $addressRepository->findOneBy(['id' => $id]);
        if (!$address OR $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_profile_addresses');
        }
        $this->entityManager->remove($address);
        $this->entityManager->flush();
        $this->addFlash(
            'success',
            'Address deleted'
        );
        return $this->redirectToRoute('app_profile_addresses');        
    }
}
