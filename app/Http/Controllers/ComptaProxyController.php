<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ComptaProxyController extends Controller
{
    public function __invoke(Request $request, ?string $path = null)
    {
        $baseUrl = rtrim((string) config('services.compta.url'), '/');

        // #region agent log
        $__dbg = function (string $message, array $data, string $hypothesisId) {
            $payload = json_encode([
                'sessionId' => '5fa0d4',
                'runId' => 'post-fix',
                'hypothesisId' => $hypothesisId,
                'location' => 'ComptaProxyController.php',
                'message' => $message,
                'data' => $data,
                'timestamp' => (int) (microtime(true) * 1000),
            ], JSON_UNESCAPED_UNICODE);
            @file_put_contents(
                '/home/bienvenu/Documents/ADVANCED ITB/budental/.cursor/debug-5fa0d4.log',
                $payload."\n",
                FILE_APPEND
            );
        };
        $__dbg('proxy_invoke', [
            'baseUrl' => $baseUrl,
            'path' => $path,
            'method' => $request->method(),
            'env_COMPTA_API_URL' => env('COMPTA_API_URL'),
        ], 'A');
        // #endregion

        if ($baseUrl === '') {
            return response()->json([
                'success' => false,
                'message' => 'COMPTA_API_URL non configurée.',
                'data' => null,
                'error' => ['config' => 'services.compta.url'],
            ], 503);
        }

        $targetPath = ltrim((string) $path, '/');
        $url = $baseUrl.'/api'.($targetPath !== '' ? '/'.$targetPath : '');

        if ($request->getQueryString()) {
            $url .= '?'.$request->getQueryString();
        }

        // #region agent log
        $__dbg('proxy_target_url', ['url' => $url], 'C');
        $__t0 = microtime(true);
        // #endregion

        try {
            $response = Http::acceptJson()
                ->withHeaders($this->forwardHeaders($request))
                ->timeout(8)
                ->connectTimeout(3)
                ->send(
                    $request->method(),
                    $url,
                    $this->requestOptions($request)
                );
        } catch (ConnectionException $e) {
            // #region agent log
            $__dbg('proxy_connection_exception', [
                'url' => $url,
                'error' => $e->getMessage(),
                'ms' => (int) ((microtime(true) - $__t0) * 1000),
            ], 'A');
            // #endregion
            return response()->json([
                'success' => false,
                'message' => 'API comptabilité injoignable.',
                'data' => null,
                'error' => $e->getMessage(),
            ], 502);
        } catch (RequestException $e) {
            // #region agent log
            $__dbg('proxy_request_exception', [
                'url' => $url,
                'error' => $e->getMessage(),
                'ms' => (int) ((microtime(true) - $__t0) * 1000),
            ], 'C');
            // #endregion
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'appel à l\'API comptabilité.',
                'data' => null,
                'error' => $e->getMessage(),
            ], 502);
        }

        // #region agent log
        $__dbg('proxy_success', [
            'url' => $url,
            'status' => $response->status(),
            'ms' => (int) ((microtime(true) - $__t0) * 1000),
        ], 'A');
        // #endregion

        return response($response->body(), $response->status())
            ->withHeaders([
                'Content-Type' => $response->header('Content-Type') ?? 'application/json',
            ]);
    }

    private function forwardHeaders(Request $request): array
    {
        $headers = [
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ];

        if ($request->hasHeader('Content-Type')) {
            $headers['Content-Type'] = $request->header('Content-Type');
        }

        $token = config('services.compta.token');
        if ($token) {
            $headers['Authorization'] = 'Bearer '.$token;
        }

        return $headers;
    }

    private function requestOptions(Request $request): array
    {
        if (in_array($request->method(), ['GET', 'HEAD'], true)) {
            return [];
        }

        if ($request->isJson() || str_contains((string) $request->header('Content-Type'), 'application/json')) {
            return ['json' => $request->all()];
        }

        return ['form_params' => $request->all()];
    }
}
