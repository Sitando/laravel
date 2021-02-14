<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\View\View;
use Swift_TransportException;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
	/**
	 * Send a contact message.
	 *
	 * @param App\Http\Requests\ContactRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function send(ContactRequest $request): RedirectResponse
	{
		try {
			Mail::to(config('mail.address'))->send(new ContactMail($this->trimRequest($request->all())));
		} catch (Swift_TransportException $exception) {
			logger()->error($exception->getMessage());
			if ($request->wantsJson()) {
				return response([
					'message' => __('No internet connection detected'),
				], 599);
			}
			return redirect('/contact#errors')->withErrors(__('No internet connection detected'));
		}
		if ($request->wantsJson()) {
			return response([
				'message' => __('')
			]);
		}
		return redirect('/contact#success')->with('success');
	}

	/**
	 * Remove any additional data on the request
	 * Merely a security measure.
	 *
	 * @return array $details
	 */
	public function trimRequest(array $data): array
	{
		$details = [
			'name' => $data['name'],
			'email' => $data['email'],
			'subject' => $data['subject'],
			'message' => $data['message'],
		];

		return $details;
	}

	public function show(): View
	{
		return view('contact');
	}
}
