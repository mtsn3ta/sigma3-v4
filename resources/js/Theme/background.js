// resources/js/Theme/background.js

export const background = {
    // Gradasi gelap yang sangat dalam dengan nuansa "Midnight"
    hero: `
    relative
    overflow-hidden

    bg-gradient-to-br
    from-emerald-50
    via-white
    to-cyan-50
`,
aurora: `
    absolute
    inset-0
    overflow-hidden
    pointer-events-none
`,
    // Aura atau Glow yang lebih halus (tidak lagi membulat sempurna, tapi organik)
    glowTop: `
    absolute
    -top-40
    left-1/2
    -translate-x-1/2

    h-[900px]
    w-[900px]

    rounded-full

    bg-emerald-400/30

    blur-[180px]
`,
glowLeft: `
    absolute

    left-[-200px]
    top-40

    h-[700px]
    w-[700px]

    rounded-full

    bg-cyan-400/25

    blur-[180px]
`,
    glowRight: `
    absolute

    right-[-220px]
    bottom-[-180px]

    h-[700px]
    w-[700px]

    rounded-full

    bg-sky-400/20

    blur-[180px]
`,
    // Memberikan tekstur "kertas" digital yang sangat halus
    noise: "absolute inset-0 opacity-[0.03] pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]",

    // Grid yang halus untuk kesan "Tech/Future"
    grid: `
    absolute
    inset-0

    bg-[linear-gradient(rgba(15,23,42,.03)_1px,transparent_1px),linear-gradient(90deg,rgba(15,23,42,.03)_1px,transparent_1px)]

    bg-[size:48px_48px]

    [mask-image:radial-gradient(circle_at_center,#000,transparent)]
`,
};
