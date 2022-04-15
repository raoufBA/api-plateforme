<?php

namespace App\Controller;

use App\Entity\Post;

class PostPublishController
{

    /**
     * @param Post $data
     * @return Post
     */
    public function __invoke(Post $data): Post
    {
        $data->setPublish(true);

        return $data;
    }

}