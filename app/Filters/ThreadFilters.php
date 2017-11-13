<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filter
{
    protected $filters = ['by', 'popularity', 'unanswered'];

    /**
     * @param $userName
     * @return mixed
     */
    public function by($userName)
    {
        $user = User::where('name', $userName)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    public function popularity()
    {
        $this->builder->getQuery()->orders = [];

        $this->builder->orderBy('replies_count', 'desc');
    }

    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}
