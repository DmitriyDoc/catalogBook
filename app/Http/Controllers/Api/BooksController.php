<?php


namespace App\Http\Controllers\Api;


use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function App\Helpers\transaction;
use function Nette\Utils\first;
use function Symfony\Component\Mime\Header\all;

class BooksController
{
    public function index(Request $request)
    {
        if (!$request->query('search')){
            return response()->json(Book::with('writers')->orderBy('id','desc')->paginate($request->size??5));
        }
        $q = $request->query('search');
        $searchResult = $this->users = Book::query()->whereHas('writers', function ($query) use ($q)
        {
            return $query->where('name', 'like', '%' . $q . '%');
        })
            ->with('writers')
            ->orWhere('title','LIKE','%'. $q .'%')
            ->paginate($request->size??5)->toArray();
        return response()->json($searchResult);
    }

    public function show($id)
    {
        return response()->json(Book::with(['writers','categories'])->find($id));
    }

    public function store(Request $request)
    {
        $data = Validator::make($request->all(),[
            'title' => 'required|string|max:150',
            'year' => 'required|numeric|between:1900,'.date('Y'),
            'description' => 'required|string|max:2000',
            'image' =>'required|image|mimes:jpg,png,jpeg|max:512000',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }
        $imagePath = $request->file('image')->store('images','public');
        Book::create([
            'title' => trim($request->title),
            'year' => trim($request->year),
            'description' => trim($request->description),
            'cover' => '/storage/'.$imagePath,
        ]);
        return response()->json(['success'=>'Книга добавлена успешно!']);
    }

    public function update(Request $request)
    {

        $data = Validator::make($request->all(),[
            'title' => 'required|string|max:150',
            'year' => 'required|numeric|between:1900,'.date('Y'),
            'description' => 'required|string|max:2000',
            'image' =>'image|mimes:jpg,png,jpeg|max:512000',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }
        $book = Book::findOrFail($request->id);

        if ($request->hasFile('image')) {
            $imagePath = '/storage/'.$request->file('image')->store('images','public');
            Storage::disk('public')->delete('images/'.ltrim($request->cover,'/storage/images/'));
        }
        $updateData = [
            'title' => $request->title,
            'year' => $request->year,
            'description' => $request->description,
            'cover' => isset($imagePath) ? $imagePath : $request->cover,
        ];

        if ($book->updateOrFail($updateData) === false) {
            return response()->json(['error'=>"Запись под id - {$request->id} не может быть обновлена."]);
        }
        return response()->json(['success'=>'Книга обновлена успешно!']);
    }
    public function destroy(Request $request){

        $data = Validator::make($request->all(),[
            'id' => 'required|numeric',
        ]);
        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }
        $bookId = $data->getData();
        transaction( function () use ($bookId){
            $book = Book::where('id',$bookId['id']);
            $cover = $book->first()->cover;
            $book->delete();
            BookWriter::where('books_id',$bookId['id'])->delete();
            BookCategory::where('books_id',$bookId['id'])->delete();
            Storage::disk('public')->delete('images/'.ltrim($cover,'/storage/images/'));
        });
        return response()->json(['success'=>'Удаление прошло успешно!']);
    }
}
