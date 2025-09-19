<?php

declare(strict_types=1);

namespace App\Services\Stripe;

use Exception;
use Stripe\Account;
use Stripe\AccountLink;

use function PHPSTORM_META\type;

class OrganizerService extends BaseService
{
    public function createAccount(string $email): array|bool
    {
        try {

            // Motivos para escolher o Express:

            // Os oranizadores tem um painel Stripe, mas sua plataforma gerencia o fluxo de pagamentos.
            // O Stripe cuida de impostos, transferências bancárias e suporte ao organizados.
            // Menos burocracia para você e mais rápido para implementar.
            // Ideal para marketplaces no Brasil, onde a parte fiscal pode ser comlexa.

            $account = Account::create([
                'type' => 'express',
                'country' => 'BR',
                'email' => $email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true]
                ]
            ]);

            $linkUrl = $this->getLink($account->id);

            if (! is_string($linkUrl)) {
                throw new Exception("Erro ao gerar o link de criação/conclusão da conta na Stripe");
            }

            return [
                'id' => $account->id,
                'link' => $linkUrl
            ];
        } catch (\Throwable $th) {
            log_message('error', '[STRIPE ERROR] {exception}', ['exception' => $th]);
            return false;
        }

        return [];
    }

    public function getLink(string $stripeAccountId): string|bool
    {
        try {

            $cacheKey = "stripe_connect_link_{$stripeAccountId}";
            $cahceLink = cache($cacheKey);

            if ($cahceLink) {
                return $cahceLink;
            }

            $url = url_to('dashboard.organizer');

            $link = AccountLink::create([
                'account' => $stripeAccountId,
                'refresh_url' => $url,
                'return_url' => $url,
                'type' => 'account_onboarding',
            ]);

            cache()->save($cacheKey, $link->url, 7200);

            return $link->url;
        } catch (\Throwable $th) {
            log_message('error', '[STRIPE ERROR] {exception}', ['exception' => $th]);
            return false;
        }
    }

    public function getAccount(string $stripeAccountId): Account|bool
    {
        try {

            $account = Account::retrieve($stripeAccountId);
            return $account;
        } catch (\Throwable $th) {
            log_message('error', '[STRIPE ERROR] {exception}', ['exception' => $th]);
            return false;
        }
    }

    public function accountIsCompleted(Account $account): bool
    {
        $stripeAccountIsCompleted =
            $account->details_submitted && // Conta verificada (details_submitted = true)
            $account->charges_enabled && // Pagamentos habilitados (charges_enabled)
            $account->payouts_enabled && // Repasses habilitados (pauouts_enabled)
            empty($account->requirements->currently_due); // Não podem haver pendências

        return $stripeAccountIsCompleted;
    }

    public function getDashboardLink(string $stripeAccountId): string|bool
    {
        try {

            $cacheKey = "stripe_dashboard_link_{$stripeAccountId}";
            $cahceLink = cache($cacheKey);

            if ($cahceLink) {
                return $cahceLink;
            }

            $link = Account::createLoginLink($stripeAccountId);

            cache()->save($cacheKey, $link->url, 7200);

            return $link->url;
            
        } catch (\Throwable $th) {
            log_message('error', '[STRIPE ERROR] {exception}', ['exception' => $th]);
            return false;
        }
    }
}
