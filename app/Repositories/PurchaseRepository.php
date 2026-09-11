<?php

namespace App\Repositories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Collection;
use Livewire\WithPagination;

class PurchaseRepository
{
    use WithPagination;

    public function all(array $columns = ['*']):Collection
    {
        return Purchase::all($columns);
    }

    public function paginate(int $perPage = 20)
    {
        return Purchase::paginate($perPage);
    }

    public function create(array $data):Purchase|false
    {
        return Purchase::create($data);
    }

    public function update(Purchase $purchase, array $data):bool
    {
        return $purchase->update($data);
    }

    public function delete(Purchase $purchase):bool
    {
        return $purchase->delete();
    }
}
