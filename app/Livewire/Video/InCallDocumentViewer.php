<?php

namespace App\Livewire\Video;

use App\Models\Contract;
use Livewire\Component;

class InCallDocumentViewer extends Component
{
    public int $sessionId;
    public int $contractId;

    public function render()
    {
        $contract = Contract::with('signatures')->findOrFail($this->contractId);
        return view('livewire.video.in-call-document-viewer', compact('contract'));
    }
}
