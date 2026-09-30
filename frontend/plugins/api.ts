import axios from "axios";
import { defineNuxtPlugin, useRuntimeConfig, navigateTo } from "#app";

export default defineNuxtPlugin((nuxtApp) => {
  const runtimeConfig = useRuntimeConfig();

  const api = axios.create({
    baseURL: runtimeConfig.public.apiBase,
    headers: {
      Accept: "application/json",
    },
  });

  api.interceptors.request.use((config) => {
    const token =
      localStorage.getItem("token") || localStorage.getItem("auth_token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  });

  api.interceptors.response.use(
    (response) => response,
    (error) => {
      if (
        error.response?.status === 401 &&
        (localStorage.getItem("token") || localStorage.getItem("auth_token"))
      ) {
        localStorage.removeItem("token");
        localStorage.removeItem("auth_token");
        localStorage.removeItem("auth_user");
        alert("Sesi Anda telah berakhir, silakan login kembali.");
        navigateTo("/login");
      }
      return Promise.reject(error);
    }
  );

  return {
    provide: {
      api,
    },
  };
});
