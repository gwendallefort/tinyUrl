<?php

namespace App\Livewire;

use App\Models\ShortUrl;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class DashboardUrls extends Component
{
    public string $search = '';

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public function clear(): void
    {
        $this->search = '';
    }

    public function sortBy(string $field): void
    {
        if (! in_array($field, ['short_code', 'clicks'], true)) {
            return;
        }

        if ($this->sortField === $field) {
            if ($field === 'short_code') {
                if ($this->sortDirection === 'asc') {
                    $this->sortDirection = 'desc';
                } else {
                    $this->sortField = 'created_at';
                    $this->sortDirection = 'desc';
                }
            } elseif ($this->sortDirection === 'desc') {
                $this->sortDirection = 'asc';
            } else {
                $this->sortField = 'created_at';
                $this->sortDirection = 'desc';
            }
        } else {
            $this->sortField = $field;
            $this->sortDirection = $field === 'short_code' ? 'asc' : 'desc';
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard-urls', [
            'shortUrls' => $this->shortUrls(),
            'search' => trim($this->search),
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
        ]);
    }

    protected function shortUrls(): Collection
    {
        $search = trim($this->search);
        $sortField = in_array($this->sortField, ['created_at', 'short_code', 'clicks'], true)
            ? $this->sortField
            : 'created_at';
        $sortDirection = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        return auth()->user()
            ->shortUrls()
            ->when($search !== '', function ($query) use ($search) {
                $term = '%'.addcslashes($search, '%_\\').'%';

                $query->where(function ($query) use ($term) {
                    $query->whereLike('short_code', $term)
                        ->orWhereLike('original_url', $term);
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->get();
    }
}
