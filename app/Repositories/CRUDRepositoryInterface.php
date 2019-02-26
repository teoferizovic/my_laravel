<?php
namespace App\Repositories;

interface CRUDRepositoryInterface
{
	public function all();

	public function get(int $id);

	public function create(array $data):bool;

	public function update($obj,array $data):bool;

	public function delete($obj):bool;
}