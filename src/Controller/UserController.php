<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class UserController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    // public function index(PersistenceManagerRegistry $doctrine){

    //     $users = $doctrine->getRepository(User::class)->findAll();
    //     return $this->json($users);
    
    // }
    

    // public function show(PersistenceManagerRegistry $doctrine,int $id){

    //     $users = $doctrine->getRepository(User::class)->find($id);

    //     if(!$users){
    //         return new JsonResponce(['error' => 'User not found'], 404);
    //     }

    //     return $this->json($users);
    // }

    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
       
        //Récupère les données JSON de la requete
        $data = json_decode($request->getContent(), true);
        //Crée un nouvel utilisateur
        $user = new User();
        $user->setUsername($data['Username']);
        $user->setEmail($data['Email']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['Password']);
        $user->setPassword($hashedPassword);

        $user->setRoles($data['Roles'] ?? ['ROLE_USER']);

        //Enregistre l'utilisateur dans la base de données
        $entityManager->persist($user);
        $entityManager->flush();//push l'utilisateur dans la base de données

        //Retourne une confirmation de création
        return new JsonResponse(['status' => 'User created'], JsonResponse::HTTP_CREATED);
    }

    public function show(User $user): JsonResponse
    {

        //Crée un nouvel utilisateur
       $data = 
       [
        'Id' => $user->getID(),
        'Username' => $user->getUsername(),
        'Email' => $user->getEmail(),
        'Password'=> $user->getPassword(),
        'Roles' => $user ->getRoles(),
       ];
        //Retourne une confirmation de création
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    public function update(EntityManagerInterface $entityManager,User $user,Request $request,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
       
        //Récupère les données JSON de la requete
        $data = json_decode($request->getContent(),true);

        //Crée un nouvel utilisateur
        if(isset($data['Username'])){
            $user->setUsername($data['Username']);
        }
        if(isset($data['Email'])){
            $user->setEmail($data['Email']);
        }
        if(isset($data['Password'])){
            $user->setPassword($data['Password']);
        }
        if(isset($data['Roles'])){
            $user->setRoles($data['Roles'] ?? ['ROLE_USER']);
        }

        //Enregistre l'utilisateur dans la base de données
        $entityManager->flush();//push l'utilisateur dans la base de données

        //Retourne une confirmation de création
        return new JsonResponse(['status' => 'User updated'], JsonResponse::HTTP_OK);
    }
    public function delete(EntityManagerInterface $entityManager,User $user): JsonResponse
    {
       
        //Supprime l'utilisateur dans la base de données
        $entityManager->remove($user);
        $entityManager->flush();//push l'utilisateur dans la base de données

        //Retourne une confirmation de création
        return new JsonResponse(['status' => 'User deleted'], JsonResponse::HTTP_OK);
    }
}
