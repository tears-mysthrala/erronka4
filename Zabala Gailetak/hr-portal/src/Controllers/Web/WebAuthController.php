<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers\Web;

use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Database\Database;

class WebAuthController
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function loginForm(Request $request): Response
    {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            return Response::redirect('/dashboard');
        }

        return Response::view('auth/login');
    }

    public function login(Request $request): Response
    {
        $data = $request->getBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        // TODO: Use real authentication logic from Services/AuthService
        // This is a simplified migration example

        // Simulating DB check for now (Replace with real Auth logic)
        // In a real scenario: $user = $this->authService->authenticate($email, $password);

        if ($email === 'admin@zabala.com' && $password === 'password') {
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Admin User';
            $_SESSION['role'] = 'admin';

            return Response::redirect('/dashboard');
        }

        return Response::view('auth/login', ['error' => 'Credenciales incorrectas']);
    }

    public function logout(Request $request): Response
    {
        session_destroy();
        return Response::redirect('/login');
    }
}
