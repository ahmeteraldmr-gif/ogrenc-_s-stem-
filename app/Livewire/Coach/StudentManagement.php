<?php

namespace App\Livewire\Coach;

use App\Models\CoachStudent;
use App\Models\Role;
use App\Models\User;
use App\Notifications\WelcomeStudentNotification;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class StudentManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editMode = false;
    public $studentId;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $is_active = true;

    public $search = '';

    protected $queryString = ['search'];

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->studentId,
            'phone' => 'nullable|string|max:20',
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
        $this->reset(['studentId', 'name', 'email', 'password', 'phone', 'editMode']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $studentRole = Role::where('name', 'student')->first();
        $coach = auth()->user();

        // Abonelik limiti kontrolü
        if (!$this->editMode) {
            $currentStudentCount = $coach->students()->count();
            $subscription = $coach->subscription;
            
            if ($subscription && $subscription->plan->student_limit !== null) {
                if ($currentStudentCount >= $subscription->plan->student_limit) {
                    session()->flash('error', 'Öğrenci limitine ulaştınız. Paketinizi yükseltmeniz gerekiyor.');
                    return;
                }
            }
        }

        if ($this->editMode) {
            $student = User::findOrFail($this->studentId);
            $student->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'is_active' => $this->is_active,
            ]);

            if ($this->password) {
                $student->update(['password' => Hash::make($this->password)]);
            }

            session()->flash('message', 'Öğrenci başarıyla güncellendi.');
        } else {
            $student = User::create([
                'role_id' => $studentRole->id,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone' => $this->phone,
                'is_active' => $this->is_active,
            ]);

            // Koç-öğrenci ilişkisi oluştur
            CoachStudent::create([
                'coach_id' => $coach->id,
                'student_id' => $student->id,
                'is_active' => true,
            ]);

            // Hoş geldin maili gönder
            try {
                $student->notify(new WelcomeStudentNotification($coach->name));
            } catch (\Exception $e) {
                // Mail gönderiminde hata olursa sessizce devam et
            }

            session()->flash('message', 'Öğrenci başarıyla eklendi.');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $student = User::findOrFail($id);
        
        // Bu öğrenci bu koça ait mi kontrol et
        if (!auth()->user()->students()->where('users.id', $id)->exists()) {
            session()->flash('error', 'Bu öğrenciye erişim yetkiniz yok.');
            return;
        }
        
        $this->studentId = $student->id;
        $this->name = $student->name;
        $this->email = $student->email;
        $this->phone = $student->phone;
        $this->is_active = $student->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        // Koç-öğrenci ilişkisini sil
        CoachStudent::where('coach_id', auth()->id())
            ->where('student_id', $id)
            ->delete();
            
        session()->flash('message', 'Öğrenci listenizden çıkarıldı.');
    }

    public function render()
    {
        $coach = auth()->user();
        
        $students = $coach->students()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount(['questionLogs', 'examResults'])
            ->latest('users.created_at')
            ->paginate(10);

        $subscription = $coach->subscription;
        $studentLimit = $subscription?->plan->student_limit;
        $currentCount = $coach->students()->count();

        return view('livewire.coach.student-management', [
            'students' => $students,
            'studentLimit' => $studentLimit,
            'currentCount' => $currentCount,
        ]);
    }
}
