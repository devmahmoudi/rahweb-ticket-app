<div class="row">
    <div class="col-xl-12">
        @can(['viewAny'], \App\Models\Ticket::class)
            <livewire:cartable.tickets/>
        @else
            <div class="card">
                <div class="card-body">
                    <h5 class="my-0">شما دسترسی به قسمت تیکت ها ندارید !</h5>
                </div>
            </div>
        @endcan
    </div>
</div>
