<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agreement;

class AgreementController extends Controller
{


    function index() {
        return view('admin.agreement_create',['data'=>false]);
    }

public function agreements()
{
    $agreements = Agreement::latest()->get();
    return view('admin.agreements_list', compact('agreements'));
}
public function store(Request $request) {
    $validatedData = $request->validate([
        "title" => 'required',
        "description" => 'required'
    ]);

    $agreements = Agreement::create([
        'title' => $request->title,
        'description' => $request->description,
    ]);
    return back()->with('success', 'Agreement has been created successfully!');
}


public function edit($id) {
    $agreements=Agreement::find($id);
        return view('admin.agreement_create',['data'=>$agreements]);
}
public function update(Request $request) {
    $validatedData = $request->validate([
        "title" => 'required',
        "description" => 'required'
    ]);
    $agreements = Agreement::find($request->edit_id);
    $agreements->update([
        'title' => $request->title,
        'description' => $request->description,
    ]);
    return back()->with('success', 'Agreement has been updated successfully!');
}
public function delete($deleteId)
{
$user = Agreement::findOrFail($deleteId);
$user->delete();
return redirect()->back();
}
public function view($id) {
    $agreements=Agreement::find($id);
    return view('admin.agreement_view',compact('agreements'));

}
}
