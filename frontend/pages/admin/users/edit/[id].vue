<template>
  <div>
    <div class="mb-8">
      <NuxtLink to="/admin/users" class="mb-3 inline-block text-sm font-semibold text-sky-600 transition hover:text-sky-700">← Kembali ke Daftar Petugas</NuxtLink>
      <h1 class="text-2xl font-bold text-slate-800 sm:text-3xl">Edit Petugas</h1>
      <p class="mt-1.5 text-sm text-slate-500">Perbarui data petugas</p>
    </div>

    <div v-if="loadingData" class="rounded-2xl border border-slate-100 bg-white py-16 text-center text-sm text-slate-400 shadow-sm">
      Memuat data...
    </div>

    <div v-else class="mx-auto max-w-lg rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
      <p v-if="error" class="mb-4 rounded-lg border border-pink-200 bg-pink-50 px-3 py-2 text-sm text-pink-700">
        {{ error }}
      </p>

      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-400"
        />
      </div>

      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-400"
        />
      </div>

      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-slate-700">No. HP</label>
        <input
          v-model="form.no_hp"
          type="text"
          placeholder="08xxxxxxxxxx"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-400"
        />
      </div>

      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Password Baru <span class="font-normal text-slate-400">(kosongkan jika tidak ingin ubah)</span></label>
        <input
          v-model="form.password"
          type="password"
          placeholder="Minimal 6 karakter"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-400"
        />
      </div>

      <div class="mb-6">
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          placeholder="Ulangi password baru"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-400"
          @keyup.enter="submit"
        />
      </div>

      <button
        @click="submit"
        :disabled="loading"
        class="w-full rounded-xl bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-200 transition hover:brightness-110 disabled:opacity-60"
      >
        {{ loading ? 'Menyimpan...' : 'Simpan Perubahan' }}
      </button>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin',
});

const { $api } = useNuxtApp();
const router = useRouter();
const route = useRoute();

const userId = route.params.id;

const form = ref({
  name: '',
  email: '',
  no_hp: '',
  password: '',
  password_confirmation: '',
});

const loadingData = ref(true);
const loading = ref(false);
const error = ref(null);

async function loadUser() {
  loadingData.value = true;
  try {
    const res = await $api.get(`/users/${userId}`);
    const user = res.data.data;
    form.value.name = user.name;
    form.value.email = user.email;
    form.value.no_hp = user.no_hp;
  } catch (e) {
    alert('Gagal memuat data petugas');
    router.push('/admin/users');
  } finally {
    loadingData.value = false;
  }
}

async function submit() {
  error.value = null;
  loading.value = true;

  const payload = {
    name: form.value.name,
    email: form.value.email,
    no_hp: form.value.no_hp,
  };

  if (form.value.password) {
    payload.password = form.value.password;
    payload.password_confirmation = form.value.password_confirmation;
  }

  try {
    await $api.put(`/users/${userId}`, payload);
    router.push('/admin/users');
  } catch (e) {
    if (e.response?.data?.errors) {
      const msgs = Object.values(e.response.data.errors).flat();
      error.value = msgs.join(', ');
    } else {
      error.value = e.response?.data?.message || 'Gagal memperbarui petugas';
    }
  } finally {
    loading.value = false;
  }
}

onMounted(loadUser);
</script>