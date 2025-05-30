<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ChatGptService;
use Illuminate\Support\Facades\DB;
use App\DataTables\ProductsDataTable;
use App\Http\Requests\ProductPostRequest;
use App\Services\OpenAIService;

class ProductController extends Controller
{
    protected $chat;

    public function __construct(ChatGptService $chat)
    {
        $this->chat = $chat;
        // $this->openai = $openai;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('pages.product.index', ['type_menu' => 'product']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('pages.product.create', ['type_menu' => 'product']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductPostRequest $request)
    {
        $validated = $request->validated();

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->price = str_replace(".", "", $request->price);
        $product->description = $request->description;

        $product->save();
        $notification = [
            'message' => 'Data Berhasil Dibuat',
            'alert-type' => 'success'
        ];

        return redirect()->route('product.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->price = str_replace(',', '.', str_replace(".00", "", number_format($product->price, 2)));
        return view('pages.product.show', ['type_menu' => 'product', 'product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductPostRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product->product_name = $request->product_name;
        $product->price = str_replace(".", "", $request->price);
        $product->description = $request->description;

        $product->save();
        $notification = [
            'message' => 'Data Berhasil Diubah',
            'alert-type' => 'success'
        ];

        return redirect()->route('product.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        $notification = [
            'message' => 'Data Berhasil Dihapus',
            'alert-type' => 'success'
        ];

        return redirect()->route('product.index')->with($notification);
    }

    public function search(Request $request)
    {
        // $query = $request->input('query');
        $query = $request->description;

        $input = $query;

        $keywords = $this->chat->extractKeywords($input);

        $queryData = DB::table('products');

        foreach ($keywords as $keyword)
        {
            $queryData->orWhere('product_name', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%")
                ->orWhere('price', 'like', "%$keyword%");
        }
        $data = $queryData->get();

        if (!count($data))
        {
            try
            {
                $sql = $this->chat->generateProductQuery($query);
                $data = DB::select(DB::raw($sql));
            }
            catch (\Exception $e)
            {
                return response()->json(['error' => 'Query gagal: ' . $e->getMessage()], 400);
            }
        }

        if (!count($data))
        {
            $data = $this->chat->chat($query);
        }
        return response()->json($data);
    }
}
