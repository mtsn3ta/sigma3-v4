import React from "react";
import { usePage, Head } from "@inertiajs/react";
import FrontendLayout from "@/Layouts/FrontendLayout";
import SmartHero from "@/Components/Landing/SmartHero";
import LiveTelemetry from "@/Components/Landing/LiveTelemetry";
import QuickServiceGrid from "@/Components/Landing/QuickServiceGrid";
import BentoFeatures from "@/Components/Landing/BentoFeatures";
import PrincipalVision from "@/Components/Landing/PrincipalVision";
import CallToAction from "@/Components/Landing/CallToAction";

export default function Landing() {
    const {
        settings = {},
        statistics = {},
        latestAchievements = [],
        featuredExtracurriculars = [],
        latestGallery = [],
        attendanceSummary = {},
    } = usePage().props;

    const schoolName = settings.school_name || "MTsN 3 Tulungagung";

    return (
        <FrontendLayout>
            <Head>
                <title>{`SIGMA-3 - ${schoolName}`}</title>
                <meta
                    name="description"
                    content="SIGMA-3: Sistem Informasi Digital Terpadu MTsN 3 Tulungagung. Presensi RFID pintar, monitoring ibadah, portal wali murid, rekap nilai, dan layanan asisten AI resmi madrasah."
                />
                <link rel="canonical" href="https://sigma3.mtsn3tulungagung.sch.id/" />
                <meta property="og:title" content={`SIGMA-3 - ${schoolName}`} />
                <meta
                    property="og:description"
                    content="Sistem Informasi Digital Terpadu MTsN 3 Tulungagung: Presensi RFID, portal wali murid & layanan madrasah."
                />
                <meta property="og:url" content="https://sigma3.mtsn3tulungagung.sch.id/" />
                <meta property="og:type" content="website" />
                {settings.logo && <meta property="og:image" content={settings.logo} />}
            </Head>

            {/* 1. Smart Hero with RFID e-Card Showcase & Fast NISN Search Console */}
            <SmartHero settings={settings} />

            {/* 2. Live Telemetry Strip (% Hadir Hari Ini & Status IoT) */}
            <LiveTelemetry
                attendanceSummary={attendanceSummary}
                statistics={statistics}
            />

            {/* 3. 4 Clean Quick Service Tiles + Mockup Asisten AI WhatsApp */}
            <QuickServiceGrid />

            {/* 4. Layanan Unggulan (Bento Grid: RFID Live, Prestasi, Ekskul, Galeri) */}
            <BentoFeatures
                attendanceSummary={attendanceSummary}
                statistics={statistics}
                latestAchievements={latestAchievements}
                featuredExtracurriculars={featuredExtracurriculars}
                latestGallery={latestGallery}
            />

            {/* 5. Sambutan & Visi Kepala Madrasah */}
            <PrincipalVision settings={settings} />

            {/* 6. Call to Action (PPDB Online & Konsultasi) */}
            <CallToAction settings={settings} />
        </FrontendLayout>
    );
}
