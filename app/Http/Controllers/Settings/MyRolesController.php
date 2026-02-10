<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Models\MyRole;

class MyRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = MyRole::query()->withTrashed();

            return datatables()->of($items)
                ->addIndexColumn()
                ->addColumn('name', fn($row) => '<strong>' . e($row->name) . '</strong>')
                ->addColumn('slug', fn($row) => '<code>' . e($row->slug) . '</code>')
                ->addColumn('level', fn($row) => '<code>' . e($row->level) . '</code>')
                ->addColumn('action', function ($row) {
                    return renderActionButtons($row, [
                        'show' => 'settings.my-roles.show',
                        'edit' => 'settings.my-roles.edit',
                        'destroy' => 'settings.my-roles.delete',
                    ]);
                })
                ->rawColumns(['name', 'slug', 'level', 'action'])
                ->toJson();
        }

        return view('settings.my_roles.index', [
            'title' => 'Roles List',
            'new_route' => ['settings.my-roles.create', 'New Role'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(MyRole $item)
    {
        $data = [
            'title' => 'Create a New Role',
            'back_route' => ['settings.my-roles.list', 'Roles List'],
            'new_route' => ['settings.my-roles.create', 'New Role'],
            'item' => $item
        ];

        return view('settings.my_roles.form', $data);
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
            'slug' => 'required|unique:roles,slug'
        ]);

        $inputs = $request->all();
        $my_role = MyRole::create($inputs);

        if ($my_role) {
            Session::flash('success', 'New role created successfully');
            return redirect(route('settings.my-roles.list'));
        } else {
            Session::flash('error', 'Something went wrong, please try later');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $item = MyRole::with([
            'permissions'
        ])->find($id);

        $data = [
            'title' => 'Role Permissions',
            'back_route' => ['settings.my-roles.list', 'Roles List'],
            'new_route' => ['settings.my-roles.create', 'New Role'],
            'item' => $item
        ];

        // Get all menus with permissions (removed app-based grouping)
        // Load top-level menus with their children and permissions
        $menus_with_permissions = \App\Models\Menu::with([
            'myPermissions' => function($q) {
                $q->whereNull('deleted_at')->orderBy('name');
            },
            'childrenRecursive' => function($q) {
                $q->whereNull('deleted_at');
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

        return view('settings.my_roles.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $item = MyRole::find(Crypt::decrypt($id));

        $data = [
            'title' => 'Update Role',
            'back_route' => ['settings.my-roles.list', 'Roles List'],
            'new_route' => ['settings.my-roles.create', 'New Role'],
            'item' => $item
        ];

        return view('settings.my_roles.form', $data);
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
        $my_role = MyRole::find($id);

        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:roles,slug,' . $id
        ]);

        $inputs = $request->all();

        if ($my_role->fill($inputs)->save()) {
            Session::flash('success', 'Role updated successfully');
            return redirect(route('settings.my-roles.list'));
        } else {
            Session::flash('error', 'Something went wrong, please try later');
            return redirect()->back();
        }
    }


    public function rolePermissionsSave(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $role = MyRole::find($id);

        if (is_null($request->get('checked_permissions'))) {
            $permissions = [];
        } else {
            $permissions = explode(",", $request->get('checked_permissions'));
        }

        if ($role->permissions()->sync($permissions)) {
            // Clear permission cache for all users with this role
            $usersWithRole = $role->users;
            foreach ($usersWithRole as $user) {
                \Illuminate\Support\Facades\Cache::forget("user_permissions_{$user->id}");
                \Illuminate\Support\Facades\Cache::forget("user_role_permissions_{$user->id}");
            }
            // Clear gate permissions cache
            \Illuminate\Support\Facades\Cache::forget('permissions_for_gates');

            Session::flash('success', 'Role updated successfully');
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
        $my_role = MyRole::find($id);

        if ($my_role->delete()) {
            Session::flash('success', 'Role deleted successfully');
            return redirect()->back();
        } else {
            Session::flash('error', 'Something went wrong, please try later');
            return redirect()->back();
        }
    }
}
