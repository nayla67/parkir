import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  css: ["~/assets/css/main.css"],

  vite: {
    plugins: [tailwindcss()],
    
  },

  runtimeConfig: {
    public: {
      apiBase: "http://localhost:8000/api",
    },
  },
});