<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use Lcobucci\JWT\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class ACIDTransactionMiddleware
{
    
    
    public function handle(Request $request, Closure $next)
    {
        DB::beginTransaction();

        try {
        
        $response = $next($request);
        
        if(isset($response->exception)){
        throw $response->exception;
        
        }
        
        DB::commit();
        
        return $response;
        
        }  catch(QueryException $e)
        {
            DB::rollBack();
            return $e;
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
    
    // public function handle(Request $request, Closure $next)
    // {
       
//         DB::beginTransaction();

//         try {
           
//             $response = $next($request);

           
//             if (isset($response->exception)) {
//                 throw $response->exception;
//             }

//             DB::commit();

//             return $response;
//         } catch (Throwable $e) {

//             DB::rollBack();
//             if ($e instanceof ValidationException ||
//             $e instanceof AuthenticationException ||
//             $e instanceof HttpException
//         ) {
//             throw $e;
//         }
//             $statusCode = $e instanceof HttpException ? $e->getStatusCode() : 500;
//             // $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
//             return response()->json([

//                 'message' => $e->getMessage(),

//                 'status' =>  $statusCode,

//                 'type' => get_class($e),
//             ], 500);
//         }
//     }
// }
