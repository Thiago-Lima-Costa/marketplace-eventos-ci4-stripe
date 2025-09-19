<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\Stripe\OrganizerService;
use CodeIgniter\HTTP\RedirectResponse;
use Stripe\Account;

class OrganizerController extends BaseController
{
    public function edit()
    {
        $data = [
            'title' => 'Organizador de Eventos',
            'user' => auth()->user(),
        ];

        return view('Dashboard/Organizer/form', $data);
    }

    public function create(): RedirectResponse
    {
        $user = auth()->user();

        $organizerService = new OrganizerService;
        $result = $organizerService->createAccount($user->email);

        if(! is_array($result)) {
            return redirect()->back()->with('danger', 'Ocorreu um erro ao criar a conta na Stripe');
        }

        $model = auth()->getProvider();
        $user->stripe_account_id = $result['id'];
        $user->stripe_account_is_completed = false;

        $model->setAllowedFields(['stripe_account_id', 'stripe_account_is_completed']);
        $model->save($user);

        return redirect()->to($result['link']);
    }

    public function check(): RedirectResponse
    {
        $user = auth()->user();
        $organizerService = new OrganizerService;

        $result = $organizerService->getAccount($user->stripe_account_id);

        if(! $result instanceof Account) {
            return redirect()->back()->with('danger', 'Ocorreu um erro ao consultar a conta na Stripe');
        }

        if($organizerService->accountIsCompleted($result)) {
            $model = auth()->getProvider();
            $user->stripe_account_is_completed = true;
            $model->setAllowedFields(['stripe_account_is_completed']);
            $model->save($user);
            return redirect()->back()->with('success', 'Conta verificada com sucesso!');
        }

        $link = $organizerService->getLink($user->stripe_account_id);

        if(!is_string($link)) {
            return redirect()->back()->with('danger', 'Ocorreu um erro ao gerar o link de conclusão da conta Stripe');
        }

        return redirect()->to($link);
    }

    public function panel(): RedirectResponse
    {
        $user = auth()->user();
        $organizerService = new OrganizerService;

        $link = $organizerService->getDashboardLink($user->stripe_account_id);

       if(!is_string($link)) {
            return redirect()->back()->with('danger', 'Ocorreu um erro ao gerar o link de acesso ao painel da conta Stripe');
        }

        return redirect()->to($link);
    }
}
