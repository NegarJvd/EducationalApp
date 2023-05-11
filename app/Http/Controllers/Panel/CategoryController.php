<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $permissions = [
            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
        ];

        foreach ($permissions as $permission) {
            $array = Permission::where('name', $permission)->get();
            if(count($array) == 0){
                Permission::create(['name' => $permission]);
            }
        }

        $this->middleware('permission:category-list|category-create|category-edit|category-delete');
        $this->middleware('permission:category-list', ['only' => ['index','show']]);
        $this->middleware('permission:category-create', ['only' => ['create','store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){
        $categories = Category::query();

        $search = $request->get('search');
        if (!is_null($search)){
            $categories = $categories->where(function ($query) use($search){
                                    $query->where('fa_name', 'LIKE', "%$search%");
                                    $query->orWhere('en_name' , 'LIKE', "%$search%");
            });
        }

        $categories = $categories->orderBy("id", 'desc')->paginate($this->perPagePanel);

        return view('panel.categories.index', compact('categories'));
    }

    public function show($id){
        $category = Category::find($id);

        if($category){
            return view('panel.categories.show', compact('category'));
        }

        return abort(404);
    }

    public function create(){
        return view('panel.categories.create');
    }

    public function store(Request $request){
        $request->validate([
            'fa_name' => ['required', 'string', 'max:255', 'unique:categories'],
            'en_name' => ['nullable', 'string', 'max:255', 'unique:categories'],
            'discount' => ['numeric', 'min:0', 'max:100'],
        ]);

        Category::create($request->all());

        return redirect()->route('panel.categories.index')
            ->with('success','دسته بندی با موفقیت ایجاد شد.');
    }

    public function edit(Category $category){
        return view('panel.categories.edit', compact('category'));
    }

    public function update($id, Request $request){
        $request->validate([
            'fa_name' => ['required', 'string', 'max:255', 'unique:categories,fa_name,'.$id],
            'en_name' => ['nullable', 'string', 'max:255', 'unique:categories,en_name,'.$id],
            'discount' => ['numeric', 'min:0', 'max:100'],
        ]);

        $category = Category::find($id);

        if(!$category){
            return abort(404);
        }

        $category->update($request->all());

        return redirect()->route('panel.categories.index')
            ->with('success','دسته بندی با موفقیت ویرایش شد.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if(!$category){
            return abort(404);
        }

        if($category->products()->count() == 0){
            $category->delete();
        }else{
            return redirect()->route('panel.categories.index')
                ->with('error',' دسته بندی  ' . $category->fa_name . 'دارای محصول بوده و قابل حذف نیست.');
        }

        return redirect()->route('panel.categories.index')
            ->with('success','دسته بندی با موفقیت حذف شد.');
    }
}
