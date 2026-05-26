<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Module;
use Livewire\Component;
use App\Models\Department;
use Livewire\WithPagination;
use App\Models\ModuleDownload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class BrowseModules extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedDepartments = [];
    public $semester = '';
    public $selectedCourseCode = '';
    public $departments;
    public $remainingQuota;
    public $isSearching = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'selectedDepartments' => ['except' => [], 'as' => 'dept'],
        'semester' => ['except' => ''],
        'selectedCourseCode' => ['except' => '', 'as' => 'code'],
    ];

    protected $listeners = [
        'quotaUpdated' => 'updateQuota',
        'clearSearch' => 'clearSearch',
        'searchUpdated' => 'handleSearchUpdate',
        'logout' => 'handleLogout'
    ];

    public function mount()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $this->departments = Department::select('id', 'department_name')->get();
        $this->selectedDepartments = is_array($this->selectedDepartments)
            ? array_map('strval', $this->selectedDepartments)
            : [];
        $this->updateQuota();
    }

    public function updatingSearch()
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $this->isSearching = true;
        $this->resetPage();
        $this->dispatch('searchStateChanged', isSearching: true);
    }

    public function updatedSearch($value)
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $this->isSearching = false;
        $this->resetPage();
        $this->emitSearchStatus();
        $this->dispatch('searchUpdated');
        $this->dispatch('searchStateChanged', isSearching: false);
    }

    public function handleSearchUpdate($search = null)
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        if ($search !== null) {
            $this->search = $search;
        }
        $this->resetPage();
        $this->emitSearchStatus();
    }

    public function toggleDepartment($departmentId)
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $departmentId = (string)$departmentId;

        if (in_array($departmentId, $this->selectedDepartments)) {
            $this->selectedDepartments = array_values(array_diff($this->selectedDepartments, [$departmentId]));
        } else {
            $this->selectedDepartments[] = $departmentId;
        }

        $this->resetPage();
        $this->emitSearchStatus();
    }

    public function setSemester($semester)
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        if ($this->semester === $semester) {
            $this->semester = ''; // Toggle off if clicking the same
        } else {
            $this->semester = $semester;
        }

        $this->resetPage();
        $this->emitSearchStatus();
    }

    public function setCourseCode($courseCode)
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        if ($this->selectedCourseCode === $courseCode) {
            $this->selectedCourseCode = ''; // Toggle off if clicking the same
        } else {
            $this->selectedCourseCode = $courseCode;
        }

        $this->resetPage();
        $this->emitSearchStatus();
    }

    public function clearFilters()
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $this->search = '';
        $this->selectedDepartments = [];
        $this->semester = '';
        $this->selectedCourseCode = '';
        $this->resetPage();
        $this->emitSearchStatus();
        $this->dispatch('searchUpdated', search: '');
    }

    public function clearSearch()
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        $this->search = '';
        $this->isSearching = false;
        $this->resetPage();
        $this->emitSearchStatus();
        $this->dispatch('searchUpdated', search: '');
        $this->dispatch('searchStateChanged', isSearching: false);
    }

    protected function emitSearchStatus()
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        $hasResults = Module::query()
            ->where('status', 'published')
            // Filter by user's course_id
            ->where('course_id', $user->course_id)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('course_code', 'like', '%'.$this->search.'%');
                });
            })
            ->when(!empty($this->selectedDepartments), function($query) {
                $query->whereIn('department_id', $this->selectedDepartments);
            })
            ->when($this->semester, function($query) {
                $query->where('semester', $this->semester);
            })
            ->when($this->selectedCourseCode, function($query) {
                $query->where('course_code', $this->selectedCourseCode);
            })
            ->exists();

        $this->dispatch('updateSearchStatus', [
            'search' => $this->search,
            'hasResults' => $hasResults
        ]);
    }

    public function updateQuota($data = null)
    {
        // Check authentication before proceeding
        if (!Auth::check()) {
            $this->redirect('/login');
            return;
        }

        if ($data && isset($data['remainingQuota'])) {
            $this->remainingQuota = $data['remainingQuota'];
        } else {
            $this->remainingQuota = max(0, 5 - ModuleDownload::where('user_id', Auth::id())
                ->whereDate('downloaded_at', Carbon::today())
                ->count());
        }

        $this->dispatch('quotaUpdated', remainingQuota: $this->remainingQuota);
    }

    public function handleLogout()
    {
        // Clear any Livewire state that might cause conflicts
        $this->reset();
        $this->dispatch('livewire:logout');

        // Redirect to login page
        $this->redirect('/login');
    }

    public function render()
    {
        // Check authentication before rendering
        if (!Auth::check()) {
            $this->redirect('/login');
            return view('livewire.browse-modules', [
                'modules' => collect(),
                'courseCodeGroups' => collect(),
                'remainingQuota' => 0
            ]);
        }

        try {
            $user = Auth::user();

            // Get course code groups with module counts
            $courseCodeGroups = Module::where('course_id', $user->course_id)
                ->where('status', 'published')
                ->when($this->semester, function($q) {
                    $q->where('semester', $this->semester);
                })
                ->when($this->search && !$this->selectedCourseCode, function($q) {
                    $q->where(function($query) {
                        $query->where('course_code', 'like', '%'.$this->search.'%')
                              ->orWhere('title', 'like', '%'.$this->search.'%');
                    });
                })
                ->select('course_code')
                ->selectRaw('COUNT(*) as modules_count')
                ->groupBy('course_code')
                ->orderBy('course_code')
                ->get();

            $modules = collect();
            $recentModules = collect();

            // Load recent modules for the "For You" feed if no course code is selected
            if (!$this->selectedCourseCode && empty($this->search) && empty($this->semester)) {
                $recentModules = Module::query()
                    ->where('status', 'published')
                    ->with([
                        'user' => fn($q) => $q->select('id', 'first_name', 'middle_initial', 'last_name', 'profile_picture', 'department_id'),
                        'user.department' => fn($q) => $q->select('id', 'department_name'),
                    ])
                    ->where('course_id', $user->course_id)
                    ->where('department_id', $user->department_id)
                    ->latest()
                    ->take(6)
                    ->get();
            }

            // Only load paginated modules when a course code is selected
            if ($this->selectedCourseCode) {
                $modulesQuery = Module::query()
                    ->where('status', 'published')
                    ->with([
                        'user' => fn($q) => $q->select('id', 'first_name', 'middle_initial', 'last_name', 'profile_picture', 'department_id'),
                        'user.department' => fn($q) => $q->select('id', 'department_name'),
                    ])
                    ->where('course_id', $user->course_id)
                    ->where('course_code', $this->selectedCourseCode)
                    ->when($this->search, function($query) {
                        $query->where(function($q) {
                            $q->where('title', 'like', '%'.$this->search.'%');
                        });
                    })
                    ->when($this->semester, function($query) {
                        $query->where('semester', $this->semester);
                    })
                    ->latest();

                $modules = $modulesQuery->paginate(9);
            }

            return view('livewire.browse-modules', [
                'modules' => $modules,
                'recentModules' => $recentModules,
                'courseCodeGroups' => $courseCodeGroups,
                'remainingQuota' => $this->remainingQuota
            ]);
        } catch (\Exception $e) {
            Log::error('BrowseModules render error: ' . $e->getMessage());
            $this->redirect('/login');
            return view('livewire.browse-modules', [
                'modules' => collect(),
                'recentModules' => collect(),
                'courseCodeGroups' => collect(),
                'remainingQuota' => 0
            ]);
        }
    }
}
