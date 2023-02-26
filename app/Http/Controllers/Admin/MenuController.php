<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Query\Builder;

class MenuController extends Controller {
    public function index() {
        return view('admin.settings.menu.index');
    }
    public function list(Request $request) {
        $limit = $request->input('length');
        $offset = $request->input('start');
        $search = $request->input('search.value');
        if ($search !== '') {
            $data = DB::table('menus as a')
                ->leftJoin('menus as b', 'a.parent', '=', 'b.id')
                ->select('a.*', DB::raw('IFNULL(b.name, "Main Parent") AS parent_name'))
                ->where('a.name', 'like', "%{$search}%")
                ->orWhere('b.name', 'like', "%{$search}%")
                ->orderBy('a.created_at', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $data = DB::table('menus as a')
                ->leftJoin('menus as b', 'a.parent', '=', 'b.id')
                ->select('a.*', DB::raw('IFNULL(b.name, "Main Parent") AS parent_name'))
                ->orderBy('a.created_at', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        
       // Menghitung total record
       $total = DB::table('menus')->count();

        $countFiltered = DB::table('menus as a')
            ->leftJoin('menus as b', 'a.parent', '=', 'b.id')
            ->select('a.*', DB::raw('IFNULL(b.name, "Main Parent") AS parent_name'))
            ->orderBy('a.created_at', 'desc');

        if (!empty($search)) {
            $countFiltered->where(function($query) use($search) {
                $query->where('a.name', 'like', '%'.$search.'%')
                    ->orWhere('b.name', 'like', '%'.$search.'%');
            });
        }

        $countFiltered = $countFiltered->count();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($model) {
                $action = "";
                $action .= "<a onclick='editModal($model->id)' class='btn btn-sm btn-icon btn-warning btn-hover-rise me-1'><i class='bi bi-pencil-square'></i></a>";
                $action .= " <a onclick='deleteModal($model->id)' class='btn btn-sm btn-icon btn-danger btn-hover-rise me-1'><i class='bi bi-trash'></i></a>";

                return $action;
            })->addColumn('status', function($model){
                if($model->status == 1){
                    $status = '<div class="badge badge-light-success fw-bolder">Active</div>';
                }else{
                    $status = '<div class="badge badge-light-danger fw-bolder">Not Active</div>';
                }

                return $status;
            })->rawColumns(['action', 'status'])
            ->with('recordsTotal', $total)
            ->with('recordsFiltered', $countFiltered)
            ->setOffset((int)$offset)
            ->make(true);
    
    }
    public function create() {
        return view('admin.settings.menu.create', ['menu' => Menu::all()]);
    }
    public function store(Request $request) {
        dd($request);
        Menu::create([
            'parent' => $request->parent,
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $request->icon,
            'status' => $request->status
        ]);
        return response()->json(['success' => 'Menu Created']);
    }

    public function edit(Request $request) {

        return view('admin.settings.menu.edit', ['menu' => Menu::all(), 'onMenu' => Menu::where(['id' => $request->id])->first()]);
    }

    public function update(Request $request) {
        Menu::where(['id' => $request->id])->update([
            'parent' => $request->parent,
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $request->icon,
            'status' => $request->status
        ]);
        return response()->json(["success" => "Data $request->name Updated"]);
    }

    public function destroy(Request $request) {
        Menu::where(['id' => $request->id])->delete();
        return response()->json(["success" => "Data Deleted"]);
    }

    public function loadmenu(Request $request) {
        $menu = DB::select("select b.id, b.parent, b.name, b.url, b.icon from menu_accesses a join menus b on a.menu_id = b.id where a.role_id = $request->role_id and status = 1 and b.parent = $request->parent");
        $html = '';
        foreach ($menu as $mn) {
            $submenu = DB::select("select b.id, b.parent, b.name, b.url, b.icon from menu_accesses a join menus b on a.menu_id = b.id where a.role_id = $request->role_id and status = 1 and b.parent = $mn->id");
            if ($submenu) {
                $html .= " <div data-kt-menu-trigger='click' class='menu-item menu-accordion'>
                <span class='menu-link'>
                    <span class='menu-icon'>
                    <i class='bi bi-$mn->icon'></i>
                    </span> 
                    <span class='menu-title'>$mn->name</span>
                    <span class='menu-arrow'></span>
                </span> 
                <div class='menu-sub menu-sub-accordion menu-active-bg'>";



                foreach ($submenu as $sm) {
                    $tandapetik = '"';
                    $subOnSubmenu = DB::select("select b.id, b.parent, b.name, b.url, b.icon from menu_accesses a join menus b on a.menu_id = b.id where a.role_id = $request->role_id and status = 1 and b.parent = $sm->id");


                    if ($subOnSubmenu) {
                        $html .= "<div data-kt-menu-trigger='click' class='menu-item menu-accordion'>
                        <span class='menu-link'>
                            <span class='menu-bullet'>
                                <span class='bullet bullet-dot'></span>
                            </span>
                            <span class='menu-title'>$sm->name</span>
                            <span class='menu-arrow'></span>
                        </span>
                        <div class='menu-sub menu-sub-accordion menu-active-bg'>";

                        foreach ($subOnSubmenu as $sosm) {
                            $html .= " <div class='menu-item'>
                            <a class='menu-link' href='$sosm->url'>
                                <span class='menu-bullet'>
                                    <span class='bullet bullet-dot'></span>
                                </span>
                                <span class='menu-title'>$sosm->name</span>
                            </a>
                        </div>";
                        }
                        $html .= "</div></div>";
                    } else {
                        $html .= "<div class='menu-item'>
                        <a class='menu-link' href='$sm->url'>
                            <span class='menu-bullet'>
                                <span class='bullet bullet-dot'></span>
                            </span>
                            <span class='menu-title'>$sm->name</span>
                        </a>
                    </div>";
                    }
                }
                $html .= '</div></div>';
            } else {
                $html .= "<div class='menu-item'>
                    <a class='menu-link' href='$mn->url'>
                        <span class='menu-icon'>
                        <i class='bi bi-$mn->icon'></i>
                        </span>
                        <span class='menu-title'>$mn->name</span>
                    </a>
                </div>";
            }
        }
        
        if(auth()->user()->role->role == 'Super Admin'){
            $html .= ' <div data-kt-menu-trigger="click" class="menu-item menu-accordion mb-1">
            <span class="menu-link">
                <span class="menu-icon">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen051.svg-->
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                fill="black" />
                            <path
                                d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z"
                                fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </span>
                <span class="menu-title">User Management</span>
                <span class="menu-arrow"></span>
            </span>
            <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                    <a class="menu-link" href="../../demo8/dist/apps/user-management/permissions.html">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Users</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="../../demo8/dist/apps/user-management/permissions.html">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Roles</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="../../demo8/dist/apps/user-management/permissions.html">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Permissions</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="/admin/menu/index">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Menu Management</span>
                    </a>
                </div>
            </div>
        </div>';
        }

        $request->session()->push('menu', $html);

        return response()->json($html);
    }
}
