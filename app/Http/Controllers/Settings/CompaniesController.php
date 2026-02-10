<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Section;
use App\Models\User;
use App\Traits\CommonMethods;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class CompaniesController extends Controller
{
    use CommonMethods;

    public function company_details(Request $request): \Illuminate\Http\JsonResponse
    {
        $company = Company::find($request->id);

        $sections = Section::with([
            'children' => function ($q) {
                $q->with('children');
            }
        ])->whereNull('parent_id');
        if ($request->has('section_id')) {
            $sections = $sections->whereNot('id', $request->get('section_id'));
        }
        $sections = $sections->get();
        $sections_dd = [];
        foreach ($sections as $cdd) {
            if ($request->has('section_id') && $request->get('section_id') == $cdd->id) {
                continue;
            }
            $sections_dd[$cdd->id] = $cdd->title;
            foreach ($cdd->children ?? [] as $cdd_cl1) {
                if ($request->has('section_id') && $request->get('section_id') == $cdd_cl1->id) {
                    continue;
                }
                $sections_dd[$cdd_cl1->id] = $cdd->title.' -> '.$cdd_cl1->title;
                foreach ($cdd_cl1->children ?? [] as $cdd_cl2) {
                    if ($request->has('section_id') && $request->get('section_id') == $cdd_cl2->id) {
                        continue;
                    }
                    $sections_dd[$cdd_cl2->id] = $cdd->title.' -> '.$cdd_cl1->title.' -> '.$cdd_cl2->title;
                }
            }
        }

        $users_r = User::with(['company', 'section'])
            ->where('company_id', $request->id);

        if ($request->has('current_user')) {
            $current_user_id = Crypt::decrypt($request->get('current_user'));
            $users_r = $users_r->whereNot('id', $current_user_id);
        }
        $users_r = $users_r->get();

        $users = [];
        foreach ($users_r as $u) {
            if ($request->has('user_id') && $request->get('user_id') == $u->id) {
                continue;
            }
            $user_title = $u->name;
            if ($u->section) {
                $user_title .= ' ('.$u->section->title.')';
            }
            $users[$u->id] = $user_title;
        }

        $isHod = 0;
        $user_id = $request->has('user_id') ? (int) $request->get('user_id') : null;
        if ($user_id !== null) {
            $user_rec = User::where('company_id', $request->id)
                ->where('is_hod', 1)
                ->where('id', $user_id)
                ->exists();
            if ($user_rec) {
                return response()->json(['isHod' => 2]);
            }
        }
        $isHod = User::where('company_id', $request->id)->where('is_hod', 1)->exists() ? 1 : 0;

        return response()->json([
            'sections' => $sections_dd,
            'users' => $users,
            'isHod' => $isHod,
        ]);
    }

    public function checkDomainPrefix(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'domain_prefix' => 'required|string|max:255',
            'company_id' => 'nullable|integer',
        ]);

        $query = Company::where('domain_prefix', $request->get('domain_prefix'));
        if ($request->filled('company_id')) {
            $query->where('id', '<>', $request->get('company_id'));
        }
        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'This domain prefix is already in use.' : 'This domain prefix is available.',
        ]);
    }

    public function index(): Renderable
    {
        $data = [
            'title' => str_plural(config('settings.company_title', 'Company')).' List',
            'new_route' => ['settings.companies.create', 'New '.config('settings.company_title', 'Company')],
            'items' => Company::with(['parent', 'children', 'type'])->orderBy('id', 'desc')->get(),
        ];

        return view('settings.companies.index', $data);
    }

    public function create(Company $item): Renderable
    {
        $companies_dd = $this->buildCompaniesDropdown(Company::with(['children' => fn ($q) => $q->with('children')])->whereNull('parent_id')->get(), null);

        $data = [
            'title' => 'Create '.config('settings.company_title', 'Company'),
            'back_route' => ['settings.companies.list', str_plural(config('settings.company_title', 'Company')).' List'],
            'new_route' => ['settings.companies.create', 'New '.config('settings.company_title', 'Company')],
            'item' => $item,
            'companies' => $this->getCompaniesList(),
            'companies_dd' => $companies_dd,
            'types' => CompanyType::orderBy('id')->pluck('title', 'id'),
        ];

        return view('settings.companies.form', $data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'domain_prefix' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|in:draft,active,inactive,archived',
            'company_type_id' => 'nullable|exists:company_types,id',
            'parent_id' => 'nullable|exists:companies,id',
            'description' => 'nullable|string',
            'short_code' => 'nullable|string|max:50',
            'launched_at' => 'nullable|date',
            'deactivated_at' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::id();
        $company = Company::create($validated);

        if ($company) {
            Session::flash('success', 'New '.config('settings.company_title', 'Company').' created successfully');

            return redirect(route('settings.companies.list'));
        }

        Session::flash('error', 'Something went wrong, please try later');

        return redirect()->back();
    }

    public function show($id): Renderable
    {
        return view('settings.show');
    }

    public function edit($id): Renderable
    {
        $id = Crypt::decrypt($id);
        $item = Company::findOrFail($id);
        $companies_dd = $this->buildCompaniesDropdown(
            Company::with(['children' => fn ($q) => $q->with('children')])->whereNull('parent_id')->whereNot('id', $id)->get(),
            $id
        );

        $data = [
            'title' => 'Update '.config('settings.company_title', 'Company'),
            'back_route' => ['settings.companies.list', str_plural(config('settings.company_title', 'Company')).' List'],
            'new_route' => ['settings.companies.create', 'New '.config('settings.company_title', 'Company')],
            'item' => $item,
            'companies' => $this->getCompaniesList(),
            'companies_dd' => $companies_dd,
            'types' => CompanyType::orderBy('id')->pluck('title', 'id'),
        ];

        return view('settings.companies.form', $data);
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $id = Crypt::decrypt($id);
        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'domain_prefix' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'status' => 'required|in:draft,active,inactive,archived',
            'company_type_id' => 'nullable|exists:company_types,id',
            'parent_id' => 'nullable|exists:companies,id',
            'description' => 'nullable|string',
            'short_code' => 'nullable|string|max:50',
            'launched_at' => 'nullable|date',
            'deactivated_at' => 'nullable|date',
        ]);

        if ($company->update($validated)) {
            Session::flash('success', config('settings.company_title', 'Company').' updated successfully');

            return redirect(route('settings.companies.list'));
        }

        Session::flash('error', 'Something went wrong, please try later');

        return redirect()->back();
    }

    public function destroy($id): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $id = Crypt::decrypt($id);
            $company = Company::findOrFail($id);

            if ($company->children()->count() > 0) {
                $msg = 'Cannot delete '.config('settings.company_title', 'Company').' because it has sub-'.str_plural(config('settings.company_title', 'Company')).'. Please delete or move them first.';
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                Session::flash('error', $msg);

                return redirect()->back();
            }

            if ($company->users()->count() > 0) {
                $msg = 'Cannot delete '.config('settings.company_title', 'Company').' because it has assigned users. Please reassign them first.';
                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                Session::flash('error', $msg);

                return redirect()->back();
            }

            if ($company->delete()) {
                if (request()->ajax()) {
                    return response()->json(['success' => true, 'message' => config('settings.company_title', 'Company').' deleted successfully']);
                }
                Session::flash('success', config('settings.company_title', 'Company').' deleted successfully');

                return redirect()->back();
            }

            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong, please try later'], 500);
            }
            Session::flash('error', 'Something went wrong, please try later');

            return redirect()->back();
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid company ID'], 400);
            }
            Session::flash('error', 'Invalid company ID');

            return redirect()->back();
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
            }
            Session::flash('error', 'Error: '.$e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, Company>  $companies
     * @return array<int, string>
     */
    private function buildCompaniesDropdown($companies, ?int $excludeId): array
    {
        $result = [];
        foreach ($companies as $cdd) {
            if ($excludeId !== null && $cdd->id == $excludeId) {
                continue;
            }
            $result[$cdd->id] = $cdd->title;
            foreach ($cdd->children ?? [] as $cdd_cl1) {
                if ($excludeId !== null && $cdd_cl1->id == $excludeId) {
                    continue;
                }
                $result[$cdd_cl1->id] = $cdd->title.' -> '.$cdd_cl1->title;
                foreach ($cdd_cl1->children ?? [] as $cdd_cl2) {
                    if ($excludeId !== null && $cdd_cl2->id == $excludeId) {
                        continue;
                    }
                    $result[$cdd_cl2->id] = $cdd->title.' -> '.$cdd_cl1->title.' -> '.$cdd_cl2->title;
                }
            }
        }

        return $result;
    }
}
