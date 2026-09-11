<div>
    <div class="modal fade show" id="largeModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title secondary-font"
                        id="exampleModalLabel3">{{ $title->isEmpty() ? 'Empty title' : $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $slot }}
                </div>
                @if(! $footer->isEmpty())
                    {{ $footer }}
                @endif
            </div>
        </div>
    </div>
</div>
