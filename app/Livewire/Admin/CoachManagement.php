<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class CoachManagement extends Component
{
    use WithPagination;

    // Form properties
    public $showModal = false;
    public $editMode = false;
    public $coachId;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $subscription_plan_id;
    public $is_active = true;

    // Search & Filter
    public $search = '';
    public $filterStatus = '';

    protected $queryString = ['search', 'filterStatus'];

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->coachId,
            'phone' => 'nullable|string|max:20',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'is_active' => 'boolean',
        ];

        if (!$this->editMode) {
            $rules['password'] = 'required|min:6';
        } elseif ($this->password) {
            $rules['password'] = 'min:6';
        }

        return $rules;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['coachId', 'name', 'email', 'password', 'phone', 'subscription_plan_id', 'editMode']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $coachRole = Role::where('name', 'coach')->first();

        if ($this->editMode) {
            $coach = User::findOrFail($this->coachId);
            $coach->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'is_active' => $this->is_active,
                'created_by' => auth()->id(),
            ]);

            if ($this->password) {
                $coach->update(['password' => Hash::make($this->password)]);
            }

            // Update subscription
            $subscription = $coach->subscription;
            if ($subscription) {
                $subscription->update([
                    'subscription_plan_id' => $this->subscription_plan_id,
                ]);
            }

            session()->flash('message', 'Koç başarıyla güncellendi.');
        } else {
            $coach = User::create([
                'role_id' => $coachRole->id,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone' => $this->phone,
                'is_active' => $this->is_active,
                'created_by' => auth()->id(),
            ]);

            // Create subscription
            Subscription::create([
                'user_id' => $coach->id,
                'subscription_plan_id' => $this->subscription_plan_id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'next_payment_date' => Carbon::now()->addMonth(),
                'is_active' => true,
                'is_trial' => true,
            ]);

            session()->flash('message', 'Koç başarıyla eklendi.');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $coach = User::with('subscription')->findOrFail($id);
        
        $this->coachId = $coach->id;
        $this->name = $coach->name;
        $this->email = $coach->email;
        $this->phone = $coach->phone;
        $this->is_active = $coach->is_active;
        $this->subscription_plan_id = $coach->subscription?->subscription_plan_id;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function toggleStatus($id)
    {
        $coach = User::findOrFail($id);
        $coach->update(['is_active' => !$coach->is_active]);
        
        session()->flash('message', 'Koç durumu güncellendi.');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'Koç silindi.');
    }

    public function render()
    {
        $coachRole = Role::where('name', 'coach')->first();
        $user = auth()->user();
        
        $coaches = User::where('role_id', $coachRole->id)
            ->with(['subscription.plan', 'students'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->when(!$user->isSuperAdmin(), function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('creator', function ($q2) {
                        $q2->where('role_id', '!=', Role::where('name', 'superadmin')->first()->id);
                    })->orWhereNull('created_by');
                });
            })
            ->latest()
            ->paginate(10);

        $subscriptionPlans = SubscriptionPlan::where('is_active', true)->get();

        return view('livewire.admin.coach-management', [
            'coaches' => $coaches,
            'subscriptionPlans' => $subscriptionPlans,
        ]);
    }
}
