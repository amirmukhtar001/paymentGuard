<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Models\Section;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Section::with(['parent'])->withTrashed();

            return datatables()->eloquent($data)
                ->addColumn('title', function ($row) {
                    return e($row->title);
                })
                ->addColumn('parent', function ($row) {
                    return $row->parent->title ?? '-';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return renderActionButtons($row, [
                        'edit' => 'settings.sections.edit',
                        'destroy' => 'settings.sections.delete'
                    ]);
                })
                ->filter(function ($query) {
                    if (request()->has('search') && !empty(request()->get('search')['value'])) {
                        $searchTerm = request()->get('search')['value'];
                        $query->where(function($q) use ($searchTerm) {
                            $q->where('title', 'LIKE', "%{$searchTerm}%")
                              ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                              ->orWhereHas('parent', function($parentQuery) use ($searchTerm) {
                                  $parentQuery->where('title', 'LIKE', "%{$searchTerm}%");
                              });
                        });
                    }
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('settings.sections.index', [
            'title' =>  'Unit Listings',
            'new_route' => ['settings.sections.create', 'New ' . config('settings.section_title')]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Section $item)
    {
        $data = [
            'title' => 'Create a New '.config('settings.section_title', 'Section'),
            'back_route' => ['settings.sections.list', str_plural(config('settings.section_title', 'Section')).' List'],
            'new_route' => ['settings.sections.create', 'New '.config('settings.section_title', 'Section')],
            'item' => $item,
            'parent_sections' => Section::orderBy('title')->pluck('title', 'id'),
        ];

        return view('settings.sections.form', $data);
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
        ]);

        $inputs = $request->all();
        $inputs['user_id'] = Auth::user()->id;

        $section = Section::create($inputs);

        if ($section) {
            Session::flash('success', 'New ' . config('settings.section_title') . ' created successfully');
            return redirect(route('settings.sections.list'));
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
        $item = Section::find(Crypt::decrypt($id));

        $data = [
            'title' => 'Update '.config('settings.section_title', 'Section'),
            'back_route' => ['settings.sections.list', str_plural(config('settings.section_title', 'Section')).' List'],
            'new_route' => ['settings.sections.create', 'New '.config('settings.section_title', 'Section')],
            'item' => $item,
            'parent_sections' => Section::where('id', '!=', $item->id)->orderBy('title')->pluck('title', 'id'),
        ];

        return view('settings.sections.form', $data);
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
        $section = Section::find($id);

        $this->validate($request, [
            'title' => 'required',
        ]);

        $inputs = $request->all();

        if ($section->fill($inputs)->save()) {
            Session::flash('success', config('settings.section_title') . ' updated successfully');
            return redirect(route('settings.sections.list'));
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
        $section = Section::find($id);

        if ($section->delete()) {
            Session::flash('success', config('settings.section_title') . ' deleted successfully');
            return redirect()->back();
        } else {
            Session::flash('error', 'Something went wrong, please try later');
            return redirect()->back();
        }
    }
}
