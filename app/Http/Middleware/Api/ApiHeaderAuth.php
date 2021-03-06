<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Auth;

class ApiHeaderAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $applicable = TRUE;
        $exceptRoute = ['register', 'login'];
        foreach ($exceptRoute as $er) {
            if (is_numeric(strpos($request->path(), $er))) {
                $applicable = FALSE;
                break;
            }
        }
        if ($applicable) {
            $message = [];
            $request->headers->set('Accept', str_replace(['*/*', ' '], '', $request->headers->get('Accept')));
            $contentType = $request->headers->get('Content-Type');
            if ($contentType && trim($contentType) === 'application/json') {
                $request->headers->set('Accept', 'application/json');
            }
            $accept = trim($request->headers->get('Accept'));
            $authorization = trim($request->headers->get('Authorization'));
            $authorization = $authorization ? explode(' ', $authorization) : '';
            if (!$accept) {
                $message[] = 'Content-Type or Accept is not defined';
            } else if ($accept != 'application/json') {
                $message[] = 'Invalid Content-Type or Accept';
            }
            if (!$authorization) {
                $message[] = 'Authorization token is not defined';
            } else if ($authorization[0] != "Bearer") {
                $message[] = 'Token type must be Bearer'; // must be Bearer';
            } else if (!Auth::guard('api')->check()) {
                $message[] = 'Invalid Authorization token.!';
            }
            if (!empty($message)) {
                return response()->json(['done'=>false,'message' => $message], 401);
            };
        }
        return $next($request);        
    }

}
