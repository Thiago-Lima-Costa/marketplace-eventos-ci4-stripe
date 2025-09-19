<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Organizer implements FilterInterface
{
    /**
     * Identifica se o user está logado e se tem conta na Stripe e se está completamente verificada. 
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if(!auth()->loggedIn()){
            return redirect()->route('login');
        }

        if(! (bool) auth()->user()->stripe_account_is_completed){
            return redirect()->route('dashboard.organizer')->with('info', 'Você precisa concluir a sua conta na Stripe antes de prosseguir.');
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
