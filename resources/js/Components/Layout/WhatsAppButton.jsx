import React from "react";
import { Bot, MessageCircle } from "lucide-react"; // Pastikan sudah install lucide-react

export default function WhatsAppButton() {
    return (
        <a
            href="https://wa.me/62881081297786?text=Assalamualaikum%20Warahmatullahi%20Wabarakatuh"
            target="_blank"
            rel="noopener noreferrer"
            className="sigma-ai-float"
            title="SIGMA-3 CS AI"
        >
            <div className="sigma-ai-badge">
                <div className="sigma-ai-dot"></div>
                ONLINE 24 JAM
            </div>
            <div className="sigma-ai-box">
                <div className="sigma-ai-icon">
                    <Bot size={28} />
                </div>
                <div className="sigma-ai-text">
                    {/* Judul lebih lugas */}
                    <div className="sigma-ai-title text-slate-800 text-sm font-black uppercase tracking-wide">
                        SIGMA-3 CS AI
                    </div>
                    {/* Deskripsi dengan kontras warna yang lebih baik */}
                    <div className="sigma-ai-desc text-slate-600 text-[11px] leading-snug mt-1">
                        <span className="font-bold text-emerald-700 block mb-0.5">
                            Ajukan Izin & Cek Data Siswa
                        </span>
                        Pantau nilai, BK, & prestasi secara realtime.
                    </div>
                </div>
                <div className="sigma-ai-wa">
                    <MessageCircle size={22} />
                </div>
            </div>
        </a>
    );
}
