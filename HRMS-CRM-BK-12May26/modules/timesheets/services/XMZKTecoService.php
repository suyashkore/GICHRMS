<?php
namespace app\modules\timesheets\services;
use Exception;

class XMZKTecoService
{
    private string $baseUrl;
    private string $username;
    private string $password;
    private string $tokenFile;
    private int    $timeout;


    private ?string $token = null;

    public function __construct(array $config)
    {
        $this->baseUrl   = rtrim($config['base_url'], '/');
        $this->username  = $config['username'];
        $this->password  = $config['password'];
        $this->tokenFile = $config['token_file'];
        $this->timeout   = $config['timeout'] ?? 15;

        $this->loadToken();
    }

    /* =========================================
     * TOKEN HANDLING
     * ======================================= */

    private function loadToken(): void
    {
        if (!file_exists($this->tokenFile)) {
            return;
        }

        $data = json_decode(file_get_contents($this->tokenFile), true);

        if (!empty($data['token'])) {
            $this->token = $data['token'];
        }
    }

    private function saveToken(string $token): void
    {
        $this->token = $token;

        file_put_contents($this->tokenFile, json_encode([
            'token'      => $token,
            'updated_at' => date('c'),
        ]));
    }

    private function authenticate(): void
    {
        $response = $this->request(
            'POST',
            '/api-token-auth/',
            [
                'username' => $this->username,
                'password' => $this->password,
            ],
            false
        );

        if (empty($response['token'])) {
            throw new Exception('XMZKTeco: Token not returned');
        }

        $this->saveToken($response['token']);
    }

    /* =========================================
     * CORE REQUEST (AUTO REFRESH)
     * ======================================= */

    private function request(
        string $method,
        string $endpoint,
        array  $payload = [],
        bool   $auth    = true,
        bool   $retry   = true
    ) {
        $url = $this->baseUrl . $endpoint;

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        // TODO
        if ($auth && $this->token) {
            // for live mode
            // $headers[] = 'Authorization: Token ' . $this->token;
            
            // for test mode
            $headers[] = 'Authorization: Basic ' . base64_encode(
                $this->username . ':' . $this->password
            );
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => $this->timeout,
        ]);

        if ($method !== 'GET' && !empty($payload)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        // 🔑 XMZKTeco / SimpleJWT token invalid detection
        $tokenInvalid =
            $httpCode === 401 ||
            (
                is_array($data) &&
                in_array(($data['detail'] ?? ''), [
                    'Invalid token.',
                    'Given token not valid for any token type',
                    'Authentication credentials were not provided.'
                ])
            );

        if ($tokenInvalid && $auth && $retry) {
            
            // 🔁 Auto re-login
            $this->authenticate();
            return $this->request($method, $endpoint, $payload, true, false);
        }

        if ($httpCode >= 400) {
            throw new Exception("XMZKTeco API error ($httpCode): $response");
        }

        return $data;
    }

    /* =========================================
     * PUBLIC API METHODS
     * ======================================= */

    public function transactions($params = [])
    {
        $query = http_build_query($params);
        return $this->request('GET', "/iclock/api/transactions/?$query");
    }

    public function allTransactions($params = [])
    {
        $page = 1;
        $pageSize = $params['page_size'] ?? 50;
        $results = [];

        do {
            $params['page'] = $page;
            $params['page_size'] = $pageSize;

            $data = $this->transactions($params);
            $results = array_merge($results, $data['data'] ?? []);

            $hasNext = !empty($data['next']);
            $page++;
        } while ($hasNext);

        return $results;
    }


    public function personnel($params = [])
    {
        $query = http_build_query($params);
        return $this->request('GET', "/iclock/api/personnel/?$query");
    }

    public function employees($params = [])
    {
        $query = http_build_query($params);
        return $this->request('GET', "/personnel/api/employees/?$query");
    }

    public function allEmployees($params = [])
    {
        $page = 1;
        $pageSize = $params['page_size'] ?? 50;
        $results = [];

        do {
            $params['page'] = $page;
            $params['page_size'] = $pageSize;

            $data = $this->employees($params);
            $results = array_merge($results, $data['data'] ?? []);

            $hasNext = !empty($data['next']);
            $page++;
        } while ($hasNext);

        return $results;
    }

}
