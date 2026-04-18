<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ContractList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $contract = Contract::findOrFail($id);
        $this->authorize('delete', $contract);
        $contract->delete();
    }

    public function render()
    {
        $teamId = Auth::user()->currentTeam->id;

        $contracts = Contract::where('team_id', $teamId)
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.contracts.contract-list', compact('contracts'));
    }
}
