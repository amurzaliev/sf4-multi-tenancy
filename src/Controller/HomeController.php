<?php

namespace App\Controller;

use App\Doctrine\DBAL\TenantConnection;
use App\Entity\Tenant\User;
use App\Repository\Main\TenantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @param TenantRepository $tenantRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(TenantRepository $tenantRepository)
    {
        $users = [];
        /** @var TenantConnection $connection */
        $connection = $this->get('doctrine')->getConnection('tenant');

        foreach ($tenantRepository->findAll() as $tenant) {
            $connection->changeParams($tenant->getDbName(), $tenant->getDbUser(), $tenant->getDbPassword());
            $connection->reconnect();

            $userRepository = $this->getDoctrine()->getRepository(User::class, 'tenant');
            $tenantUsers = $userRepository->findAll();
            $userRepository->clear();
            $users[] = $tenantUsers;
        }

        return $this->render('home/index.html.twig', [
            'users' => $users,
        ]);
    }
}
