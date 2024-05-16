<div>
    <form class="form-horizontal" wire:submit.prevent="create">

        <div class="form-group row">
            <label for="salary_rate" class="col-sm-2 col-form-label">Salary Rate / Hour</label>
            <div class="col-sm-10">
                <input wire:model="salary_rate" type="text"
                    class="form-control @error('salary_rate') is-invalid @enderror" id="salary_rate">
                @error('salary_rate')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
                <button wire:click="create" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="create">Pay Raise</span>
                    <span wire:loading wire:target="create">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="visually-hidden">Loading...</span>
                    </span>
                </button>
            </div>
        </div>
    </form>
    <div class="tab-pane" id="timeline">
        @forelse ($records as $item)
            <div class="timeline timeline-inverse">
                <div>
                    <i class="fas fa-usd bg-{{ $item->is_active ? 'success' : 'secondary' }}">K</i>
                    <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i>
                            {{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}</span>
                        <h3 class="timeline-header">{{ $item->salary_rate }}</h3>
                    </div>
                </div>

            </div>
        @empty
            <p>No salary history...</p>
        @endforelse
    </div>
</div>
