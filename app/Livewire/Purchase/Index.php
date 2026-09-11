<?php

namespace App\Livewire\Purchase;

use App\Models\Purchase;
use App\Repositories\PurchaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function delete(Purchase $purchase)
    {
        $repository = app()->make(PurchaseRepository::class);

        $repository->delete($purchase) ?
            session()->now('alert-success', 'حذف شد !'):
            session()->now('alert-danger', 'وجود خطا در سرور !');
    }

    public function render(PurchaseRepository $repository)
    {
        return view('livewire.pages.purchase.index')
            ->with('purchases', $repository->paginate());
    }
}
