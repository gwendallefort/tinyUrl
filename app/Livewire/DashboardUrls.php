<?php

namespace App\Livewire;

use App\Models\ShortUrl;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class DashboardUrls extends Component
{
    public string $search = '';

    public function clear(): void
    {
        $this->search = '';
    }

    public function render(): View
    {
        return view('livewire.dashboard-urls', [
            'shortUrls' => $this->shortUrls(),
            'search' => trim($this->search),
        ]);
    }

    protected function shortUrls(): Collection
    {
        $search = trim($this->search);

        return auth()->user()
            ->shortUrls()
            ->latest()
            ->when($search !== '', function ($query) use ($search) {
                $term = '%'.addcslashes($search, '%_\\').'%';

                $query->where(function ($query) use ($term) {
                    $query->whereLike('short_code', $term)
                        ->orWhereLike('original_url', $term);
                });
            })
            ->get();
    }
}
