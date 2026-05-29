<?php

namespace App\Livewire;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCustomers extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingCustomerId = null;

    // Form fields
    public $full_name = '';
    public $phone = '';
    public $email = '';
    public $notes = '';

    // Confirmation state
    public $confirmingDeletion = false;
    public $customerToDelete = null;

    protected function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingCustomerId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $customerId): void
    {
        $customer = Customer::findOrFail($customerId);
        $this->editingCustomerId = $customer->id;
        $this->full_name = $customer->full_name;
        $this->phone = $customer->phone ?? '';
        $this->email = $customer->email ?? '';
        $this->notes = $customer->notes ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        // Only admins can create/edit
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can manage customers.');
        }

        $this->validate();

        $data = [
            'full_name' => $this->full_name,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->editingCustomerId) {
            $customer = Customer::findOrFail($this->editingCustomerId);
            $customer->update($data);
            session()->flash('message', 'Customer updated successfully.');
        } else {
            Customer::create($data);
            session()->flash('message', 'Customer created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $customerId): void
    {
        $this->confirmingDeletion = true;
        $this->customerToDelete = $customerId;
    }

    public function deleteCustomer(): void
    {
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can delete customers.');
        }

        if ($this->customerToDelete) {
            Customer::findOrFail($this->customerToDelete)->delete();
            session()->flash('message', 'Customer deleted successfully.');
        }

        $this->confirmingDeletion = false;
        $this->customerToDelete = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeletion = false;
        $this->customerToDelete = null;
    }

    private function resetForm(): void
    {
        $this->full_name = '';
        $this->phone = '';
        $this->email = '';
        $this->notes = '';
        $this->editingCustomerId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->orderBy('full_name')
            ->paginate(10);

        return view('livewire.manage-customers', [
            'customers' => $customers,
            'isAdmin' => Auth::user()->isAdmin(),
        ]);
    }
}
