<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class IsbnController extends Controller
{
    public function store(Request $request)
    {
        $isbn = $request->input('isbn');
        $passou = false;
        $soma = 0;

        $quantidadeCaracter = strlen($isbn);

        if ($quantidadeCaracter == 10) {
            for ($i = 0; $i < 9; $i++) {
                if (preg_match('/[0-9]$/',$isbn[$i]) == 0)
                {
                    return response()->json('isbn invalido');
                }
                $soma += $isbn[$i] * (10 - $i);
            }
            if (preg_match('/[x+X]$/',$isbn[9])==1) 
            {
                $soma += 10;
            } 
            else if (preg_match('/^[0-9]$/',$isbn[9]) == 1) 
            {
                $soma += $isbn[9];
            }else{
                return response()->json('isbn invalido');
            }
            if (($soma % 11) == 0) 
            {
                $passou = true;
            }
        }

        //return response()->json($soma); isbn13 9788576842125

        if ($passou == true) {
            //$url = "https://openlibrary.org/isbn/$isbn.json";
            $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";

            $response = json_decode(file_get_contents($url));

            return ($response);
        }
    }
}
