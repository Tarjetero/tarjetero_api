<?php

namespace App\Http\Middleware;

use Closure;

class CompressesResponses
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);

		if ($this->shouldCompress($response)) {
			$response->setContent(gzencode($response->getContent(), 9));
			$response->headers->set('Content-Encoding', 'gzip');
			$response->headers->set('Vary', 'Accept-Encoding');
		}

		return $response;
	}

	protected function shouldCompress($response)
	{
		$content = $response->getContent();
		$data = json_decode($content);
		if ($data && isset($data->datos->base64)) {
			// Desactiva la compresión Gzip en la respuesta
			$response->header('Content-Encoding', 'identity');
			return false;
		}
		return true;
	}
}
