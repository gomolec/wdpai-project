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
            'pageTitle' => 'Pan Masełko — Lista zakupów',
            'activeNavigation' => 'lists',
            'activePromotionsCount' => 1,
            'filters' => [
                ['id' => 'all', 'label' => 'Wszystkie (1)', 'active' => true],
                ['id' => 'dairy', 'label' => 'Nabiał (1)', 'active' => false],
                ['id' => 'bakery', 'label' => 'Pieczywo (0)', 'active' => false],
            ],
            'categories' => [
                [
                    'name' => 'Nabiał & Jaja',
                    'progress' => '0 z 1 kupione',
                    'items' => [
                        [
                            'id' => 1,
                            'name' => 'Masło Ekstra 82% 200g',
                            'quantity' => '2 sztuki',
                            'price' => '5,49 zł',
                            'old_price' => '8,45 zł',
                            'promo_label' => 'Biedronka -35%',
                            'promo_variant' => 'error',
                            'purchased' => false,
                            'is_discounted' => true,
                        ],
                    ],
                ],
            ],
            'tip' => [
                'eyebrow' => 'Wskazówka dnia',
                'title_desktop' => 'Kup masło w 3-paku!',
                'title_mobile' => 'Wskazówka od Pana Masełka!',
                'body' => 'W Biedronce obok Ciebie trwa promocja 3 w cenie 2. Zaoszczędzisz dodatkowe 11,20 zł i zamrozisz resztę na pieczenie w weekend!',
            ],
            'summary' => [
                'regular_total' => '8,45 zł',
                'discount_total' => '- 2,96 zł',
                'final_total' => '5,49 zł',
            ],
        ]);
    }

    public function notFound(): void
    {
        $this->render('not-found', [
            'pageTitle' => 'Nie znaleziono strony',
        ]);
    }
}