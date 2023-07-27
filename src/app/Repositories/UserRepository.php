<?php

namespace App\Repositories;

use App\Models\User;
use DB;
use Exception;

class UserRepository
{
    public function fetchRoleId($name)
    {
        $role = DB::table("role")
            ->where("name", $name)
            ->first();
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

    /**
     * @param int $id
     * @return null|mixed
     */
    public function find(int $id)
    {
        $user = User::query()
            ->where("user.id", $id)
            ->join("role", "role.id", "=", "user.role_id")
            ->select("user.*", "role.name as role")
            ->first();

        return $user;
    }

    /**
     * @param string $email
     * @return null|mixed
     */
    public function findByEmail(string $email)
    {
        $user = User::query()
            ->where("email", $email)
            ->join("role", "role.id", "=", "user.role_id")
            ->select("user.*", "role.name as role")
            ->first();

        return $user;
    }

    public function fetchItems(int $page = 0, int $size = 10)
    {
        $users = User::query()
            ->join("role", "role.id", "=", "user.role_id")
            ->select("user.*", "role.name as role")
            ->skip($page)
            ->take($size)
            ->get();

        return $users;
    }

    public function fetchItemsCount()
    {
        $count = User::query()
            ->join("role", "role.id", "=", "user.role_id")
            ->select("user.*", "role.name as role")
            ->count();

        return $count;
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

        $user = $this->find($id);

        return $user;
    }
}
