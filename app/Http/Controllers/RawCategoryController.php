<?php

namespace App\Http\Controllers;

use App\Models\RawCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RawCategoryController extends Controller
{
    /**
     * Display category list
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = RawCategory::query()->orderBy('id','DESC');;
            
            if ($request->name) {
                            $categories->where('name', 'LIKE', '%' . $request->name . '%');
                        }

            return DataTables::of($categories)
                        
   ->filterColumn('parent_category', function ($query, $keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->whereHas('parent', function ($parentQuery) use ($keyword) {
                $parentQuery->where('name', 'like', "%{$keyword}%");
            });
      
           
        });
    })
                ->addColumn('action', function ($row) {
                    return view('admin.rawcategory.action', compact('row'))->render();
                })
                ->addColumn('parent_category', function ($row) {
                    return $row->parent ? $row->parent->name : 'N/A';
                })
                ->editColumn('status', function ($row) {
                    return $row->status 
                        ? '<span class="badge bg-success px-2 py-1">Active</span>' 
                        : '<span class="badge bg-danger px-2 py-1">Inactive</span>';
                })
                ->rawColumns(['action', 'status', 'parent_category'])
                ->make(true);
        }
            $categories = RawCategory::select('name')->groupBy('name')->get();
                return view('admin.rawcategory.index',compact('categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = RawCategory::whereNull('parent_category_id')->get();
        return view('admin.rawcategory.create', compact('categories'));
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'code' => 'required|string|max:255|unique:categories,code',

            ]);

            $category = RawCategory::create([
                'name' => $request->name,
                'parent_category_id' => $request->parent_category_id,
                'code' => $request->code,
                'description' => $request->description,
                'image' => $request->image,
                'status' => $request->status ?? 1,
                'created_by' => auth()->id(),
            ]);

            // if ($request->ajax()) {
            //     return response()->json([
            //         'success' => true,
            //         'message' => 'Category created successfully!',
            //         'data' => $category
            //     ]);
            // }
              DB::commit();

            return redirect()->route('rawcategory.index')->with('success', 'Raw Category created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    /**
     * Edit category
     */
    public function edit($id)
    {
        try {
            $id = decrypt($id);
            $category = RawCategory::findOrFail($id);
            $categories = RawCategory::whereNull('parent_category_id')->get();

            return view('admin.rawcategory.edit', compact('category', 'categories'));
       } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    /**
     * Update category
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $category = RawCategory::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:100',
                  'code' => 'required|min:3|unique:categories,code,' . $category->id,
     
            ]);

            $category->update([
                
                'name' => $request->name,
                'parent_category_id' => $request->parent_category_id,
                'code' => $request->code,
                'description' => $request->description,
                'image' => $request->image,
                'status' => $request->status,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();


            return redirect()->route('rawcategory.index')->with('success', 'Raw Category updated successfully!');
      } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $id = decrypt($id);
        $category = RawCategory::findOrFail($id);
        return view('admin.rawcategory.show', compact('category'));
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        try {
            $category = RawCategory::findOrFail($id);
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exception handler (for AJAX + normal)
     */
    private function handleException(Exception $e, Request $request, $action)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => "Error while {$action} category: " . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', "Error while {$action} category: " . $e->getMessage());
    }
}