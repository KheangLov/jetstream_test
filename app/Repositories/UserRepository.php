<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class TypeRepository
 * @package App\Repositories\V1\Types
 * @version February 18, 2020, 1:22 am UTC
*/

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable() {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model() {
        return User::class;
    }

    public function getUserFilter($request, $keys) {
        return $this->model
            ->Filter($request->only($keys))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
    }
}
