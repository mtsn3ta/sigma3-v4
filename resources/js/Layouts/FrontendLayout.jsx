import { useState } from "react";
import { usePage } from "@inertiajs/react";

import Navbar from "../Components/Layout/Navbar";
import Footer from "../Components/Layout/Footer";
import UrgentAlert from "../Components/Layout/UrgentAlert";
import WelcomePopup from "../Components/Layout/WelcomePopup";

export default function FrontendLayout({ children }) {
    const { urgentAnnouncements = [] } = usePage().props;

    const [showUrgentAlert, setShowUrgentAlert] = useState(
        urgentAnnouncements.length > 0,
    );

    return (
        <div className="min-h-screen bg-slate-50 text-slate-900">
            <WelcomePopup />

            <UrgentAlert
                visible={showUrgentAlert}
                onClose={() => setShowUrgentAlert(false)}
            />

            <Navbar
                hasUrgentAlert={
                    showUrgentAlert && urgentAnnouncements.length > 0
                }
            />

            <main>{children}</main>

            <Footer />
        </div>
    );
}
