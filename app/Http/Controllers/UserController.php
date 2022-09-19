<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    public function usersApi(Request $request){
      //  $users = User::simplePaginate(100); //  850 ms== 100000 record
        $totalData = User::count();

        $start = $request->start ? $request->start : 0;
        $limit = $request->limit ? $request->limit : 10;
            $users = User::offset($start)
                ->limit($limit)
                ->get();

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $show =  $user->id;
                $edit =  $user->id;

                $nestedData['id'] = $user->id;
                $nestedData['name'] = $user->name;
                $nestedData['email'] =  $user->email;
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($user->created_at));
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "data"  => $data,
            'totalData'=>$totalData,
            'recordsFinished'=> $totalData  -  ($totalData / $limit),
        );

        return $json_data;
    }


    public function index()
    {
        return view('users');
    }
    public function allUsers(Request $request)
    {

        $columns = array(
            0 =>'id',
            1 =>'name',
            2=> 'email',
            3=> 'created_at',
            4=> 'active',
            5=> 'id',
        );

        $totalData = User::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $users = User::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $users =  User::where('id','LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->orWhere('email', 'LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = User::where('id','LIKE',"%{$search}%")
                ->orWhere('name', 'LIKE',"%{$search}%")
                ->orWhere('email', 'LIKE',"%{$search}%")
                ->count();
        }

        $data = array();
        if(!empty($users))
        {
            foreach ($users as $user)
            {
                $id =  $user->id;
                $link =  route('users.destroy',$id) ;

                $nestedData['id'] = $user->id;
                $nestedData['name'] = $user->name;
                $nestedData['email'] =  $user->email;
//                    substr(strip_tags($user->body),0,50)."...";
                $nestedData['created_at'] = date('j M Y h:i a',strtotime($user->created_at));
                $nestedData['active'] = $user->getActive();
                $nestedData['options'] = "
<a href='users/{$id}/edit' class='edit btn btn-primary' title='EDIT' >
<i class='glyphicon glyphicon-user'></i> EDIT</a>
<button data-remote='users/{$id}' class='btn-delete btn btn-danger'>
<i class='glyphicon glyphicon-remove'></i> Delete</button>
";
//                <a href='users/{$show}' class='show btn btn-info btn-sm'  title='SHOW' >SHOW</a>
//<a href='users/{$edit}/edit' class='edit btn btn-primary btn-sm' title='EDIT' >EDIT</a>
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "show " . $id;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return "edit " . $id;

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $User = User::find($id);

        $User->delete();
    }

}
