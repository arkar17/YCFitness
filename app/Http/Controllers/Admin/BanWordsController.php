<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BanWordsRequest;
use App\Models\BanWord;
use Yajra\Datatables\Datatables;

class BanWordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
       return view('admin.banwords.index');
    }

    public function ssd()
    {
        $banwords=BanWord::all();
        return Datatables::of($banwords)
            ->addIndexColumn()
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $delete_icon = '';

                $edit_icon = '<a href=" ' . route('banwords.edit', $each->id) . ' " class="text-success mx-1 " title="edit">
                            <i class="fa-solid fa-edit fa-xl" data-id="' . $each->id . '"></i>
                        </a>';
                $delete_icon = '<a href=" ' . route('banwords.destroy', $each->id) . ' " class="text-danger mx-1" id="delete" title="delete">
                            <i class="fa-solid fa-trash fa-xl delete" data-id="' . $each->id . '"></i>
                        </a>';

                return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon . '</div>';
            })
            ->make(true);
    }

    public function create()
    {
        return view('admin.banwords.create');
    }

    public function store(BanWordsRequest $request)
    {
        $banword_store=New BanWord();
        $banword_store->ban_word_english=$request->ban_word_english;
        $banword_store->ban_word_myanmar=$request->ban_word_myanmar;
        $banword_store->ban_word_myanglish=$request->ban_word_myanglish;

        $banword_store->save();
       return redirect()->route('banwords.index')->with('success', 'New Ban Word is created successfully!');
    }


    public function edit($id)
    {
        $banword_edit = BanWord::findOrFail($id);
        return view('admin.banwords.edit',compact('banword_edit'));
    }


    public function update(Request $request, $id)
    {
        $banword_update=BanWord::findOrFail($id);
        $banword_update->ban_word_english=$request->ban_word_english;
        $banword_update->ban_word_myanmar=$request->ban_word_myanmar;
        $banword_update->ban_word_myanglish=$request->ban_word_myanglish;

        $banword_update->update();
        return redirect()->route('banwords.index')->with('success', 'Ban Word is Updated successfully!');
    }

    public function destroy($id)
    {
        $banword=BanWord::findOrFail($id);
        $banword->delete();
        return redirect()->route('banwords.index')->with('success', 'Ban Word is Deleted successfully!');
    }
}
