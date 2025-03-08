<?php

namespace App\Services\Csp\Policies;

use Illuminate\Http\Request;
use Spatie\Csp;
use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Symfony\Component\HttpFoundation\Response;

class ZdravaCspPolicy extends Csp\Policies\Basic
{
    public function configure(): void
    {
        parent::configure();

        $this->reportOnly();

        $this
            ->addDirective(Directive::DEFAULT, [
                'googleapis.com',
                'mc.yandex.ru',
            ])
            ->addDirective(Directive::STYLE, [
                Keyword::UNSAFE_EVAL,
                Keyword::UNSAFE_HASHES,
                Keyword::STRICT_DYNAMIC,
            ])
            ->addDirective(Directive::IMG, [
                'data:',
                'blob:',
                '*.tile.openstreetmap.org',
            ])
            ->addDirective(Directive::SCRIPT, [
                Keyword::UNSAFE_EVAL,
                Keyword::STRICT_DYNAMIC,
            ])
            ->addDirective(Directive::CONNECT, [
                'glitchtip.mpolr.ru',
                'mc.yandex.ru',
            ])
            ->addDirective(Directive::FONT, [
                Keyword::SELF,
                'data:',
                'fonts.gstatic.com',
            ]);
    }

    public function shouldBeApplied(Request $request, Response $response): bool
    {
        if (config('app.debug') && ($response->isClientError() || $response->isServerError())) {
            return false;
        }

        return parent::shouldBeApplied($request, $response);
    }
}
