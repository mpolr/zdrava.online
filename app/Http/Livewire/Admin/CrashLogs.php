<?php

namespace App\Http\Livewire\Admin;

use App\Models\AndroidAppCrashes;
use Livewire\Component;
use Livewire\Redirector;

class CrashLogs extends Component
{
    public $reports;
    public ?string $issueId;

    public function mount(?string $issueId = null): void
    {
        if (!empty($issueId)) {
            $this->issueId = $issueId;
            $this->reports = AndroidAppCrashes::where('issue_id', $issueId)->firstOrFail();
        } else {
            $this->reports = AndroidAppCrashes::all();
        }
    }

    public function deleteReport(string $issueId): \Illuminate\Http\RedirectResponse|Redirector
    {
        AndroidAppCrashes::where('issue_id', $issueId)->first()->delete();
        return redirect()->route('admin.crashlogs');
    }
}
