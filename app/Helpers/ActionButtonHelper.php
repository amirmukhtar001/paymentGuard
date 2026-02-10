<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

if (!function_exists('renderActionButtons')) {
    function renderActionButtons($row, $routes, $permissionPrefix = null)
    {
        $id = Crypt::encrypt($row->id);

        // Handle trashed (soft deleted) rows
        if (method_exists($row, 'trashed') && $row->trashed()) {
            return '<button type="button"
                        class="btn btn-success btn-sm restore-btn"
                        data-id="' . $id . '"
                        data-model="' . addslashes(get_class($row)) . '"
                        data-route="' . route('ajaxRestore') . '"
                        title="Restore">
                        <i class="bx bx-undo"></i> Restore
                    </button>';
        }

        $viewButton = $updateButton = $deleteButton = '';
        $user = Auth::user();

        // Show View button ONLY if user has view permission (if permission prefix provided)
        if (isset($routes['show'])) {
            if (!$permissionPrefix || ($user && $user->can($permissionPrefix . '.show'))) {
                $viewButton = "<a href='" . route($routes['show'], ['id' => $id]) . "' class='btn btn-warning btn-sm me-1' title='View'>
                                <i class='fas fa-eye'></i>
                           </a>";
            }
        }

        // Show Edit button ONLY if user has edit permission (if permission prefix provided)
        if (isset($routes['edit'])) {
            if (!$permissionPrefix || ($user && $user->can($permissionPrefix . '.edit'))) {
                $updateButton = "<a href='" . route($routes['edit'], ['id' => $id]) . "' class='btn btn-primary btn-sm me-1' title='Edit'>
                                <i class='fas fa-edit'></i>
                             </a>";
            }
        }

        // Show Delete button ONLY if user has delete permission (if permission prefix provided)
        if (isset($routes['destroy'])) {
            if (!$permissionPrefix || ($user && $user->can($permissionPrefix . '.delete'))) {
                $deleteButton = '<button type="button" class="btn btn-danger btn-sm" onclick="destroy(this, \'' . route($routes['destroy'], $id) . '\')" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                             </button>';
            }
        }

        $buttons = $viewButton . $updateButton . $deleteButton;
        return !empty($buttons) ? '<div class="d-flex gap-1">' . $buttons . '</div>' : '-';
    }
}



if (!function_exists('renderDatatableButtons')) {
    /**
     * Render action buttons for DataTable rows with permission checking
     *
     * @param object $row The row model instance
     * @param array $routes Array of routes ['show' => 'route.name', 'edit' => 'route.name', 'destroy' => 'route.name', 'currentTable' => 'table-id']
     * @param string|null $permissionPrefix Permission prefix (e.g., 'settings.news') - if null, no permission check
     * @return string HTML string with action buttons
     */
    function renderDatatableButtons($row, $routes, $permissionPrefix = null)
    {
        $id = $row->uuid;
        // Handle trashed (soft deleted) rows
        if (method_exists($row, 'trashed') && $row->trashed()) {
            return '<button type="button"
                        class="btn btn-success btn-sm restore-btn"
                        data-id="' . $id . '"
                        data-model="' . addslashes(get_class($row)) . '"
                        data-route="' . route('ajaxRestore') . '"
                        title="Restore">
                        <i class="bx bx-undo"></i> Restore
                    </button>';
        }
        $viewButton = $updateButton = $deleteButton = $manageButton = '';
        $user = Auth::user();
        // Show View button ONLY if user has view permission (if permission prefix provided)
        if (isset($routes['show'])) {
            if (!$permissionPrefix || ($user && $user->can($permissionPrefix . '.show'))) {
                $viewButton = "<a href='" . route($routes['show'], $id) . "' class='btn btn-warning btn-sm me-1' title='View'>
                                <i class='fas fa-eye'></i>
                           </a>";
            }
        }
        // Show Edit button ONLY if user has edit permission (if permission prefix provided)
        if (isset($routes['edit'])) {
            if (!$permissionPrefix || ($user && $user->can($permissionPrefix . '.edit'))) {
                $updateButton = "<a href='" . route($routes['edit'], $id) . "' class='btn btn-primary btn-sm me-1' title='Edit'>
                                <i class='fas fa-edit'></i>
                             </a>";
            }
        }
        // Show Delete button ONLY if user has delete permission (if permission prefix provided)
        if (isset($routes['destroy'])) {
            if (!$permissionPrefix || ($user && $user->can($permissionPrefix . '.delete'))) {
                $deleteButton = '<button type="button" class="btn btn-danger btn-sm delete_record" data-table="#' . ($routes['currentTable'] ?? '') . '" data-url="' . route($routes['destroy'], $id) . '" title="Delete"><i class="fas fa-trash-alt"></i></button>';
            }
        }



        if (isset($routes['manage'])) {
            if (!$permissionPrefix || ($user && $user->can($permissionPrefix . '.manage'))) {
                $manageButton = "<a href='" . route($routes['manage'], $id) . "' class='btn btn-warning btn-sm me-1' title='Manage More ..'>
                                <i class='fas fa-list'></i>
                           </a>";
            }
        }

        // Return buttons or "-" if no buttons available
        $buttons =$manageButton. $viewButton . $updateButton . $deleteButton;
        return !empty($buttons) ? '<div class="d-flex gap-1">' . $buttons . '</div>' : '-';
    }
}
