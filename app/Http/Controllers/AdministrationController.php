<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class AdministrationController extends Controller
{
    public function index()
    {
        $items = Item::with('group')->orderBy('groupid')->get();
        return view('administration.index', compact('items'));
    }

    public function updateStatus(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->status = $request->input('status');
        $item->save();

        return redirect()->back()->with('status', 'Item status updated successfully!');
    }

    public function updateDescription(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->description = $request->input('description');
        $item->save();

        return redirect()->back()->with('status', 'Item description updated successfully!');
    }
}
