<?php

namespace App\Livewire\Purchase;

use App\Models\Purchase;
use App\Repositories\PurchaseRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Purchase $purchase;

    private PurchaseRepository $repository;

    #[Validate(['required', 'string', 'max:255'])]
    public string $fullname;

    #[Validate(['required', 'date'])]
    public string $sale_date;

    #[Validate(['required', 'numeric'])]
    public string $amount_paid;

    #[Validate(['required', 'min:11', 'max:11'])]
    public string $phone;

    #[Validate(['required', 'string'])]
    public string $description;

    public function update()
    {
        $this->validate();

        $this->repository->update($this->purchase, $this->all())?
            session()->flash('alert-success', 'ویرایش شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('purchase.index'));
    }

    public function __construct()
    {
        $this->repository = app()->make(PurchaseRepository::class);
    }

    public function mount()
    {
        $this->fullname = $this->purchase->fullname;

        $this->sale_date = $this->purchase->sale_date;

        $this->amount_paid = $this->purchase->amount_paid;

        $this->phone = $this->purchase->phone;

        $this->description = $this->purchase->description;
    }

    public function render()
    {
        return view('livewire.pages.purchase.edit');
    }
}
