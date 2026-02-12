<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
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
        $orders = Order::with(['items.product', 'items.product_color', 'items.product_size', 'send_method'])
            ->whereNotNull('track_number')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('track_number', 'like', '%' . $this->search . '%')
                        ->orWhere('name', 'like', '%' . $this->search . '%')
                        ->orWhere('mobile', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterPayment !== '', function ($query) {
                $query->where('is_paid', $this->filterPayment);
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);

        return view('livewire.order-table', compact('orders'));
    }
}
