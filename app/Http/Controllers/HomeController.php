<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // SEO Configuration
        SEOMeta::setTitle('Buku Karir – Catat & Pantau Semua Lamaran Kerja Anda dengan Mudah')
            ->setDescription('Buku Karir adalah platform sederhana untuk mencatat dan memantau seluruh lamaran kerja Anda di satu tempat. Efisien, cepat, dan terorganisir demi memaksimalkan peluang karir Anda.')
            ->setCanonical(url('/'))
            ->addKeyword([
                'Buku Karir',
                'lamaran kerja',
                'melamar pekerjaan',
                'job applications',
                'job tracking',
                'monitoring lamaran',
                'career tracker',
                'manajemen lamaran',
                'aplikasi pencatat lamaran',
                'lowongan kerja',
                'platform karir',
                'efisiensi karir',
                'catat lamaran kerja',
            ]);

        // Open Graph
        OpenGraph::setTitle('Buku Karir – Catat & Pantau Semua Lamaran Kerja Anda dengan Mudah')
            ->setDescription('Platform sederhana untuk mencatat dan memantau seluruh lamaran kerja Anda di satu tempat. Efisien, cepat, dan terorganisir.')
            ->setUrl(url('/'))
            ->addProperty('type', 'website')
            ->addProperty('locale', 'id_ID')
            ->addProperty('locale:alternate', ['en_US'])
            ->addImage(asset('images/logo.svg')); // Ganti dengan path logo Buku Karir Anda

        // JSON-LD
        JsonLd::setTitle('Buku Karir – Catat & Pantau Semua Lamaran Kerja Anda dengan Mudah')
        
            ->setDescription('Buku Karir membantu Anda mencatat dan memantau semua lamaran kerja di satu tempat secara simpel dan efisien.')
            ->setType('WebSite')
            ->addValue('url', url('/'))
            ->addValue('contactPoint', [
                '@type'        => 'ContactPoint',
                'telephone'    => '+62-856-5085-2602',
                'contactType'  => 'customer service',
                'areaServed'   => 'ID',
                'availableLanguage' => ['Indonesian', 'English'],
            ])
            ->addValue('sameAs', [
                'https://instagram.com/bukukarir',
            ]);

        return view('home');
    }
}
