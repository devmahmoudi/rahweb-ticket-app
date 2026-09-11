<?php

namespace App\Livewire\Purchase;

use App\Repositories\PurchaseRepository;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
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

    public function store()
    {
        $this->validate();

        $this->repository->create(array_merge(
            $this->all(),
            ['creator_id' => auth()->id()]
        ))?
            session()->flash('alert-success', 'فروش جدید ثبت شد !'):
            session()->flash('alert-danger', 'وجود خطا در سرور !');

        $this->redirect(route('purchase.index'));
    }

    public function __construct()
    {
        $this->repository = app()->make(PurchaseRepository::class);
    }

    public function render()
    {
        return view('livewire.pages.purchase.create');
    }
}
