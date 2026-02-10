<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Models\Menu;
use App\Models\MyApp;


class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */


    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax() || $request->wantsJson()) {
            $menus = Menu::query()
                ->with(['parent'])
                ->withTrashed()->orderBy('order');

            return datatables()->of($menus)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return '<i class="' . $row->icon . '"></i> <strong>' . e($row->title) . '</strong>';
                })
                ->addColumn('parent', function ($row) {
                    return optional($row->parent)->title ?? '-';
                })
                ->addColumn('action', function ($row) use ($user) {
                    $actions = [];

                    // if ($user->hasPermission('settings.menus.edit')) {
                    $actions['edit'] = 'settings.menus.edit';
                    // }

                    // if ($user->hasPermission('settings.menus.delete')) {
                    $actions['destroy'] = 'settings.menus.delete';
                    // }

                    return renderActionButtons($row, $actions);
                })
                ->rawColumns(['action', 'title'])
                ->toJson();
        }

        return view('settings.menus.index', [
            'title' => 'Menus List',
            'new_route' => ['settings.menus.create', 'New Menu']
        ]);
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Menu $item)
    {
        $data = [
            'title' => 'Create a New Menu',
            'back_route' => ['settings.menus.list', 'Menus List'],
            'new_route' => ['settings.menus.create', 'New Menu'],
            'item' => $item,
            'apps' => MyApp::pluck('title', 'id'),
            'menus_parents' => Menu::whereNull('parent_id')->pluck('title', 'id')
        ];

        return view('settings.menus.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'order' => 'required'
        ]);

        $inputs = $request->all();
        $inputs['is_collapsible'] = $request->has('is_collapsible') ? 'yes' : 'no';
        $menu = Menu::create($inputs);

        if ($menu) {
            Session::flash('success', 'New menu created successfully');
            return redirect(route('settings.menus.list'));
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
        return view('settings.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $item = Menu::find($id);
        // dd($item);

        $data = [
            'title' => 'Update Menu',
            'back_route' => ['settings.menus.list', 'Menus List'],
            'new_route' => ['settings.menus.create', 'New Menu'],
            'item' => $item,
            'apps' => MyApp::pluck('title', 'id'),
            'menus_parents' => Menu::whereNull('parent_id')->where('id', '!=', $id)->pluck('title', 'id')
        ];

        return view('settings.menus.form', $data);
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
        $menu = Menu::find($id);
        // dd($menu);

        $this->validate($request, [
            'title' => 'required',
            'order' => 'required'
        ]);

        $inputs = $request->all();
        $inputs['is_collapsible'] = $request->has('is_collapsible') ? 'yes' : 'no';

        if ($menu->fill($inputs)->save()) {
            Session::flash('success', 'Menu updated successfully');
            return redirect(route('settings.menus.list'));
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
        $menu = Menu::find($id);

        if ($menu->delete()) {
            Session::flash('success', 'Menu deleted successfully');
            return redirect()->back();
        } else {
            Session::flash('error', 'Something went wrong, please try later');
            return redirect()->back();
        }
    }


    public function menuByAppId(Request $request)
    {
        // Removed app_id filtering - return all menus
        $menus = Menu::pluck('title', 'id');
        return $menus;
    }
}
