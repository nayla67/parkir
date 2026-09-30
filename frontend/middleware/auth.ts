export default defineNuxtRouteMiddleware((to) => {
  const publicPages = ["/login"];

  // localStorage hanya tersedia di browser
  if (import.meta.server) {
    return;
  }

  const token = localStorage.getItem("auth_token");

  if (publicPages.includes(to.path)) {
    if (token) {
      const user = JSON.parse(
        localStorage.getItem("auth_user") || "{}"
      );

      if (user.role === "super_admin") {
        return navigateTo("/admin/dashboard");
      }

      return navigateTo("/admin/dashboard");
    }

    return;
  }

  // Halaman selain public membutuhkan token
  if (!token) {
    return navigateTo("/login");
  }

  // Cek user jika diperlukan
  const user = JSON.parse(
    localStorage.getItem("auth_user") || "{}"
  );

  if (to.path.startsWith("/admin") && user.role !== "admin") {
    return navigateTo("/admin/dashboard");
  }
});
