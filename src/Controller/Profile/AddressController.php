<?php

namespace App\Controller\Profile;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/profile/addresses', name: 'app_profile_addresses')]
    public function index(): Response
    {
        return $this->render('profile/address/index.html.twig');
    }

    #[Route('/profile/address/add/{id}', name: 'app_profile_address_form', defaults: ['id' => null])]
    public function form(Request $request, $id, AddressRepository $addressRepository): Response
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
        return $this->render('profile/address/form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/profile/address/delete/{id}', name: 'app_profile_address_delete')]
    public function delete($id, AddressRepository $addressRepository): Response
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