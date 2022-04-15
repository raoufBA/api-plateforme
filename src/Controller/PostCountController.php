<?php

namespace App\Controller;


use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostCountController extends AbstractController
{

    /**
     * @var PostRepository
     */
    private $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke(): int
    {
        return $this->repository->count([]);
    }
}