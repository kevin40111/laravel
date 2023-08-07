<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use DB;
use Exception;

class UserRepository
{
    public function fetchRoleId($name)
    {
        $role = Role::where("name", $name)->first();
        if (is_null($role)) {
            return null;
        }

        return $role->id;
    }
    public function create(mixed $data)
    {
        if (isset($data["role"])) {
            $role_id = $this->fetchRoleId($data["role"]);
            if (is_null($role_id)) {
                throw new Exception("Role not found");
            }

            $data["role_id"] = $role_id;
            unset($data["role"]);
        }

        return User::create($data);
    }

    public function find(int $id): mixed
    {
        return User::query()
            ->where("user.id", $id)
            ->join("role", "role.id", "=", "user.role_id")
            ->select("user.*", "role.name as role")
            ->first();
    }

    public function findByEmail(string $email): mixed
    {
        return User::query()
            ->where("email", $email)
            ->join("role", "role.id", "=", "user.role_id")
            ->select("user.*", "role.name as role")
            ->first();
    }

    public function fetchItems(int $page = 0, int $size = 10)
    {
        return User::query()
            ->join("role", "role.id", "=", "user.role_id")
            ->select("user.*", "role.name as role")
            ->skip($page)
            ->take($size)
            ->get();
    }

    public function fetchItemsCount()
    {
        return User::query()
            ->join("role", "role.id", "=", "user.role_id")
            ->select("user.*", "role.name as role")
            ->count();
    }

    public function update(int $id, mixed $data)
    {
        if (isset($data["role"])) {
            $role_id = $this->fetchRoleId($data["role"]);
            if (is_null($role_id)) {
                throw new Exception("Role not found");
            }

            $data["role_id"] = $role_id;
            unset($data["role"]);
        }

        User::where("id", $id)->update($data);

        return $this->find($id);
    }
}
