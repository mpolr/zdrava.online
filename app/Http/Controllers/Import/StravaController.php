<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Models\StravaToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Strava;

class StravaController extends Controller
{
    private string $accessToken;
    private string $refreshToken;
    private int $expiresAt;
    private int $stravaUserId;

    public function auth(): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
//        return Strava::authenticate($scope = 'read_all,profile:read_all,activity:read_all');
        return Strava::authenticate($scope = 'read_all,profile:read_all,activity:read_all', route('strava.token', [
            'uid' => auth()->user()->id,
            'cs' => md5(auth()->user()->email),
        ]));
    }

    public function getToken(Request $request)
    {
        $token = Strava::token($request->code);

        if (empty($token) || !$token instanceof \stdClass) {
            session()->flash('error', 'Не удалось получить токен от Strava!');
            return redirect()->route('site.dashboard');
        }

        if (empty($request->uid) || empty($request->cs)) {
            session()->flash('error', 'Не переданы необходимые данные!');
            return redirect()->route('site.dashboard');
        }

        $user = User::where(['id' => $request->uid])->first();

        if (empty($user) || $request->cs !== md5($user->email)) {
            session()->flash('error', 'Ошибка. Получены неверные данные');
            return redirect()->route('site.dashboard');
        }

        $this->accessToken = $token->access_token;
        $this->refreshToken = $token->refresh_token;
        $this->stravaUserId = $token->athlete->id;
        $this->expiresAt = $token->expires_at;

        $savedToken = StravaToken::where('strava_user_id', $token->athlete->id)->first();
        if (empty($savedToken)) {
            $savedToken = new StravaToken();
            $savedToken->access_token = $this->accessToken;
            $savedToken->refresh_token = $this->refreshToken;
            $savedToken->user_id = $user->id;
            $savedToken->strava_user_id = $this->stravaUserId;
        } else {
            $this->refreshToken();
        }

        $savedToken->expires_at = Carbon::createFromTimestamp($this->expiresAt)->toDateTimeString();
        $savedToken->save();

        if (!empty($token->athlete->profile)) {
            if (empty($user->getPhoto())) {
                $user->update([
                    'photo' => $token->athlete->profile,
                ]);
            }
        }

        $athlete = Strava::athlete($this->accessToken);
        $activities = Strava::activities($this->accessToken, 1, 50);
        $athleteRoutes = Strava::athleteRoutes($this->accessToken, $this->stravaUserId, 1, 50);
        $starredSegments = Strava::starredSegments($this->accessToken, 1, 50);
    }

    private function refreshToken() {
        if (strtotime(Carbon::now()) > $this->expiresAt) {
            $refresh = Strava::refreshToken($this->refreshToken);

            $this->accessToken = $refresh->access_token;
            $this->refreshToken = $refresh->refresh_token;

            StravaToken::where('strava_user_id', $this->stravaUserId)->update([
                'access_token' => $this->accessToken,
                'refresh_token' => $this->refreshToken,
            ]);
        }
    }
}
