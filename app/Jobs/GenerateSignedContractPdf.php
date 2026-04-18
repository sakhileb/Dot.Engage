<?php

namespace App\Jobs;

use App\Models\Contract;
use App\Models\ContractVersion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateSignedContractPdf implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(public readonly Contract $contract) {}

    /**
     * Merge all collected signatures into a final signed PDF and store it
     * as a new ContractVersion so the original is preserved.
     *
     * Full PDF manipulation requires a library such as FPDF or Snappy;
     * this implementation records the version entry and stamps the
     * contract as 'signed' — swap the placeholder with real PDF logic
     * once a renderer is installed.
     */
    public function handle(): void
    {
        if ($this->contract->status !== 'signed') {
            Log::info('GenerateSignedContractPdf: contract ' . $this->contract->id . ' is not fully signed yet, skipping.');
            return;
        }

        $signatures = $this->contract->signatures()->with('user')->get();

        if ($signatures->isEmpty()) {
            Log::warning('GenerateSignedContractPdf: no signatures found for contract ' . $this->contract->id);
            return;
        }

        // Determine next version number.
        $nextVersion = ($this->contract->versions()->max('version_number') ?? 0) + 1;

        // Build a placeholder signed path; replace with actual PDF rendering.
        $originalPath  = $this->contract->file_path;
        $signedFilename = 'signed_v' . $nextVersion . '_' . basename((string) $originalPath);
        $signedPath     = 'signed/' . $signedFilename;

        // Copy original as base (real impl would overlay signature images).
        if (Storage::disk('contracts')->exists((string) $originalPath)) {
            Storage::disk('contracts')->copy((string) $originalPath, $signedPath);
        }

        ContractVersion::create([
            'contract_id'    => $this->contract->id,
            'created_by'     => $this->contract->created_by,
            'version_number' => $nextVersion,
            'file_path'      => $signedPath,
            'change_notes'   => 'Signed by all parties on ' . now()->toDateString(),
        ]);

        Log::info('GenerateSignedContractPdf: version ' . $nextVersion . ' created for contract ' . $this->contract->id);

        // Trigger email delivery job.
        DispatchSignedContractEmail::dispatch($this->contract);
    }
}
