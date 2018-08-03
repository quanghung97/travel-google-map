<?php

namespace App\Repositories\Contracts;

interface CommentInterface
{
    /**
     * store comment
     * @return  mixed
     */
    public function storeComment($id, $content, $address);
}
