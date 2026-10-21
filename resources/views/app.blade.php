<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO Core Meta Tags --}}
    <title>SIGMA-3 — MTsN 3 Tulungagung | Sistem Informasi Digital Terpadu</title>
    <meta name="description" content="SIGMA-3 adalah Sistem Informasi Madrasah Terpadu MTsN 3 Tulungagung. Layanan presensi RFID pintar, monitoring ibadah, portal wali murid, rekapitulasi nilai, dan asisten virtual AI.">
    <meta name="keywords" content="SIGMA-3, MTsN 3 Tulungagung, Matsanega, Madrasah Tsanawiyah Negeri 3 Tulungagung, Presensi RFID, Portal Wali Murid, Rapor Digital">
    <meta name="author" content="MTsN 3 Tulungagung">
    <meta name="robots" content="index, follow, max-image-preview:large">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="SIGMA-3 — MTsN 3 Tulungagung | Sistem Informasi Digital Terpadu">
    <meta property="og:description" content="Sistem Informasi Madrasah Terpadu MTsN 3 Tulungagung: Presensi RFID, monitoring ibadah, portal izin wali murid, dan layanan akademik.">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="SIGMA-3 MTsN 3 Tulungagung">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SIGMA-3 — MTsN 3 Tulungagung">
    <meta name="twitter:description" content="Sistem Informasi Madrasah Terpadu MTsN 3 Tulungagung. Presensi RFID, portal wali murid & layanan madrasah.">

    {{-- Structured Data (JSON-LD) Schema.org --}}
    <script type="application/ld+json">
    {!! json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'EducationalOrganization',
      'name' => 'MTsN 3 Tulungagung',
      'alternateName' => 'MATSANEGA',
      'url' => 'https://sigma3.mtsn3tulungagung.sch.id',
      'description' => 'Sistem Informasi Madrasah Terpadu MTsN 3 Tulungagung (SIGMA-3)',
      'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => 'Tulungagung',
        'addressRegion' => 'Jawa Timur',
        'addressCountry' => 'ID',
      ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
