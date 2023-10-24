<?php

namespace Artaskynet\Id;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Routing\Controller;

class Artaid extends Controller {

	private $user, $token;

	private function AuthLogin () {
		if (!$this->user) return;

		$user = User::find($this->user['id']);
		$user = $user ? $user : new User;

		$user->id = $this->user['id'];
		$user->name = $this->user['name'];
		$user->surname = $this->user['surname'];
		$user->patronymic = $this->user['patronymic'];
		$user->email = $this->user['email'];
		$user->admin = $this->user['admin'];

		$user->save();

		session([
			'guestId' => session()->getId()
		]);

		auth()->loginUsingId($user->id);
		$user->tokens()->delete();

		$token = $user->createToken('web');

		return $token->plainTextToken ?? '';
	}

	public function Auth (Request $req) {
		$response = Http::withOptions(["verify"=>false])->get("https://id.artasky.net/api/auth/" . $req->hash);
		if ($response->ok()) {
			$this->user = $response->json();
			$this->token = $this->AuthLogin();
			return [
				'user' => auth()->user(),
				'token' => $this->token
			];
		}
		throw new HttpException(500, "Error authorization");
	}
}
