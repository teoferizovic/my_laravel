<?php
namespace App\Repositories;

interface CRUDRepositoryInterface
{
	public function all();

	public function get($id);

	public function create($data);

	public function update($category,$data);

	public function delete($category);
}