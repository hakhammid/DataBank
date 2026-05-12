import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                error: {
                    500: '#ef4444',
                },
                primary: '#1b232c',
                secondary: '#f0f0f0',
                danger: '#FF2929',
                gunmetal: "#1b232c",
                "flash-white": "#f4f5f8",
                "slate-grey": "#808997",
                "light-white": "#FAFCFF",
                "medium-blue": "#3026B9",
                "davy-grey": "#4A4E55",
                "persian-rose": "#FC2EB0",
                brown: "#90722C",
                "light-brown": "#FCF2D9",
                green: "#2E6638",
                "light-green": "#DCEEDE",
                blue: "#101749",
                "light-blue": "#EAE8F7",
                purple: "#7A4C7A",
                "light-purple": "#FCE9FC",
                "pine-green": "#19746A",
                "light-pine-green": "#E7FBF9",
                "pigment-green": "#4DAA5D",
            },
            fontFamily: {
                lato: ["Lato", "sans-serif"],
                lilita: ["Lilita One", "cursive"],
            },
            screens: {
                xs: "420px",
                sm: "576px",
                md: "768px",
                lg: "992px",
                xl: "1200px",
                xxl: "1400px",
            },
            boxShadow: {
                "tab-item": "rgba(0, 0, 0, 0.1) 0px 3px 5px",
                "tab-item-hover": "rgba(0, 0, 0, 0.2) 0px 3px 8px",
                nav: "rgba(100, 100, 111, 0.2) 0px 7px 29px 0px",
            },
        },
    },

    plugins: [forms],
};
