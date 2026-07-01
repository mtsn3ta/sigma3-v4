export const motion = {
    fast: {
        type: "spring",
        stiffness: 500,
        damping: 30,
        duration: 0.25,
    },

    normal: {
        type: "spring",
        stiffness: 400,
        damping: 35,
        duration: 0.4,
    },

    slow: {
        type: "spring",
        stiffness: 300,
        damping: 40,
        duration: 0.6,
    },

    easing: "cubic-bezier(0.32, 0.72, 0, 1)",
};

export const css = {
    // Menambahkan ease kustom agar transisi CSS murni memiliki nuansa Apple
    transition: "transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]",
};
