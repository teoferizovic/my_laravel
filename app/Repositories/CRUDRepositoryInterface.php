<?php
namespace App\Repositories;

use App\Category;

interface CRUDRepositoryInterface
{
	public function all();

	public function get(int $id);

	public function create(array $data):bool;

	public function update(Category $category,array $data):bool;

	public function delete(Category $category):bool;
}