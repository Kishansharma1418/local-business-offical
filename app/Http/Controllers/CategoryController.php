<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class CategoryController extends Controller
{
    /**
     * Display category list
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::query()->orderBy('id', 'DESC');;

            if ($request->category_name) {
                $categories->where('category_name', 'LIKE', '%' . $request->category_name . '%');
            }

            return DataTables::of($categories)
                ->addColumn('action', function ($row) {
                    return view('admin.category.action', compact('row'))->render();
                })
                ->addColumn('parent_category', function ($row) {
                    return $row->parent ? $row->parent->category_name : '-';
                })
                ->editColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success px-2 py-1">Active</span>'
                        : '<span class="badge bg-danger px-2 py-1">Inactive</span>';
                })
                ->rawColumns(['action', 'status', 'parent_category'])
                ->make(true);
        }
        $categories = Category::select('category_name')->groupBy('category_name')->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::whereNull('parent_category_id')->get();
        return view('admin.category.create', compact('categories'));
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'category_name' => 'required|string|max:100',
                'code' => 'required|string|max:255|unique:categories,code',
            ]);

            $category = Category::create([
                'category_name' => $request->category_name,
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

            return redirect()->route('category.index')->with('success', 'Category created successfully!');
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
            $category = Category::findOrFail($id);
            $categories = Category::whereNull('parent_category_id')->get();

            return view('admin.category.edit', compact('category', 'categories'));
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
            $category = Category::findOrFail($id);

            $request->validate([
                'category_name' => 'required|string|max:100',
                'code' => 'required|min:3|unique:categories,code,' . $category->id,
            ]);

            $category->update([
                'category_name' => $request->category_name,
                'parent_category_id' => $request->parent_category_id,
                'code' => $request->code,
                'description' => $request->description,
                'image' => $request->image,
                'status' => $request->status,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('category.index')->with('success', 'Category updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $id = decrypt($id);
        $category = Category::findOrFail($id);
        return view('admin.category.show', compact('category'));
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
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
