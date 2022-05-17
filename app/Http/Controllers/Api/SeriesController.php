<?php

namespace App\Http\Controllers\Api;

use App\Models\Series;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Repositories\SeriesRepository;
use App\Http\Requests\SeriesFormRequest;

class SeriesController extends Controller
{

    public function __construct(private SeriesRepository $seriesRepository)
    {
    }

    public function index(Request $request)
    {
        //if (!$request->has('nome')) {
        //return Series::all();
        //}

        //return Series::whereNome($request->nome)->get();

        $query = Series::query();
        if (!$request->has('nome')) {
            $query->where('nome', $request->nome);
        }

        return $query->paginate(5,);
    }

    public function store(SeriesFormRequest $request)
    {
        return response()->json($this->seriesRepository->add($request), 201);
    }

    public function show(int $series)
    {
        //$series = Series::whereId($series)->with('seasons.episodes')->first();

        //$seriesModel = Series::find($series);
        $seriesModel = Series::with('seasons.episodes')->find($series);
        if ($seriesModel === null) {
            return response()->json(['message' => 'Serie não encontrada'], 404);
        }
        //return $series;
        return $seriesModel;
    }

    public function update(SeriesFormRequest $request, Series $series)
    {
        //return response()->json($this->seriesRepository->update($request, $series), 200);
        $series->fill($request->all());

        //Series::where(‘id’, $series)->update($request->all());
        // retorno de uma resposta que não contenha a série, já que não fizemos um `SELECT`
        $series->save();
        return $series;
    }

    public function destroy(int $series, Authenticatable $user)
    {
        //return response()->json($this->seriesRepository->delete($series), 204);
        Series::destroy($series);
        return response()->noContent();
    }
}
