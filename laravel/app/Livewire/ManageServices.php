<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ManageServices extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingServiceId = null;

    // Form fields
    public $name = '';
    public $duration_minutes = '';
    public $price = '';

    // Confirmation state
    public $confirmingDeletion = false;
    public $serviceToDelete = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:480'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingServiceId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $serviceId): void
    {
        $service = Service::findOrFail($serviceId);
        $this->editingServiceId = $service->id;
        $this->name = $service->name;
        $this->duration_minutes = $service->duration_minutes;
        $this->price = $service->price;
        $this->showModal = true;
    }

    public function save(): void
    {
        // Only admins can manage services
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can manage services.');
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'duration_minutes' => (int) $this->duration_minutes,
            'price' => (float) $this->price,
        ];

        if ($this->editingServiceId) {
            $service = Service::findOrFail($this->editingServiceId);
            $service->update($data);
            session()->flash('message', 'Service updated successfully.');
        } else {
            Service::create($data);
            session()->flash('message', 'Service created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $serviceId): void
    {
        $this->confirmingDeletion = true;
        $this->serviceToDelete = $serviceId;
    }

    public function deleteService(): void
    {
        if (! Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can delete services.');
        }

        if ($this->serviceToDelete) {
            Service::findOrFail($this->serviceToDelete)->delete();
            session()->flash('message', 'Service deleted successfully.');
        }

        $this->confirmingDeletion = false;
        $this->serviceToDelete = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeletion = false;
        $this->serviceToDelete = null;
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->duration_minutes = '';
        $this->price = '';
        $this->editingServiceId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $services = Service::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.manage-services', [
            'services' => $services,
        ]);
    }
}
