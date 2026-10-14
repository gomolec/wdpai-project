<?php

declare(strict_types=1);

namespace App\Controller;


final class DefaultController extends AppController
{
    public function show(int $id): void
    {
        $this->render('project', [
            'pageTitle' => 'Moja aplikacja',
        ]);
    }

    public function index(): void
    {
        $this->render('home', [
            'pageTitle' => 'Moja aplikacja',
        ]);
    }

    public function notFound(): void
    {
        $this->render('not-found', [
            'pageTitle' => 'Nie znaleziono strony',
        ]);
    }
}