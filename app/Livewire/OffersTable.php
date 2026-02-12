<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Component;
use Livewire\WithPagination;

class OffersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // جستجو
    public $searchInput = '';
    public $search = '';

    // مرتب‌سازی
    public $sortField = 'created_at';
    public $sortAsc = false;

    // فیلتر وضعیت
    public $filterStatus = '';
    public $filterPayment = '';

    public function applySearch()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortField = $field;
            $this->sortAsc = true;
        }
    }

    public function render()
    {
        $offers = Offer::
            when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('code', 'like', '%' . $this->search . '%');

                });
            })

            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);

        return view('livewire.offers-table', compact('offers'));
    }
}
