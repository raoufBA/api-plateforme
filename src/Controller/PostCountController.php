<?php

namespace App\Controller;


use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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


    public function __invoke(Request $request): int
    {
        $query = $request->get('publish');
        $condition = isset($query) ? ['publish' => $query] : [];

        return $this->repository->count($condition);
    }
}