<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\AddEditProduitType;
use App\Repository\AuthorRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
  
    #[Route('/list', name: 'app_produit_list')]
    public function produitList(ManagerRegistry $doctrine){
        $produitRepository= $doctrine->getRepository(Produit::class);
        $produits= $produitRepository->findAll();
        return $this->render('produit/list.html.twig',[
            'produits' => $produits
        ]);
    }

    #[Route('/new', name: 'app_produit_new')]
    public function newproduit(Request $request, ManagerRegistry $doctrine){
        $produit = new produit();
        //$produit->setTitle('Abc'); //champs du formulaire pré-rempli
        $em= $doctrine->getManager();
        $form= $this->createForm(AddEditproduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit_list');
        }
        return $this->render('produit/form.html.twig', [
            'title' => 'Add produit',
            'form' => $form
        ]);

    }
    #[Route('/edit/{id}', name: 'app_produit_edit')]
    public function editproduit($id, Request $request, ManagerRegistry $doctrine){
        $produitRepository = $doctrine->getRepository(Produit::class);
        $produit = $produitRepository->find($id);
        $em= $doctrine->getManager();
        $form= $this->createForm(AddEditproduitType::class, $produit);
        //$form->add('Update', SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            //$em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit_list');
        }
        return $this->render('produit/form.html.twig', [
            'title' => 'Update produit',
            'form' => $form
        ]);

    }
    #[Route('/delete/{id}', name: 'app_produit_delete')]
    public function deleteproduit($id, ProduitRepository $produitRepository, EntityManagerInterface $em){
        $produit= $produitRepository->find($id);
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('app_produit_list');
    }

}
