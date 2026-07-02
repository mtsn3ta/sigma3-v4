import { useState } from "react";

export default function Avatar({ name = "", photo = null, className = "" }) {
    const [error, setError] = useState(false);

    const initials = name
        .trim()
        .split(/\s+/)
        .slice(0, 2)
        .map((word) => word[0])
        .join("")
        .toUpperCase();

    if (!photo || error) {
        return (
            <div
                className={`
                    ${className}
                    flex
                    items-center
                    justify-center
                    rounded-2xl
                    bg-emerald-600
                    font-black
                    text-white
                    select-none
                `}
            >
                {initials}
            </div>
        );
    }

    return (
        <img
            src={photo}
            alt={name}
            onError={() => setError(true)}
            className={`${className} object-cover`}
        />
    );
}
