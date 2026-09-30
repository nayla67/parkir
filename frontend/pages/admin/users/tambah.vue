<template>
  <div>
    <div class="mb-8">
      <NuxtLink to="/admin/users" class="mb-3 inline-block text-sm font-semibold text-sky-600 transition hover:text-sky-700">← Kembali ke Daftar Petugas</NuxtLink>
      <h1 class="text-2xl font-bold text-slate-800 sm:text-3xl">Tambah Petugas</h1>
      <p class="mt-1.5 text-sm text-slate-500">Buat akun baru untuk petugas</p>
    </div>

    <div class="mx-auto max-w-lg rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
      <p v-if="error" class="mb-4 rounded-lg border border-pink-200 bg-pink-50 px-3 py-2 text-sm text-pink-700">
        {{ error }}
      </p>

      <div class="mb-4">

        
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap</label>
        <input
          v-model="form.name"
          type="text"
          placeholder="Masukkan nama petugas"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-400"
        />
      </div>

      <div class="mb-4">
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
        <input
          v-model="form.email"
          type="email"
          placeholder="petugas@parkir.com"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-400"
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
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
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
          placeholder="Ulangi password"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-sky-400"
          @keyup.enter="submit"
        />
      </div>

      <button
        @click="submit"
        :disabled="loading"
        class="w-full rounded-xl bg-gradient-to-r from-sky-400 via-purple-500 to-pink-500 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-200 transition hover:brightness-110 disabled:opacity-60"
      >
        {{ loading ? 'Menyimpan...' : 'Simpan Petugas' }}
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

const form = ref({
  name: '',
  email: '',
  no_hp: '',
  password: '',
  password_confirmation: '',
});

const loading = ref(false);
const error = ref(null);

async function submit() {
  error.value = null;
  loading.value = true;

  try {
    await $api.post('/users', form.value);
    router.push('/admin/users');
  } catch (e) {
    if (e.response?.data?.errors) {
      const msgs = Object.values(e.response.data.errors).flat();
      error.value = msgs.join(', ');
    } else {
      error.value = e.response?.data?.message || 'Gagal menyimpan petugas';
    }
  } finally {
    loading.value = false;
  }
}
</script>