<template>
  <div class="page-container">

    <div class="card-box">

      <!-- QR CODE -->
      <div class="qr-section">
        <span class="qr-label">QR Member</span>

        <div class="qr-wrapper">
          <img
            :src="qrSrc"
            :alt="`QR Member ${member.kode}`"
            class="qr-img"
          />
        </div>

        <p v-if="loading" class="qr-hint">
          Memuat data member...
        </p>

        <p v-else-if="error" class="qr-hint error-text">
          {{ error }}
        </p>

        <p v-else class="qr-hint">
          Scan untuk verifikasi member {{ member.kode || '' }}
        </p>
      </div>

      <!-- INFORMASI MEMBER -->
      <div class="info-section">

        <h2 class="member-name">
          {{ member.nama || 'Nama Member' }}
        </h2>

        <p class="member-company">
          {{ member.perusahaan || 'Perusahaan / Instansi' }}
        </p>

        <div class="divider"></div>

        <div class="info-grid">

          <div class="info-row">
            <span>Kode Member</span>
            <strong>{{ member.kode || '-' }}</strong>
          </div>

          <div class="info-row">
            <span>Harga</span>
            <strong>
              Rp
              {{
                member.harga
                  ? Number(member.harga).toLocaleString('id-ID')
                  : '0'
              }}
            </strong>
          </div>

          <div class="info-row">
            <span>Status</span>

            <span
              :class="['status-badge', member.status]"
            >
              {{ member.status === 'lunas' ? 'LUNAS' : 'BELUM' }}
            </span>
          </div>

          <div class="info-row">
            <span>Berlaku sampai</span>

            <strong>
              {{ member.expired_at || '-' }}
            </strong>
          </div>

        </div>
      </div>

      <!-- TOMBOL AKSI -->
      <div class="action-buttons">

        <!-- DOWNLOAD -->
        <button
          @click="downloadCard"
          class="btn-download"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
            <polyline points="7 10 12 15 17 10" />
            <line x1="12" y1="15" x2="12" y2="3" />
          </svg>

          Download Kartu
        </button>

        <!-- KEMBALI KE HALAMAN SEBELUMNYA -->
        <button
          type="button"
          @click="goBack"
          class="btn-close"
        >
          ← Kembali
        </button>

      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

definePageMeta({
  layout: 'false',
})

const route = useRoute()
const router = useRouter()
const { $api } = useNuxtApp()

// ======================================================
// ID MEMBER
// URL contoh:
// /petugas/member/kartu/1
// /admin/member/kartu/1
// ======================================================

const memberId = route.params.id

// ======================================================
// DATA MEMBER
// ======================================================

const member = ref({
  kode: '',
  nama: '',
  perusahaan: '',
  harga: 0,
  status: 'belum',
  expired_at: '',
  qr_code: ''
})

const loading = ref(false)
const error = ref('')

// ======================================================
// QR CODE
// ======================================================

const qrValue = computed(() => {
  const kode = member.value.kode || ''
  const qr = member.value.qr_code || kode || 'MBR'

  return encodeURIComponent(qr)
})

const qrSrc = computed(() => {
  return `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${qrValue.value}`
})

// ======================================================
// FETCH MEMBER
// ======================================================

const fetchMember = async () => {

  if (!memberId) {
    error.value = 'ID member tidak ditemukan.'
    return
  }

  loading.value = true
  error.value = ''

  try {

    const response = await $api.get(`/members/${memberId}`)

    const data = response.data

    member.value = {
      kode: data?.kode ?? '',
      nama: data?.nama ?? '',
      perusahaan: data?.perusahaan ?? '',
      harga: data?.harga ?? 0,
      status: data?.status ?? 'belum',
      expired_at: data?.expired_at ?? '',
      qr_code: data?.qr_code ?? ''
    }

  } catch (e) {

    console.error(
      'Gagal mengambil data member:',
      e
    )

    error.value =
      e?.response?.data?.message ||
      'Data member tidak ditemukan.'

  } finally {

    loading.value = false

  }
}

// ======================================================
// KEMBALI KE HALAMAN SEBELUMNYA
// ======================================================

const goBack = () => {

  // Kalau ada history browser/router,
  // kembali ke halaman sebelumnya.
  if (window.history.length > 1) {
    router.back()
    return
  }

  // Fallback kalau tidak ada history.
  router.push('/admin/member')
}

// ======================================================
// DOWNLOAD / PRINT KARTU
// ======================================================

const downloadCard = () => {
  window.print()
}

// ======================================================
// MOUNT
// ======================================================

onMounted(() => {
  fetchMember()
})
</script>

<style scoped>
.page-container {
  min-height: 100vh;
  background: #f8fafc;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px 20px;
  font-family: 'Inter', system-ui, sans-serif;
}

.card-box {
  background: #ffffff;
  width: 100%;
  max-width: 420px;
  border-radius: 16px;
  padding: 28px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.qr-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 20px;
  width: 100%;
}

.qr-label {
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: #64748b;
  margin-bottom: 10px;
}

.qr-wrapper {
  background: #f8fafc;
  padding: 12px;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}

.qr-img {
  width: 130px;
  height: 130px;
  object-fit: contain;
}

.qr-hint {
  font-size: 11px;
  color: #94a3b8;
  margin-top: 8px;
}

.error-text {
  color: #ef4444;
}

.info-section {
  width: 100%;
  text-align: left;
}

.member-name {
  font-size: 20px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 2px 0;
  text-align: center;
}

.member-company {
  font-size: 13px;
  color: #64748b;
  margin: 0 0 16px 0;
  text-align: center;
}

.divider {
  width: 100%;
  height: 1px;
  background: #f1f5f9;
  margin-bottom: 16px;
}

.info-grid {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
}

.info-row span {
  color: #64748b;
}

.info-row strong {
  color: #0f172a;
}

.status-badge {
  font-size: 10px;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 6px;
}

.status-badge.belum {
  background: #fee2e2;
  color: #dc2626;
}

.status-badge.lunas {
  background: #dcfce7;
  color: #16a34a;
}

.action-buttons {
  display: flex;
  gap: 10px;
  width: 100%;
  margin-top: 24px;
  padding-top: 16px;
  border-top: 1px solid #f1f5f9;
}

.btn-download {
  flex: 1;
  background: #2563eb;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: background 0.2s;
}

.btn-download:hover {
  background: #1d4ed8;
}

.btn-close {
  flex: 1;
  background: #f1f5f9;
  color: #475569;
  border: none;
  text-decoration: none;
  padding: 10px 16px;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}

.btn-close:hover {
  background: #e2e8f0;
}

/* ======================================================
   PRINT
   ====================================================== */

@media print {

  .page-container {
    min-height: auto;
    padding: 0;
    background: white;
  }

  .card-box {
    box-shadow: none;
    border: none;
    max-width: 420px;
  }

  .action-buttons {
    display: none;
  }

}
</style>