<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\AddEditCategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'categorieController',
        ]);
    }

    #[Route('/categorie/list', name: 'app_categorie_list')]
    public function listcategorie(CategorieRepository $categorieRepository){
        $categoriesDB= $categorieRepository->findAll();
        return $this->render('categorie/list.html.twig',['categories' => $categoriesDB]);
    }

    #[Route('/categorie/new', name: 'app_categorie_new')]
    public function newcategorie(Request $request,EntityManagerInterface $em){
        $categorie= new Categorie();
        $form= $this->createForm(AddEditcategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('app_categorie_list');
        }
        return $this->render('categorie/form.html.twig',[
            'title' => 'Add categorie',
            'form'=> $form
        ]);
    }

    #[Route('/categorie/edit/{id}', name: 'app_categorie_edit')]
    public function editcategorie($id, Request $request,EntityManagerInterface $em, CategorieRepository $categorieRepository){
        $categorie= $categorieRepository->find($id);
        $form= $this->createForm(AddEditcategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //$em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('app_categorie_list');
        }
        return $this->render('categorie/form.html.twig',[
            'title' => 'Update categorie',
            'form'=> $form
        ]);
    }
    #[Route('/categorie/remove/{id}', name: 'app_categorie_remove')]
    public function removecategorie($id, CategorieRepository $categorieRepository, EntityManagerInterface $em){
        $categorie= $categorieRepository->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('app_categorie_list');
        //return new Response('categorie deleted');
    }
}
