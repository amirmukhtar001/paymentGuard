@extends('layouts.'.config('settings.active_layout'))

@push('scripts')

    <!-- Fancytree CSS and JS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/jquery.fancytree@2.38.2/dist/skin-win8/ui.fancytree.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery.fancytree@2.38.2/dist/jquery.fancytree-all-deps.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            $(".tree-checkbox-hierarchical").fancytree({
                checkbox: true,
                selectMode: 3,
                select: function (event, data) {

                }
            });

            // Loop through all trees, get the selected nodes from each tree then
            // get the permission ids by removing extra text (perm-) and then
            // join them as string with , and put it in text field
            $('#permissions_assignment_form').submit(function (e) {
                // e.preventDefault()
                var allSelectedNodes = []
                $.each($(".tree-checkbox-hierarchical"), function(i,v){
                    var thisTreeSelectedNodes = $(v).fancytree('getTree').getSelectedNodes();
                    // console.table(thisTreeSelectedNodes)
                    var thisTreeSelKeys = $.map(thisTreeSelectedNodes, function (node) {
                        // return "[" + node.key + "]: '" + node.title + "'";
                        if(node.key.includes("perm-")){
                            return node.key.replace("perm-", "");
                        }
                    });
                    // console.log(thisTreeSelKeys)
                    allSelectedNodes = allSelectedNodes.concat(thisTreeSelKeys)
                })

                // console.log(allSelectedNodes);
                $("#checked_permissions").val(allSelectedNodes.join(","));
                // return false;

            });

        })
    </script>
@endpush

@section('content')

    <div class="row">
        <div class="col-12">

            <!-- Traffic sources -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">User Details: <strong><u>{{ $item->name }}</u></strong></h6>
                    <div class="header-elements">
                        <div class="form-check form-check-right form-check-switchery form-check-switchery-sm">

                            {{--<label class="form-check-label">
                                Live update:
                                <input type="checkbox" class="form-input-switchery" checked data-fouc>
                            </label>--}}
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-12">

                            <h5><strong>{{ $item->username }} / {{ $item->email }}</strong></h5>
                            <h6>Organization: <strong>{{ $item->company->title ?? "" }}</strong> ({{ $item->section->title ?? "" }})</h6>
                            <div>
                                <strong>Assigned Roles</strong><br>
                                @foreach($item->roles as $r)
                                    <span class="badge badge-info">{{ $r->name }}</span>
                                @endforeach
                            </div>
                            <p>{{ $item->description }}</p>

                            <div class="alert alert-info mt-3">
                                <i class="icon-warning mr-1"></i> <strong>ATTENTION!</strong>
                                <p>Please select permissions that you want to assign to this user. After you select all permissions, make sure to click "Save Permissions" button at the bottom of the page.</p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <!-- /traffic sources -->

        </div>
    </div>

    <form class="form-horizontal" id="permissions_assignment_form" method="POST" action="{{ route('settings.users-mgt.user-permissions-save', \Illuminate\Support\Facades\Crypt::encrypt($item->id)) }}">
        @csrf

    <div class="row">

        @php
            $assigned_perms_c = collect($item->permissions);
            $assigned_perms_ids = $assigned_perms_c->pluck('id')->toArray();
            // dd($assigned_perms_ids);
        @endphp
        <input type="hidden" value="" id="checked_permissions" name="checked_permissions"/>
        <input type="hidden" value="{{ implode(',', $assigned_perms_ids) }}" id="assigned_permissions" name="assigned_permissions">

        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title"><strong><i class="bx bx-lock-open mr-1"></i>All Permissions</strong></h6>
                    <div class="header-elements">

                    </div>
                </div>

                <div class="card-body" style="max-height: 600px !important; min-height: 400px !important; overflow-y: auto;">

                    <div class="tree-checkbox-hierarchical well">

                        @php
                            if (!function_exists('renderMenuWithPermissions')) {
                                function renderMenuWithPermissions($menu, $assigned_perms_ids) {
                                    $html = '<li id="menu-' . $menu->id . '" rel="' . $menu->id . '" class="folder expanded">' . e($menu->title);
                                    $html .= '<ul>';

                                    // Render permissions for this menu
                                    foreach ($menu->myPermissions as $perm) {
                                        $selected = in_array($perm->id, $assigned_perms_ids) ? 'selected' : '';
                                        $html .= '<li id="perm-' . $perm->id . '" rel="' . $perm->id . '" class="' . $selected . '">' . e($perm->name) . '</li>';
                                    }

                                    // Render child menus recursively
                                    foreach ($menu->childrenRecursive as $child) {
                                        $html .= renderMenuWithPermissions($child, $assigned_perms_ids);
                                    }

                                    $html .= '</ul>';
                                    $html .= '</li>';
                                    return $html;
                                }
                            }
                        @endphp

                        <ul>
                            @foreach($trees[0]->menus as $menu)
                                {!! renderMenuWithPermissions($menu, $assigned_perms_ids) !!}
                            @endforeach
                        </ul>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="col-12">
                        <a href="{{ route('settings.users-mgt.list') }}" class="btn btn-warning">
                            <i class="bx bx-arrow-back"></i> Back to Users
                        </a>

                        <button type="submit" class="btn btn-info">
                            <i class="bx bxs-save"></i> Save Permissions
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    </form>

@endsection
