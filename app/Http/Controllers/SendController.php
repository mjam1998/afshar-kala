<?php

namespace App\Http\Controllers;

use App\Models\SendMethod;
use Illuminate\Http\Request;

class SendController extends Controller
{
    public function index()
    {
        $sends=SendMethod::paginate(10);
        return view('admin.send.index',compact('sends'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',

            'description'=>'required|string',

        ], [

            'name.required'=>'وارد کردن نام الزامی است.',
            'description.required'=>'وارد کردن توضیحات الزامی است.'

        ]);



       SendMethod::query()->create([
           'name'=>$request['name'],
           'description'=>$request['description']
       ]);

        return redirect()->back()->with('sendAdded','روش ارسال با موفقیت افزوده شد.');
    }

    public function edit(Request $request, $id)
    {
        $send=SendMethod::query()->find($id);

        $request->validate([
            'name' => 'required|string',

            'description'=>'required|string',

        ], [

            'name.required'=>'وارد کردن نام الزامی است.',
            'description.required'=>'وارد کردن توضیحات الزامی است.'

        ]);

       $data=$request->all();
        $send->update($data);

        return redirect()->route('admin.send.list')
            ->with('sendAdded', 'روش ارسال با موفقیت ویرایش شد.');
    }
}
