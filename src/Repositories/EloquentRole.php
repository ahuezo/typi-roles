<?php

namespace TypiCMS\Modules\Roles\Repositories;

use TypiCMS\Modules\Core\Repositories\EloquentRepository;
use TypiCMS\Modules\Roles\Models\Role;

class EloquentRole extends EloquentRepository
{
    protected $repositoryId = 'roles';

    protected $model = Role::class;

    /**
     * Get all models.
     *
     * @param array $with Eager load related models
     * @param bool  $all  Show published or all
     *
     * @return Collection|NestedCollection
     */
    public function all(array $with = [], $all = false)
    {
        return $this->with($with)->order()->get();
    }

    /**
     * Create a new model.
     *
     * @param array $data
     *
     * @return mixed Model or false on error during save
     */
    public function create(array $data = [])
    {
        $roleData = array_except($data, ['exit', 'permissions']);

        $model = $this->createModel()->fill($roleData);

        if ($model->save()) {
            $permissions = isset($data['permissions']) ? $data['permissions'] : [];
            $model->syncPermissions($permissions);

            return $model;
        }

        return false;
    }

    /**
     * Update an existing model.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update($id, array $data = [])
    {
        $role = $this->find($data['id']);

        $roleData = array_except($data, ['exit', 'permissions']);

        $role->fill($roleData);

        $permissions = isset($data['permissions']) ? $data['permissions'] : [];
        $role->syncPermissions($permissions);

        if ($role->save()) {
            return true;
        }

        return false;
    }
}
