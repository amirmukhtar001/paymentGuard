<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Company;
use App\Models\MyRole;
use App\Models\Section;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();

            $items = User::withTrashed()
                ->with(['section', 'company', 'permissions', 'parent', 'children', 'roles', 'verifiedBy']);

            if ($request->filled('role_id')) {
                $items->whereHas('roles', function ($q) use ($request) {
                    $q->where('roles.id', $request->role_id);
                });
            }

            if ($request->filled('org_id')) {
                $items->where('company_id', $request->org_id);
            }

            if ($request->filled('unit_id')) {
                $items->where('section_id', $request->unit_id);
            }

            return DataTables::of($items)
                ->filter(function ($query) use ($request) {
                    if ($search = $request->get('search')['value']) {
                        $query->where(function ($q) use ($search) {
                            $q->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                                ->orWhereRaw('LOWER(email) LIKE ?', ["%" . strtolower($search) . "%"])
                                ->orWhereRaw('LOWER(username) LIKE ?', ["%" . strtolower($search) . "%"])
                                ->orWhereHas('company', function ($companyQuery) use ($search) {
                                    $companyQuery->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"]);
                                })
                                ->orWhereHas('section', function ($sectionQuery) use ($search) {
                                    $sectionQuery->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"]);
                                })
                                ->orWhereHas('roles', function ($roleQuery) use ($search) {
                                    $roleQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
                                })
                                ->orWhereHas('parent', function ($parentQuery) use ($search) {
                                    $parentQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
                                });
                        });
                    }
                })
                ->addColumn('actions', function ($item) {
                    $encryptedId = Crypt::encrypt($item->id);
                    $buttons = "<a href='" . route('settings.user_logs.all', ['user_id' => $encryptedId]) . "' class='btn btn-info btn-xs mb-1'>
                                <i class='bx bx-time-five'></i>
                            </a>";

                    if ($item->username !== 'admin') {
                        if ($item->trashed()) {
                            $buttons .= "<form action='" . route('settings.users-mgt.restore', ['id' => $encryptedId]) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure to restore user?\");'>
                                        " . csrf_field() . "
                                        <button type='submit' class='btn btn-success btn-xs' title='Restore'>
                                            <i class='bx bx-undo'></i>
                                        </button>
                                    </form>";
                        } else {
                            $buttons .= "<a href='" . route('settings.users-mgt.edit', ['id' => $encryptedId]) . "' class='btn btn-warning btn-xs mb-1'>
                                        <i class='bx bx-edit'></i>
                                    </a>
                                    <a href='" . route('settings.users-mgt.show', ['id' => $encryptedId]) . "' class='btn btn-primary btn-xs mb-1'>
                                        <i class='bx bx-cog'></i>
                                    </a>";

                            // Status toggle button
                            if ($item->status == 1) {
                                $buttons .= "<form action='" . route('settings.users-mgt.toggle-status', ['id' => $encryptedId]) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure to deactivate this user?\");'>
                                            " . csrf_field() . "
                                            <button type='submit' class='btn btn-secondary btn-xs mb-1' title='Deactivate'>
                                                <i class='bx bx-block'></i>
                                            </button>
                                        </form>";
                            } else {
                                $buttons .= "<form action='" . route('settings.users-mgt.toggle-status', ['id' => $encryptedId]) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure to activate this user?\");'>
                                            " . csrf_field() . "
                                            <button type='submit' class='btn btn-success btn-xs mb-1' title='Activate'>
                                                <i class='bx bx-check'></i>
                                            </button>
                                        </form>";
                            }

                            $buttons .= "<form action='" . route('settings.users-mgt.delete', ['id' => $encryptedId]) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure to delete user?\");'>
                                        " . csrf_field() . method_field('DELETE') . "
                                        <button type='submit' class='btn btn-danger btn-xs' title='Delete'>
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>";
                        }
                    }

                    return $buttons;
                })
                ->addColumn(
                    'roles',
                    fn($item) =>
                    $item->roles->map(fn($r) => "<span class='badge bg-info'>{$r->name}</span>")->implode(' ')
                )
                ->addColumn(
                    'status',
                    function($item) {
                        if ($item->trashed()) {
                            return "<span class='badge bg-danger'>Deleted</span>";
                        } else {
                            return $item->status == 1
                                ? "<span class='badge bg-success'>Active</span>"
                                : "<span class='badge bg-warning'>Inactive</span>";
                        }
                    }
                )
                ->addColumn('parent_name', fn($item) => $item->parent->name ?? '')
                ->addColumn('children_count', fn($item) => $item->children->count() ?? '')
                ->addColumn('company_title', fn($item) => $item->company->title ?? '')
                ->addColumn('section_title', fn($item) => $item->section->title ?? '')
                ->addColumn('districts_list', fn ($item) => '-')
                ->addColumn('tehsils_list', fn ($item) => '-')
                ->addColumn('unioncouncils_list', fn ($item) => '-')
                ->addColumn('villages_list', fn ($item) => '-')
                ->addColumn('verified_by_name', function ($item) {
                    return $item->verifiedBy ? $item->verifiedBy->name : 'N/A';
                })
                ->addColumn('verified_at_formatted', function ($item) {
                    return $item->verified_at ? $item->verified_at->format('d M Y H:i') : 'N/A';
                })
                ->rawColumns(['status', 'actions', 'roles', 'districts_list', 'tehsils_list', 'unioncouncils_list', 'villages_list', 'verified_by_name', 'verified_at_formatted'])
                ->make(true);
        }

        return view('settings.users_mgt.index', [
            'title'     => 'Users List',
            'new_route' => ['settings.users-mgt.create', 'New User'],
            'roles'     => MyRole::select('id', 'name')->get(),
            'companies' => Company::select('id', 'title')->get(),
            'sections'  => Section::select('id', 'title')->get(),
        ]);
    }

    public function usersList(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Only AJAX requests allowed'], 400);
        }

        $user = Auth::user();

        $items = User::withTrashed()
            ->with(['section', 'company', 'permissions', 'parent', 'children', 'roles', 'verifiedBy']);

        if ($request->filled('role_id')) {
            $items->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        if ($request->filled('org_id')) {
            $items->where('company_id', $request->org_id);
        }

        if ($request->filled('unit_id')) {
            $items->where('section_id', $request->unit_id);
        }

        $res = DataTables::of($items)
            ->filter(function ($query) use ($request) {
                if ($search = $request->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhereRaw('LOWER(email) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhereRaw('LOWER(username) LIKE ?', ["%" . strtolower($search) . "%"])
                            ->orWhereHas('company', function ($companyQuery) use ($search) {
                                $companyQuery->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"]);
                            })
                            ->orWhereHas('section', function ($sectionQuery) use ($search) {
                                $sectionQuery->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"]);
                            })
                            ->orWhereHas('roles', function ($roleQuery) use ($search) {
                                $roleQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
                            })
                            ->orWhereHas('parent', function ($parentQuery) use ($search) {
                                $parentQuery->whereRaw('LOWER(name) LIKE ?', ["%" . strtolower($search) . "%"]);
                            });
                    });
                }
            })
            ->addColumn('actions', function ($item) {

                $encryptedId = Crypt::encrypt($item->id);

                $buttons = "";

                $buttons .= "<a href='" . route('settings.user_logs.all', ['user_id' => $encryptedId]) . "' class='btn btn-info btn-xs mb-1'>
                        <i class='bx bx-time-five'></i>
                    </a>";

                if ($item->username !== 'admin') {

                    if ($item->trashed()) {
                        $buttons .= "<form action='" . route('settings.users-mgt.restore', ['id' => $encryptedId]) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure to restore user?\");'>
                                " . csrf_field() . "
                                <button type='submit' class='btn btn-success btn-xs' title='Restore'>
                                    <i class='bx bx-undo'></i>
                                </button>
                            </form>";
                    } else {

                        $buttons .= "
                            <a href='" . route('settings.users-mgt.edit', ['id' => $encryptedId]) . "' class='btn btn-warning btn-xs mb-1'>
                                <i class='bx bx-edit'></i>
                            </a>
                            <a href='" . route('settings.users-mgt.show', ['id' => $encryptedId]) . "' class='btn btn-primary btn-xs mb-1'>
                                <i class='bx bx-cog'></i>
                            </a>
                            ";

                        // Status toggle button
                        if ($item->status == 1) {
                            $buttons .= "<form action='" . route('settings.users-mgt.toggle-status', ['id' => $encryptedId]) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure to deactivate this user?\");'>
                                        " . csrf_field() . "
                                        <button type='submit' class='btn btn-secondary btn-xs mb-1' title='Deactivate'>
                                            <i class='bx bx-block'></i>
                                        </button>
                                    </form>";
                        } else {
                            $buttons .= "<form action='" . route('settings.users-mgt.toggle-status', ['id' => $encryptedId]) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure to activate this user?\");'>
                                        " . csrf_field() . "
                                        <button type='submit' class='btn btn-success btn-xs mb-1' title='Activate'>
                                            <i class='bx bx-check'></i>
                                        </button>
                                    </form>";
                        }

                        $buttons .= "<form action='" . route('settings.users-mgt.delete', ['id' => $encryptedId]) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure to delete user?\");'>
                                " . csrf_field() . method_field('DELETE') . " <button type='submit' class='btn btn-danger btn-xs' title='Delete'>
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                        ";
                    }
                }
                return $buttons;
            })
            ->addColumn('roles', function ($item) {
                $roles = '';
                foreach ($item->roles as $r) {
                    $roles .= '<span class="badge bg-info">' . $r->name . '</span>';
                }
                return $roles;
            })
            ->addColumn('status', function ($item) {
                if ($item->trashed()) {
                    return "<span class='badge bg-danger'>Deleted</span>";
                } else {
                    return $item->status == 1
                        ? "<span class='badge bg-success'>Active</span>"
                        : "<span class='badge bg-warning'>Inactive</span>";
                }
            })
            ->addColumn('parent_name', function ($item) {
                return $item->parent->name ?? "";
            })->addColumn('children_count', function ($item) {
                return $item->children->count() ?? "";
            })->addColumn('company_title', function ($item) {
                return $item->company->title ?? "";
            })->addColumn('section_title', function ($item) {
                return $item->section->title ?? "";
            })->addColumn('districts_list', fn ($item) => '-')
            ->addColumn('tehsils_list', fn ($item) => '-')
            ->addColumn('unioncouncils_list', fn ($item) => '-')
            ->addColumn('villages_list', fn ($item) => '-')
            ->addColumn('verified_by_name', function ($item) {
                return $item->verifiedBy ? $item->verifiedBy->name : 'N/A';
            })->addColumn('verified_at_formatted', function ($item) {
                return $item->verified_at ? $item->verified_at->format('d M Y H:i') : 'N/A';
            })->rawColumns(['status', 'actions', 'roles', 'districts_list', 'tehsils_list', 'unioncouncils_list', 'villages_list', 'verified_by_name', 'verified_at_formatted'])
            ->make(true);


        return $res;
        // return response($res, 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(User $item)
    {
        $data = [
            'title' => 'Create a New User',
            'back_route' => ['settings.users-mgt.list', 'Users List'],
            'new_route' => ['settings.users-mgt.create', 'New User'],
            'item' => $item,
            'roles' => MyRole::pluck('name', 'id'),
            'sections' => collect([]),
            'parent_users' => collect([])
        ];

        $companies_dd_data = Company::with([
            'children' => function ($q) {
                $q->with('children');
            }
        ])->whereNull('parent_id')->get();

        $companies_dd = [];
        $this->buildCompanyTree($companies_dd_data, $companies_dd);
        $data['companies_dd'] = $companies_dd;

        // Load sections and parent users if a company was previously selected
        if (old('company_id')) {
           // $data['sections'] = Section::where('company_id', old('company_id'))
             //   ->pluck('title', 'id');
            $data['parent_users'] = User::where(
                'company_id',
                old('company_id')
            )->pluck('name', 'id');
        }

        // Preserve dynamic dropdown selections after validation errors (district/tehsil/uc/village removed)
        $data['oldDistricts'] = collect();
        $data['oldTehsils'] = collect();
        $data['oldUcs'] = collect();
        $data['oldVcs'] = collect();

        return view('settings.users_mgt.form', $data);
    }


    private function buildCompanyTree($companies, &$result, $prefix = "")
    {
        foreach ($companies as $company) {
            $result[$company->id] = $prefix . $company->title;

            if ($company->children->count() > 0) {
                $this->buildCompanyTree($company->children, $result, $prefix . $company->title . " -> ");
            }
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email',
          //  'cnic' => 'required|unique:users,cnic',
            'username' => 'required|string|min:6|unique:users,username',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'designation' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $rawPassword = $request->password; // Save for email
        $inputs = $request->except('password');
        $inputs['password'] = Hash::make($rawPassword);
        $inputs['is_otp_enabled'] = $request->is_otp_enabled ?? 0;
        $inputs['is_pincode_enabled'] = $request->is_pincode_enabled ?? 0;
        $inputs['status'] = 1; // Default active status
        $inputs['verified_by'] = Auth::id();
        $inputs['verified_at'] = now();

        $user = User::create($inputs);

        if ($user) {
            // Assign roles
            $user->roles()->sync($request->get('role_id'));
            $roleName = optional($user->roles->first())->name ?? 'User';

            // Assign districts
            if ($request->has('district_ids')) {
                $user->districts()->attach($request->district_ids ?? []);
            }

            // Assign tehsils
            if ($request->has('tehsil_ids')) {
                $user->tehsils()->attach($request->tehsil_ids ?? []);
            }

            // Assign union councils
            if ($request->has('union_council_ids')) {
                $user->unioncouncils()->attach($request->union_council_ids ?? []);
            }

            // Assign VCs
            if ($request->has('vc_ids')) {
                $user->ncVcLists()->attach($request->vc_ids ?? []);
            }

            $this->syncProfileImage($user, $request);

            // ✅ Send welcome email if enabled
            if (setting('enable_register_email') && $user->email) {
                try {
                    $url = url('/login');
                    Mail::to($user->email)->send(new \App\Mail\NewUserWelcome($user, $rawPassword, $roleName, $url));
                } catch (\Exception $e) {
                    // Log error but don't throw
                    Log::warning('User welcome email failed to send: ' . $e->getMessage());
                }
            }

            Session::flash('success', 'New user created successfully');
            return redirect(route('settings.users-mgt.list'));
        }

        Session::flash('error', 'Something went wrong, please try later');
        return redirect()->back()->withInput();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

        $id = Crypt::decrypt($id);
        $item = User::with([
            'permissions',
            'company',
            'section',
            'roles',
            'parent'
        ])->find($id);

        $data = [
            'title' => 'User Permissions',
            'back_route' => ['settings.users-mgt.list', 'Users List'],
            'new_route' => ['settings.users-mgt.create', 'New User'],
            'item' => $item
        ];

        // Get all menus with permissions (removed app-based grouping)
        // Load top-level menus with their recursive children and permissions
        $menus_with_permissions = \App\Models\Menu::with([
            'myPermissions' => function ($q) {
                $q->whereNull('deleted_at')->orderBy('name');
            },
            'childrenRecursive' => function ($q) {
                $q->with(['myPermissions' => function ($q2) {
                    $q2->whereNull('deleted_at')->orderBy('name');
                }])->whereNull('deleted_at');
            }
        ])
            ->whereNull('deleted_at')
            ->whereNull('parent_id') // Only top-level menus
            ->orderBy('order')
            ->orderBy('title')
            ->get();

        // Create a simple structure that matches the blade template
        $data['trees'] = collect([
            (object)[
                'id' => 1,
                'title' => 'All Permissions',
                'icon' => 'bx bx-lock-open',
                'menus' => $menus_with_permissions
            ]
        ]);

        return view('settings.users_mgt.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $item = User::with(['roles', 'districts', 'tehsils', 'unioncouncils', 'ncVcLists'])->find($id);

        $selectedCompany = old('company_id', $item->company_id);
        $data = [
            'title' => 'Edit User',
            'back_route' => ['settings.users-mgt.list', 'Users List'],
            'new_route' => ['settings.users-mgt.create', 'New User'],
            'item' => $item,
            'roles' => MyRole::pluck('name', 'id'),
            'sections' => Section::pluck('title', 'id'),
            'parent_users' => $selectedCompany ? User::where([
                ['company_id', '=', $selectedCompany],
                ['id', '!=', $id]
            ])->pluck('name', 'id') : collect([])
        ];


        $companies_dd_data = Company::with([
            'children' => function ($q) {
                $q->with('children');
            }
        ])->whereNull('parent_id')->get();

        $companies_dd = [];
        foreach ($companies_dd_data as $cdd) {
            $companies_dd[$cdd->id] = $cdd->title;
            if ($cdd->children->count() > 0) {
                foreach ($cdd->children as $cdd_cl1) {
                    $companies_dd[$cdd_cl1->id] = $cdd->title . " -> " . $cdd_cl1->title;

                    if ($cdd_cl1->children->count() > 0) {
                        foreach ($cdd_cl1->children as $cdd_cl2) {
                            $companies_dd[$cdd_cl2->id] = $cdd->title . " -> " . $cdd_cl1->title . " -> " . $cdd_cl2->title;
                        }
                    }
                }
            }
        }
        $data['companies_dd'] = $companies_dd;

        // Preserve dropdown selections after validation errors
        // $districtIds = old('district_ids', $item->districts->pluck('id')->toArray());
        // $data['oldDistricts'] = $districtIds
        //     ? District::whereIn('id', $districtIds)->pluck('title', 'id')
        //     : collect();

        // $tehsilIds = old('tehsil_ids', $item->tehsils->pluck('id')->toArray());
        // $data['oldTehsils'] = $tehsilIds
        //     ? Tehsil::whereIn('id', $tehsilIds)->pluck('title', 'id')
        //     : collect();

        // $ucIds = old('union_council_ids', $item->unioncouncils->pluck('id')->toArray());
        // $data['oldUcs'] = $ucIds
        //     ? UnionCouncil::whereIn('id', $ucIds)->pluck('name', 'id')
        //     : collect();

        // $vcIds = old('vc_ids', $item->ncVcLists->pluck('id')->toArray());
        // $data['oldVcs'] = $vcIds
        //     ? NcVcList::whereIn('id', $vcIds)->pluck('name', 'id')
        //     : collect();

        return view('settings.users_mgt.form', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        $id = Crypt::decrypt($id);
        $user = User::find($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            // 'cnic' => 'required|unique:users,cnic,' . $id,
            // 'company_id' => 'required',
            // 'section_id' => 'required',
            'username' => 'required|string|min:6|unique:users,username,' . $id . '|regex:/^[a-zA-Z0-9]+$/',
            'designation' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
        ], [
            'username.regex' => 'Username cannot contain spaces. Only letters and numbers are allowed.',
        ]);


        $inputs = $request->except('password');
        $inputs['is_otp_enabled'] = $request->is_otp_enabled ?? 0;
        $inputs['is_pincode_enabled'] = $request->is_pincode_enabled ?? 0;
        if ($request->has('reset_pincode') && $request->get('reset_pincode') == 1) {
            $inputs['pincode'] = null;
        }

        if ($request->has('password') & strlen($request->get('password')) > 0) {
            $inputs['password'] = Hash::make($request->get('password'));
        }

        if ($user->fill($inputs)->save()) {



            // Assign districts to user by Id
            if ($request->has('district_ids')) {
                $user->districts()->sync($request->district_ids);
            }

            // Assign tehsils to user by Id
            if ($request->has('tehsil_ids')) {
                $user->tehsils()->sync($request->tehsil_ids);
            }

            // Assign union councils to user by Id
            if ($request->has('union_council_ids')) {
                $user->unioncouncils()->sync($request->union_council_ids);
            }

            // Assign VCs to user by Id
            if ($request->has('vc_ids')) {
                $user->ncVcLists()->sync($request->vc_ids);
            }


            $user->roles()->sync($request->get('role_id'));
            $this->syncProfileImage($user, $request);
            Session::flash('success', 'User updated successfully');
            return redirect(route('settings.users-mgt.list'));
        } else {
            Session::flash('error', 'Something went wrong, please try later');
            return redirect()->back();
        }
    }

    protected function syncProfileImage(User $user, Request $request): void
    {
        // Profile image upload removed (Media module not in scope for cash reconciliation MVP).
        if (! $request->hasFile('profile_picture')) {
            return;
        }
    }

    public function userPermissionsSave(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);

        if (is_null($request->get('checked_permissions'))) {
            $permissions = [];
        } else {
            $permissions = explode(",", $request->get('checked_permissions'));
        }

        if ($user->permissions()->sync($permissions)) {
            // Clear permission cache for this user
            \Illuminate\Support\Facades\Cache::forget("user_permissions_{$user->id}");
            \Illuminate\Support\Facades\Cache::forget("user_role_permissions_{$user->id}");

            Session::flash('success', 'User updated successfully');
            return redirect()->back();
        } else {
            Session::flash('error', 'Something went wrong, please try later');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::find($id);

        if ($user->delete()) {
            Session::flash('success', 'User deleted successfully');
            return redirect()->back();
        } else {
            Session::flash('error', 'Something went wrong, please try later');
            return redirect()->back();
        }
    }

    public function restore($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('success', 'User restored successfully');
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::findOrFail($id);

        $newStatus = $user->status == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        $user->update([
            'status' => $newStatus,
            'verified_by' => Auth::id(),
            'verified_at' => now()
        ]);

        Session::flash('success', "User {$statusText} successfully");
        return redirect()->back();
    }


    /**
     * change email address
     */
    public function myProfile()
    {

        $user = Auth::user();

        $data = [
            'title' => 'My Profile',
            'user' => $user
        ];

        return view('settings.users_mgt.my_profile', $data);
    }


    /**
     * change email
     */
    public function myProfileAct(Request $request)
    {

        $user = Auth::user();
        // dd($user);
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|min:6|unique:users,username,' . $user->id,
        ]);

        $name = $request->get('name');
        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');

        // dd($new_email.$old_email.$password);

        $user = User::where([
            ['username', '=', $user->username]
        ])->get();
        //dd(Hash::check($password, $user[0]->password));
        if (Hash::check($password, $user[0]->password)) {

            $inputs = [
                'username' => $username,
                'email' => $email
            ];

            User::findOrFail($user[0]->id)->fill($inputs)->save();
            session()->flash('success', 'Profile updated successfully');
            return redirect()->back();
        } else {
            //dd("Error");
            session()->flash('error', 'Incorrect password');
            return redirect()->back();
        }
    }




    /**
     * change password
     */
    public function changePassword()
    {


        $data = [
            'title' => 'Update Password'
        ];

        return view('settings.users_mgt.change_password', $data);
    }


    /**
     * change the pass
     */
    public function changePasswordAct(Request $request)
    {

        $user = Auth::user();
        $this->validate($request, [
            'new_password' => 'required|same:conf_new_password',
            'conf_new_password' => 'required',
            'current_password' => 'required'
        ]);


        $user = User::where([
            ['id', '=', $user->id]
        ])->get();

        if (Hash::check($request->get('current_password'), $user[0]->password)) {

            User::findOrFail($user[0]->id)->fill([
                'password' => Hash::make($request->get('new_password'))
            ])->save();
            session()->flash('success', 'Password changed successfully');
            return redirect()->back();
        } else {
            session()->flash('error', 'Incorrect password');
            return redirect()->back();
        }
    }

    public function configPincode()
    {
        $data = [
            'title' => 'Configure Pincode'
        ];

        return view('settings.users_mgt.config_pincode', $data);
    }

    public function configPincodeAct(Request $request)
    {

        $user = Auth::user();
        $this->validate($request, [
            'pincode' => 'required',
            'current_password' => 'required'
        ]);


        $user = User::where([
            ['id', '=', $user->id]
        ])->first();

        if (Hash::check($request->get('current_password'), $user->password)) {
            User::findOrFail($user->id)->fill([
                // 'pincode' => Crypt::encrypt($request->pincode),
                'pincode' => $request->pincode,
                'is_pincode_enabled' => 1,
            ])->save();
            session()->flash('success', 'Pincode saved successfully');
            return redirect()->back();
        } else {
            session()->flash('error', 'Incorrect password');
            return redirect()->back();
        }
    }

    public function import()
    {
        $data = [
            'title' => 'Import Users'
        ];

        return view('settings.users_mgt.import', $data);
    }

    public function importUsers(Request $request)
    {
        try {
            $request->validate([
                'upload_file' => 'required|file|mimes:csv',
            ]);

            if ($request->hasFile('upload_file')) {
                $file = $request->file('upload_file');

                $handle = fopen($file->getPathname(), 'r');

                // Skip the header row
                fgetcsv($handle);
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {

                    // dd($row);
                    // Check if username already exists
                    if (User::where('username', $row[6])->exists()) {
                        continue; // Skip this row
                    }

                    if (User::where('email', $row[11])->exists()) {
                        continue; // Skip this row
                    }


                    // Create and save the user (district/tehsil attachment removed)
                    $user = new User();
                    $user->name = $row[3];
                    $user->description = $row[4];
                    $user->contact_number = $row[5];
                    $user->username = $row[6];
                    $user->password = bcrypt($row[7]);
                    $user->company_id = 2;
                    // $user->focal_person = $row[8];
                    $user->email = $row[11];
                    $user->save();

                    if (strcasecmp($row[4], 'FIELD ASSISTANT') == 0) {
                        $user->roles()->sync(46);
                    }

                    if (strcasecmp($row[4], 'AGRICULTURE OFFICER') == 0 || strcasecmp($row[4], 'SUBJECT MATTER SPECIALIST') == 0) {
                        $user->roles()->sync(47);
                    }

                    if (strcasecmp($row[4], 'DISTRICT DIRECTOR AGRICULTURE') == 0) {
                        $user->roles()->sync(48);
                    }

                    if (strcasecmp($row[4], 'CALL CENTER REPRESENTATIVE') == 0) {
                        $user->roles()->sync(105);
                    }
                }

                fclose($handle);

                // return "Users imported successfully.";
                return redirect()->back()->with('success', 'Users imported successfully.');
            } else {
                // return "No file uploaded.";
                return redirect()->back()->with('error', 'No file uploaded.');
            }
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }






    public function showConflicts(Request $request)
    {
        $roles = MyRole::all();
        $data = [
            'title' => 'Conflicted Union Councils',
            'conflicts' => collect(),
            'roles' => $roles,
        ];

        return view('settings.users_mgt.conflicts', $data);
    }

    public function checkUnAssigned(Request $request)
    {
        $roles = MyRole::pluck('name', 'slug');
        $data = [
            'title' => 'Unassigned Union Councils',
            'roles' => $roles,
            'districts' => collect(),
            'unusedUnionCouncils' => collect(),
        ];

        return view('settings.users_mgt.un_assigned_uc', $data);
    }
}
