<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class MenuController extends Controller {
    public function index() {
        return view('admin.settings.menu.index');
    }
    public function list() {
        return Datatables::of(Menu::all())->addIndexColumn()
            ->addColumn('action', function ($model) {
                $action = "";
                $action .= "<a onclick='editModal($model->id)' class='btn btn-sm btn-icon btn-warning btn-hover-rise me-1'><i class='bi bi-pencil-square'></i></a>";
                $action .= " <a onclick='deleteModal($model->id)' class='btn btn-sm btn-icon btn-danger btn-hover-rise me-1'><i class='bi bi-trash'></i></a>";

                return $action;
            })->rawColumns(['action'])->make(true);
    }
    public function create() {
        return view('admin.settings.menu.create', ['menu' => Menu::all()]);
    }
    public function store(Request $request) {
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
                $html .= "<div data-kt-menu-trigger='click' data-kt-menu-placement='bottom-start' class='menu-item menu-lg-down-accordion me-lg-1'>
                <span class='menu-link  py-3'>
                    <span class='menu-title'>$mn->name</span>
                    <span class='menu-arrow d-lg-none'></span>
                </span>
                <div class='menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px'>";



                foreach ($submenu as $sm) {
                    $tandapetik = '"';
                    $subOnSubmenu = DB::select("select b.id, b.parent, b.name, b.url, b.icon from menu_accesses a join menus b on a.menu_id = b.id where a.role_id = $request->role_id and status = 1 and b.parent = $sm->id");


                    if ($subOnSubmenu) {
                        $html .= "<div data-kt-menu-trigger=" . $tandapetik . "{default:'click', lg: 'hover'}" . $tandapetik . " data-kt-menu-placement='right-start' class='menu-item menu-lg-down-accordion'>
                        <span class='menu-link py-3'>
                            <span class='menu-icon'>
                                <span class='svg-icon svg-icon-2'>
                                    <i class='bi bi-$sm->icon'></i>
                                </span>
                            </span>
                            <span class='menu-title text-gray-700'>$sm->name</span>
                            <span class='menu-arrow'></span>
                        </span>
                        <div class='menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px'>";

                        foreach ($subOnSubmenu as $sosm) {
                            $html .= "<div class='menu-item'>
                                        <a class='menu-link py-3' href='$sosm->url'>
                                            <span class='menu-bullet'>
                                                <span class='bullet bullet-dot'></span>
                                            </span>
                                            <span class='menu-title  text-gray-700'>$sosm->name</span>
                                        </a>
                                    </div>";
                        }
                        $html .= "</div></div>";
                    } else {
                        $html .= "<div class='menu-item'>
                        <a class='menu-link py-3' href='$sm->url'  data-bs-toggle='tooltip' data-bs-trigger='hover' data-bs-dismiss='click' data-bs-placement='right'>
                        <span class='menu-icon'>
                        <span class='svg-icon svg-icon-2'>
                        <i class='bi bi-$sm->icon'></i>
                        </span>
                        </span>
                        <span class='menu-title text-gray-700'>$sm->name</span>
                        </a>
                        </div>";
                    }
                }
                $html .= '</div></div>';
            } else {
                $html .= "<div data-kt-menu-placement='bottom-start' class='menu-item menu-lg-down-accordion me-lg-1'>
                <a class='menu-link py-3' href='$mn->url'>
                <span class='menu-title'>$mn->name</span>
                <span class='menu-arrow d-lg-none'></span>
                </a>
                </div>";
            }
        }

        $request->session()->push('menu', $html);

        return response()->json($html);
    }
}
