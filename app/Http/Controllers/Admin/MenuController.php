<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;

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
                ->where('a.is_deleted', '=', 0)
                ->where('a.name', 'like', "%{$search}%")
                ->orderBy('a.updated_at', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $data = DB::table('menus as a')
                ->leftJoin('menus as b', 'a.parent', '=', 'b.id')
                ->select('a.*', DB::raw('IFNULL(b.name, "Main Parent") AS parent_name'))
                ->where('a.is_deleted', '=', 0)
                ->orderBy('a.updated_at', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        
       // Menghitung total record
       $total = DB::table('menus')->count();

        $countFiltered = DB::table('menus as a')
            ->where('a.is_deleted', '=', 0)
            ->leftJoin('menus as b', 'a.parent', '=', 'b.id')
            ->select('a.*', DB::raw('IFNULL(b.name, "Main Parent") AS parent_name'))
            ->orderBy('a.updated_at', 'desc');

        if (!empty($search)) {
            $countFiltered->where(function($query) use($search) {
                $query->where('a.name', 'like', '%'.$search.'%')
                    ->orWhere('b.name', 'like', '%'.$search.'%');
            });
        }

        $countFiltered = $countFiltered->count();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($model) {
                $action = '';
                $action .= "<button class='btn btn-icon btn-active-light-primary w-30px h-30px me-3' type='button' onclick='showEditModal($model->id)'>
                            <span class='svg-icon svg-icon-3'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
                                    <path d='M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z' fill='black'></path>
                                    <path opacity='0.3' d='M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z' fill='black'></path>
                                </svg>
                            </span>
                        </button>";
                $action .= "<button class='btn btn-icon btn-active-light-primary w-30px h-30px' type='button' onclick='deleteList($model->id)'>
                <span class='svg-icon svg-icon-3'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>
                        <path d='M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z' fill='black'></path>
                        <path opacity='0.5' d='M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z' fill='black'></path>
                        <path opacity='0.5' d='M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z' fill='black'></path>
                    </svg>
                </span>
            </button>";

                return $action;
            })->addColumn('status', function($model){
                if($model->status == 1){
                    $status = '<div class="badge badge-light-success fs-7 m-1 fw-bolder">Active</div>';
                }else{
                    $status = '<div class="badge badge-light-danger fs-7 m-1 fw-bolder">Not Active</div>';
                }

                return $status;
            })->addColumn('parent_name', function($model){
                if($model->parent_name == 'Main Parent'){
                    $parent_name = '<div class="badge badge-light-danger fs-7 m-1 fw-bolder">'.$model->parent_name.'</div>';
                }else{
                    $parent_name = '<div class="badge badge-light-primary fs-7 m-1 fw-bolder">'.$model->parent_name.'</div>';
                }

                return $parent_name;
            })->addColumn('icon', function($model){
                if($model->icon !== '-'){
                    $parent_name = "<i class='bi bi-$model->icon text-primary'></i>";
                }else{
                    $parent_name = '<div class="badge badge-light-danger fw-bolder">Nothing</div>';
                }

                return $parent_name;
            })->rawColumns(['action', 'status', 'parent_name', 'icon'])
            ->with('recordsTotal', $total)
            ->with('recordsFiltered', $countFiltered)
            ->setOffset((int)$offset)
            ->make(true);
    
    }
    public function create() {
        return view('admin.settings.menu.create', ['menu' => Menu::all()]);
    }
    public function store(Request $request) {
        $validator = Validator::make(
            $request->all(),
        [
            'parent' => 'required|integer',
            'name' => 'required|string|min:2|max:30',
            'url' => 'required|string|min:1|max:100',
            'icon' => 'required|string|min:1|max:30',
        ], 
        [
            'required' => ':attribute harus diisi',
            'integer' => ':attribute harus berupa angka',
            'string' => ':attribute harus berupa teks',
            'min' => ':attribute minimal :min karakter',
            'max'=> ':attribute tidak boleh lebih dari :max karakter'
        ]
        );
        
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['errors' => $errors],422);
        }
        DB::beginTransaction();

        try {
            $menu = new Menu([
                'parent' => $request->parent,
                'name' => $request->name,
                'url' => $request->url,
                'icon' => $request->icon,
                'status' => 1
            ]);
        
            $menu->save();
        
            DB::commit();
            Session::forget('menu');
            return response()->json(['success' => 'Menu Created']);
        } catch (\Exception $e) {
            DB::rollback();
            $errors = [
                "Gagal Menyimpan Ke Database"
            ];
            return response()->json(['errors' => $errors],422);
        }
       
    }

    public function edit($id) {

        return view('admin.settings.menu.edit', ['menu' => Menu::all(), 'onMenu' => Menu::where(['id' => $id])->first()]);
    }

    public function update(Request $request) {
        $validator = Validator::make(
            $request->all(),
        [
            'id' => 'required',
            'parent' => 'required|integer',
            'name' => 'required|string|min:2|max:30',
            'url' => 'required|string|min:1|max:100',
            'icon' => 'required|string|min:1|max:30',
            'status' => 'required',
        ], 
        [
            'required' => ':attribute harus diisi',
            'integer' => ':attribute harus berupa angka',
            'string' => ':attribute harus berupa teks',
            'min' => ':attribute minimal :min karakter',
            'max'=> ':attribute tidak boleh lebih dari :max karakter'
        ]
        );
        
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['errors' => $errors],422);
        }
        DB::beginTransaction();

        try {
            Menu::where(['id' => $request->id])->update([
                'parent' => $request->parent,
                'name' => $request->name,
                'url' => $request->url,
                'icon' => $request->icon,
                'status' => $request->status
            ]);
        
            DB::commit();
            Session::forget('menu');
            return response()->json(['success' => 'Menu Updated']);
        } catch (\Exception $e) {
            DB::rollback();
            $errors = [
                "Gagal Menyimpan Ke Database"
            ];
            return response()->json(['errors' => $errors],422);
        }
       
    }

    public function destroy(Request $request) {
        DB::beginTransaction();

        try {
            Menu::where(['id' => $request->id])->update(['is_deleted' => 1]);
            DB::commit();
            Session::forget('menu');
            return response()->json(['success' => 'Menu Deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            $errors = [
                "Gagal Menyimpan Ke Database"
            ];
            return response()->json(['errors' => $errors],422);
        }
       
        return response()->json(["success" => "Data Deleted"]);
    }

    public function loadmenu(Request $request) {
        $menu = DB::select("select b.id, b.parent, b.name, b.url, b.icon from menu_accesses a join menus b on a.menu_id = b.id where a.role_id = $request->role_id and status = 1 and b.parent = $request->parent and b.is_deleted = 0");
        $html = '';
        foreach ($menu as $mn) {
            $submenu = DB::select("select b.id, b.parent, b.name, b.url, b.icon from menu_accesses a join menus b on a.menu_id = b.id where a.role_id = $request->role_id and status = 1 and b.parent = $mn->id and b.is_deleted = 0");
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
                    $subOnSubmenu = DB::select("select b.id, b.parent, b.name, b.url, b.icon from menu_accesses a join menus b on a.menu_id = b.id where a.role_id = $request->role_id and status = 1 and b.parent = $sm->id and b.is_deleted = 0");


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
                    <a class="menu-link" href="/admin/role">
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
                    <a class="menu-link" href="/admin/menu">
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
