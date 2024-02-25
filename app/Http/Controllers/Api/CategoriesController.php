<?php


namespace App\Http\Controllers\Api;


use App\Models\BookCategory;
use App\Models\BookWriter;
use App\Models\Category;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\Helpers\transaction;

class CategoriesController
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function show($slug)
    {
        return response()->json(Category::with(['books'])->where('slug','=',$slug)->get());
    }

    public function showById($id)
    {
        return response()->json(Category::with(['books'])->where('id','=',$id)->get());
    }

    public function store(Request $request)
    {
        $data = Validator::make($request->all(),[
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:500',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }

       $category =  Category::firstOrCreate(['title' => trim($request->title)],[
           'title' => trim($request->title),
           'slug' => str_slug(trim($request->title)),
           'description' => trim($request->description),
        ]);

        if ($category->wasRecentlyCreated) {
            return response()->json(['success'=>'Категория успешно создана!']);
        } else {
            return response()->json(['warning'=>'Такая запись уже существует.']);
        }
    }
    public function update(Request $request)
    {
        $data = Validator::make($request->all(),[
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:500',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }
        $category = Category::findOrFail($request->id);
        if ($category->updateOrFail($request->all()) === false) {
            return response()->json(['warning'=>"Запись под id - {$request->id} не может быть обновлена."]);
        }
        return response()->json(['success'=>'Категория обновлена успешно!']);
    }
    public function destroy(Request $request){

        $data = Validator::make(['id'=>$request->id],[
            'id' => 'required|numeric',
        ]);
        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }
        $categoryId = $request->id;
        transaction( function () use ($categoryId){
            Category::where('id',$categoryId)->delete();
            BookCategory::where('categories_id',$categoryId)->delete();
        });
        return response()->json(['success'=>'Удаление прошло успешно!']);
    }
}
