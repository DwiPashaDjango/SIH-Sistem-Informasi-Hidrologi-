<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $users = User::with('pos', 'roles')->orderBy('id', 'DESC');
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('username', function ($row) {
                    return $row->username;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('telp', function ($row) {
                    return $row->nohp;
                })
                ->addColumn('pos', function ($row) {
                    return $row->pos ? $row->pos->nama : '-';
                })
                ->addColumn('role', function ($row) {
                    return $row->roles[0]->name;
                })
                ->addColumn('action', function ($row) {
                    if (Auth::user()->roles[0]->name === 'Admin') {
                        $btn = '<a href="' . route('users.edit', ['id' => $row->id]) . '" class="btn btn-warning btn-sm mr-2"><i class="fas fa-pen"></i></a>';
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" id="delete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    } else {
                        $btn = '-';
                    }
                    return $btn;
                })
                ->filter(function ($query) {
                    if (request()->has('search')) {
                        $search = request('search')['value'];
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhereHas('roles', function ($roles) use ($search) {
                                    $roles->where('name', 'like', "%{$search}%");
                                });
                        });
                    }
                })
                ->toJson();
        }
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenis = Jenis::select('id', 'nama')->get();
        $pos = Post::select('id', 'nama')->get();
        $roles = Role::select('id', 'name')->get();
        return view('admin.users.create', compact('jenis', 'pos', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|unique:users,email|email',
            'nohp' => 'required',
            'roles' => 'required',
            'jenis_id' => "required",
            'pos_id' => 'required',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required'
        ]);

        $post = $request->except(['jenis_id', 'roles', 'password_confirmation']);
        $post['password'] = Hash::make($post['password']);

        $users = User::create($post);

        $users->assignRole($request->input('roles'));

        return redirect()->route('users')->with('message', 'Berhasil Menyimpan Data.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = User::with('roles')->find($id);
        $jenis = Jenis::select('id', 'nama')->get();
        $pos = Post::select('id', 'nama')->get();
        $roles = Role::select('id', 'name')->get();
        return view('admin.users.edit', compact('users', 'jenis', 'pos', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'nohp' => 'required',
            'roles' => 'required',
            'jenis_id' => "required",
            'pos_id' => 'required',
        ]);

        $post = $request->except(['jenis_id', 'roles']);

        $users = User::find($id);
        $users->update($post);
        $users->syncRoles($request->input('roles'));

        return redirect()->route('users')->with('message', 'Berhasil Mengubah Data Data.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $users = User::find($id);

        $userLogin = User::where('id', Auth::user()->id)->first();

        if ($userLogin) {
            if (Hash::check($request->password, $userLogin->password)) {
                $users->delete();
                return Response::json(['message' => 'Berhasil Menghapus Data Subdas.', 'code' => 200]);
            } else {
                return Response::json(['message' => 'Password Tidak Sesuai.', 'code' => 400]);
            }
        }
    }

    public function getPosByJenis(Request $request)
    {
        $jenis_id = $request->jenis_id;

        $pos = Post::where('jenis_id', $jenis_id)->get();

        return Response::json(['data' => $pos]);
    }
}
