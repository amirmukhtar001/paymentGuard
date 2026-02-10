<?php

namespace App\Http\Controllers\Settings;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Spatie\Activitylog\Models\Activity;
use App\Models\UserLog;

class UserLogsController extends Controller
{


    public function allLogs(Request $request)
    {
        if ($request->ajax()) {
            $logs = Activity::with('causer')->orderBy('created_at', 'desc');
            // Filter by user - handle both user_id and user_ids
            if ($request->filled('user_id')) {
                try {
                    $logs->where('causer_id', Crypt::decrypt($request->user_id));
                } catch (\Exception $e) {
                    $logs->where('causer_id', $request->user_id);
                }
            } elseif ($request->filled('user_ids')) {
                $userIds = is_array($request->user_ids) ? $request->user_ids : [$request->user_ids];
                $decryptedIds = [];
                foreach ($userIds as $userId) {
                    if (!empty($userId)) {
                        try {
                            $decryptedIds[] = Crypt::decrypt($userId);
                        } catch (\Exception $e) {
                            $decryptedIds[] = $userId;
                        }
                    }
                }
                if (!empty($decryptedIds)) {
                    $logs->whereIn('causer_id', $decryptedIds);
                }
            }

            if ($request->filled('start_date')) {
                $logs->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $logs->whereDate('created_at', '<=', $request->end_date);
            }

            return datatables()->of($logs)
                ->addIndexColumn()
                ->addColumn('user_name', function ($log) {
                    return $log->causer ? e($log->causer->name) : 'System';
                })
                ->addColumn('ip_address', function ($log) {
                    $properties = $this->normalizeActivityProperties($log->properties);
                    return e($properties['ip'] ?? 'N/A');
                })
                ->addColumn('action', function ($log) {
                    return e($log->description ?? 'N/A');
                })
                ->addColumn('user_agent', function ($log) {
                    $properties = $this->normalizeActivityProperties($log->properties);
                    return e($properties['user_agent'] ?? 'N/A');
                })
                ->addColumn('created_at', function ($log) {
                    return $log->created_at ? $log->created_at->format('d-m-Y H:i:s') : 'N/A';
                })
                ->addColumn('properties', function ($log) {
                    $properties = $this->normalizeActivityProperties($log->properties);
                    $data = json_encode($properties, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES);

                    return '<button class="btn btn-sm btn-info view-properties" data-json=\'' . $data . '\'>
                <i class="fas fa-eye"></i> View
            </button>';
                })

                ->rawColumns(['properties'])
                ->toJson();
        }

        $users = [];
        $selected_users = [];
        if ($request->filled('user_id')) {
            $users = User::where('id', Crypt::decrypt($request->user_id))->pluck('name', 'id');
            $selected_users = [Crypt::decrypt($request->user_id)];
        }


        return view('settings.user_logs.all', [
            'title' => 'User All Logs',
            'users' => $users ?? [],
            'selected_users' => $selected_users

        ]);
    }

    public function index()
    {
        $data = [
            'title' => 'User Logs',
            'users' => User::select('id', 'name')->get(),
        ];
        return view('settings.user_logs.index', $data);
    }

    public function logsList(Request $request)
    {
        $logs = UserLog::with('user')->select('user_logs.*')->orderBy('user_logs.created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $logs->where('user_logs.user_id', $request->user_id);
        }

        // Filter by IP address
        if ($request->filled('ip_address')) {
            $logs->where('user_logs.ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $logs->whereBetween('user_logs.created_at', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);
        } elseif ($request->filled('date_from')) {
            $logs->whereDate('user_logs.created_at', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $logs->whereDate('user_logs.created_at', '<=', $request->date_to);
        }

        return datatables()->of($logs)
            ->addIndexColumn()
            ->addColumn('user_name', function ($log) {
                return $log->user ? e($log->user->name) : 'N/A';
            })
            ->addColumn('ip_address', function ($log) {
                return e($log->ip_address ?? 'N/A');
            })
            ->addColumn('action', function ($log) {
                return e($log->action ?? 'N/A');
            })
            ->addColumn('user_agent', function ($log) {
                return e($log->user_agent ?? 'N/A');
            })
            ->addColumn('created_at', function ($log) {
                return $log->created_at ? $log->created_at->format('d-m-Y H:i:s') : 'N/A';
            })
            ->rawColumns(['created_at'])
            ->toJson();
    }

    // Export Logs
    public function export(Request $request, $format)
    {
        $query = UserLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);
        }

        $logs = $query->get();

        if ($format === 'excel') {
            // TODO: Create UserLogsExport class in app/Exports
            // return Excel::download(new \App\Exports\UserLogsExport($logs), 'user_logs.xlsx');
            return response()->json(['error' => 'Excel export not yet migrated'], 500);
        } elseif ($format === 'csv') {
            // TODO: Create UserLogsExport class in app/Exports
            // return Excel::download(new \App\Exports\UserLogsExport($logs), 'user_logs.csv');
            return response()->json(['error' => 'CSV export not yet migrated'], 500);
        } elseif ($format === 'pdf') {
            $pdf = PDF::loadView('settings.user_logs.pdf', compact('logs'));
            return $pdf->download('user_logs.pdf');
        } else {
            abort(404);
        }
    }

    protected function normalizeActivityProperties($properties): array
    {
        if (is_array($properties)) {
            return $properties;
        }

        if (is_string($properties)) {
            $decoded = json_decode($properties, true);
            return is_array($decoded) ? $decoded : [];
        }

        if ($properties instanceof \Illuminate\Contracts\Support\Arrayable) {
            return $properties->toArray();
        }

        if ($properties instanceof \JsonSerializable) {
            return (array) $properties->jsonSerialize();
        }

        return [];
    }
}
