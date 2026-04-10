import type { Config } from "tailwindcss";

const config: Config = {
  content: [
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
    "./lib/**/*.{js,ts,jsx,tsx,mdx}"
  ],
  theme: {
    extend: {
      colors: {
        background: "#f8f8f4",
        surface: "#ffffff",
        "surface-soft": "#f1f4ee",
        "surface-strong": "#e5eadf",
        ink: "#172018",
        muted: "#5b6658",
        line: "#cad4c4",
        primary: "#196b2c",
        "primary-strong": "#114b1e",
        "primary-soft": "#dff2df",
        accent: "#9c5421",
        "accent-soft": "#ffebde",
        success: "#0d8d4d",
        danger: "#c14332",
        warning: "#d99026"
      },
      fontFamily: {
        sans: ["Inter", "sans-serif"],
        heading: ["Manrope", "sans-serif"]
      },
      boxShadow: {
        soft: "0 22px 48px -28px rgba(23, 32, 24, 0.22)",
        panel: "0 12px 30px -18px rgba(25, 107, 44, 0.18)"
      },
      backgroundImage: {
        grain:
          "radial-gradient(circle at top right, rgba(25,107,44,0.08), transparent 30%), radial-gradient(circle at bottom left, rgba(156,84,33,0.06), transparent 28%)"
      }
    }
  },
  plugins: []
};

export default config;
