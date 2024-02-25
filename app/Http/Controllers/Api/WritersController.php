<?php


namespace App\Http\Controllers\Api;


use App\Models\Book;

use App\Models\BookWriter;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function App\Helpers\transaction;

class WritersController
{
    public function index()
    {
        return response()->json(Writer::with('books')->orderBy('id','desc')->paginate(5));
    }

    public function store(Request $request)
    {

        $data = Validator::make($request->all(),[
            'name' => 'required|string|max:150',
            'country' => 'required|string|max:100,',
            'comment' => 'required|string|max:500',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }

        Writer::create([
            'name' => trim($request->name),
            'country' => trim($request->country),
            'comment' => trim($request->comment),

        ]);
        return response()->json(['success'=>'Автор добавлен успешно!']);
    }
    public function show($id)
    {
        return response()->json(Writer::with(['books'])->find($id));
    }

    public function update(Request $request)
    {

        $data = Validator::make($request->all(),[
            'name' => 'required|string|max:150',
            'country' => 'required|string|max:100,',
            'comment' => 'required|string|max:500',
        ]);

        if ($data->fails()) {
            return response()->json([
                'errors' => $data->errors()
            ], 422);
        }
        $writer = Writer::findOrFail($request->id);

        if ($writer->updateOrFail($request->all()) === false) {
            return response()->json(['error'=>"Запись под id - {$request->id} не может быть обновлена."]);
        }
        return response()->json(['success'=>'Автор обновлен успешно!']);
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
        $writerId = $request->id;
        transaction( function () use ($writerId){
            Writer::where('id',$writerId)->delete();
            BookWriter::where('writers_id',$writerId)->delete();
        });
        return response()->json(['success'=>'Удаление прошло успешно!']);
    }
}
