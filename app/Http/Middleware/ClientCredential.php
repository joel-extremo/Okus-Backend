<?php
namespace App\Http\Middleware;

use Illuminate\Http\Response;
use App\BaseModel;
use Closure;

class ClientCredential
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($request["client_id"] AND $request["client_secret"]){
            $result = BaseModel::verifyClientCredentials($request["client_id"], $request["client_secret"]);

            if($result){
                return $next($request);
            }else{
                return (new Response('Credential authentication error', 401));
            }
        }else{
            return (new Response('No credentials', 401));
        }

        
   
        

    }
}
