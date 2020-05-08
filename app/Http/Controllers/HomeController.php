<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $array = [
            [
                'account_code' => '74002',
            ],
            [
                'account_code' => '70110',
            ],
            [
                'account_code' => '70003',
            ],
            [
                'account_code' => '74323',
            ],
            [
                'account_code' => '71222',
            ]
        ];

        $final_result = [];
        foreach ($array as $key => $item) {
            self::explodeTree('account_code', $item, $final_result);
        }
        dd($final_result);
        return view('home');
    }

    public static function explodeTree($key, $item, &$final_result, $level = 0)
    {
        $digits = str_split($item[$key]);
        
        if (empty($final_result) || ($level == 0 && !array_key_exists($digits[$level], $final_result))) {
            $final_result[$digits[$level]][] = $item;
            return;
        }

        $indexed = $reindex = false;
        
        foreach ($final_result as $result_item) {
            
            if (!isset($result_item[$key])) {
                $indexed = true;
                break;
            }

            $item_digits = str_split($result_item[$key]);
            if ($item_digits[$level] === $digits[$level]) {
                if ($item_digits[$level + 1] === $digits[$level + 1]) {
                    $final_result[$digits[$level]][] = $item;
                    return;
                } else {
                    self::explodeTree($key, $item, $final_result[$digits[$level]], $level+1);
                }
            } else {
                $reindex = true;
                break;
            }
        }
        if ($indexed) {
            if (!array_key_exists($digits[$level], $final_result)) {
                $final_result[$digits[$level]][] = $item;
                return;
            }
            $next_array = $final_result[$digits[$level]];
            if (isset($next_array[0]) && isset($next_array[0][$key])) {
                $item_digits = str_split($next_array[0][$key]);
                if ($digits[$level+1] == $item_digits[$level+1]) {
                    $final_result[$digits[$level]][] = $item;
                    return;
                }
            }
            
            self::explodeTree($key, $item, $final_result[$digits[$level]], $level+1);
        }

        if ($reindex) {
            $temp_result = $final_result;
            $final_result = [];
            foreach($temp_result as $result_item) {
                $item_digits = str_split($result_item[$key]);
                $final_result[$item_digits[$level]][] = $result_item;
            }
            $final_result[$digits[$level]][] = $item;
            return;
        }
    }
}
