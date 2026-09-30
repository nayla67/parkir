<template>
  <div
    class="min-h-screen p-6"
    style="
      background: linear-gradient(
        135deg,
        #fff7fc 0%,
        #faf5ff 45%,
        #f5f7ff 100%
      );
    "
  >
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1
          class="text-3xl font-extrabold"
          style="color: #7e00d8;"
        >
          🚙 Kendaraan Keluar
        </h1>

        <p
          class="text-lg mt-1"
          style="color: #c084fc;"
        >
          Scan QR Code tiket atau kartu member yaa~
        </p>
      </div>

      <NuxtLink
        to="/petugas"
        class="
          px-7 py-3
          rounded-full
          font-semibold
          shadow-md
          transition
          hover:scale-105
        "
        style="
          background: #e9d5ff;
          color: #7e00d8;
        "
      >
        ← Kembali
      </NuxtLink>
    </div>

    <div class="max-w-2xl mx-auto">

      <!-- ========================= -->
      <!-- INPUT SCANNER -->
      <!-- ========================= -->
      <div
        v-if="!member && !tiket && !sukses && !memproses"
        class="
          bg-white
          rounded-[30px]
          p-8
          shadow-lg
        "
        style="
          border: 1px solid #f0d9ff;
          box-shadow: 0 10px 30px rgba(168, 85, 247, 0.10);
        "
      >
        <label
          class="block text-base font-bold mb-3"
          style="color: #a855f7;"
        >
          🎫 Kode Tiket / Kartu Member
        </label>

        <input
          ref="inputRef"
          v-model="qrCodeInput"
          type="text"
          autofocus
          autocomplete="off"
          placeholder="Scan QR atau ketik kode lalu tekan Enter"
          class="
            w-full
            px-5
            py-4
            rounded-2xl
            text-base
            outline-none
            transition
          "
          style="
            border: 2px solid #f472b6;
            color: #7e00d8;
            background: #fffaff;
          "
          @keyup.enter="handleSubmit"
        />

        <button
          @click="handleSubmit"
          class="
            mt-4
            w-full
            py-4
            rounded-2xl
            text-white
            font-bold
            text-lg
            shadow-md
            transition
            hover:scale-[1.01]
          "
          style="
            background: linear-gradient(
              90deg,
              #ec4899 0%,
              #c084fc 100%
            );
            box-shadow: 0 8px 18px rgba(236, 72, 153, 0.25);
          "
        >
          ✨ Proses
        </button>
      </div>

      <!-- ========================= -->
      <!-- MESSAGE -->
      <!-- ========================= -->
      <div
        v-if="message"
        class="
          mt-5
          p-4
          rounded-2xl
          text-center
          font-semibold
        "
        :style="
          success
            ? `
              background: #dcfce7;
              color: #15803d;
            `
            : `
              background: #fce7f3;
              color: #be185d;
            `
        "
      >
        {{ message }}
      </div>

      <!-- ========================= -->
      <!-- LOADING -->
      <!-- ========================= -->
      <div
        v-if="memproses"
        class="
          mt-5
          bg-white
          rounded-[30px]
          p-8
          text-center
          shadow-lg
        "
        style="
          border: 1px solid #f0d9ff;
        "
      >
        <div
          class="
            text-xl
            font-bold
          "
          style="color: #a855f7;"
        >
          ✨ Memproses...
        </div>

        <p
          class="mt-2"
          style="color: #c084fc;"
        >
          Mohon tunggu sebentar~
        </p>
      </div>

      <!-- ========================= -->
      <!-- MEMBER -->
      <!-- ========================= -->
      <div
        v-if="member && !sukses && !memproses"
        class="
          mt-5
          bg-white
          rounded-[30px]
          p-7
          shadow-lg
        "
        style="
          border: 1px solid #f0d9ff;
          box-shadow: 0 10px 30px rgba(168, 85, 247, 0.10);
        "
      >
        <!-- TITLE -->
        <div class="flex items-center justify-between mb-6">
          <h2
            class="text-xl font-extrabold"
            style="color: #7e00d8;"
          >
            Member Terdaftar
          </h2>

          <span
            class="
              text-xs
              font-bold
              px-4
              py-2
              rounded-full
            "
            style="
              background: #ede9fe;
              color: #7c3aed;
            "
          >
            Member
          </span>
        </div>

        <!-- DETAIL MEMBER -->
        <div
          class="
            rounded-2xl
            p-5
            space-y-3
          "
          style="
            background: #fff7fc;
            border: 1px solid #fce7f3;
          "
        >
          <p class="flex justify-between">
            <span style="color: #9ca3af;">
              Kode Member
            </span>

            <span
              class="font-semibold"
              style="color: #7e00d8;"
            >
              {{ member.kode }}
            </span>
          </p>

          <p class="flex justify-between">
            <span style="color: #9ca3af;">
              Nama
            </span>

            <span
              class="font-semibold"
              style="color: #374151;"
            >
              {{ member.nama }}
            </span>
          </p>

          <p
            v-if="member.perusahaan"
            class="flex justify-between"
          >
            <span style="color: #9ca3af;">
              Perusahaan
            </span>

            <span
              class="font-semibold"
              style="color: #374151;"
            >
              {{ member.perusahaan }}
            </span>
          </p>

          <div
            class="
              pt-3
              mt-2
            "
            style="
              border-top: 1px solid #f3d8ef;
            "
          >
            <p class="flex justify-between">
              <span style="color: #9ca3af;">
                Waktu Masuk
              </span>

              <span
                class="font-semibold"
                style="color: #374151;"
              >
                {{ formatTanggal(member.waktu_masuk) }}
              </span>
            </p>
          </div>
        </div>

        <!-- JENIS KENDARAAN -->
        <div class="mt-6">
          <label
            class="
              block
              text-sm
              font-semibold
              mb-2
            "
            style="color: #6b7280;"
          >
            Jenis Kendaraan
          </label>

          <div class="grid grid-cols-2 gap-3">
            <button
              type="button"
              @click="jenisKendaraan = 'motor'"
              class="
                py-3
                rounded-xl
                font-bold
                border-2
                transition
              "
              :style="
                jenisKendaraan === 'motor'
                  ? `
                    background: linear-gradient(90deg, #ec4899 0%, #c084fc 100%);
                    color: #fff;
                    border-color: transparent;
                  `
                  : `
                    background: #fffaff;
                    color: #a855f7;
                    border-color: #e9d5ff;
                  `
              "
            >
              🏍️ Motor
            </button>

            <button
              type="button"
              @click="jenisKendaraan = 'mobil'"
              class="
                py-3
                rounded-xl
                font-bold
                border-2
                transition
              "
              :style="
                jenisKendaraan === 'mobil'
                  ? `
                    background: linear-gradient(90deg, #ec4899 0%, #c084fc 100%);
                    color: #fff;
                    border-color: transparent;
                  `
                  : `
                    background: #fffaff;
                    color: #a855f7;
                    border-color: #e9d5ff;
                  `
              "
            >
              🚗 Mobil
            </button>
          </div>
        </div>

        <!-- FORM PLAT NOMOR -->
        <div class="mt-6">
          <label
            class="
              block
              text-sm
              font-semibold
              mb-2
            "
            style="color: #6b7280;"
          >
            Nomor Polisi
          </label>

          <input
            v-model="platNomor"
            type="text"
            maxlength="20"
            placeholder="Contoh: B 1234 CD"
            class="
              w-full
              px-4
              py-3
              rounded-xl
              outline-none
              uppercase
            "
            style="
              border: 1px solid #d8b4fe;
              color: #374151;
            "
            @keyup.enter="prosesKeluarMember"
          />
        </div>

        <!-- ERROR -->
        <div
          v-if="paymentError"
          class="
            mt-5
            p-3
            rounded-xl
            text-center
            font-semibold
          "
          style="
            background: #fce7f3;
            color: #be185d;
          "
        >
          {{ paymentError }}
        </div>

        <!-- BUTTON -->
        <div class="mt-6 grid grid-cols-2 gap-4">

          <button
            @click="batal"
            class="
              w-full
              py-3.5
              rounded-2xl
              text-white
              font-bold
              shadow-md
              transition
              hover:scale-[1.01]
            "
            style="
              background: #a855f7;
            "
          >
            BATAL
          </button>

          <button
            :disabled="!platNomor.trim() || !jenisKendaraan"
            @click="prosesKeluarMember"
            class="
              w-full
              py-3.5
              rounded-2xl
              text-white
              font-bold
              shadow-md
              transition
              hover:scale-[1.01]
              disabled:opacity-50
              disabled:cursor-not-allowed
              disabled:hover:scale-100
            "
            style="
              background: linear-gradient(
                90deg,
                #ec4899 0%,
                #c084fc 100%
              );
            "
          >
            KENDARAAN KELUAR
          </button>

        </div>

        <p
          class="mt-4 text-center text-xs"
          style="color: #c4b5fd;"
        >
          Member tidak dikenakan biaya parkir.
        </p>
      </div>

      <!-- ========================= -->
      <!-- PEMBAYARAN NON MEMBER (TIKET) -->
      <!-- ========================= -->
      <div
        v-if="tiket && !sukses && !memproses"
        class="
          mt-5
          bg-white
          rounded-[30px]
          p-7
          shadow-lg
        "
        style="
          border: 1px solid #f0d9ff;
          box-shadow: 0 10px 30px rgba(168, 85, 247, 0.10);
        "
      >
        <!-- TITLE -->
        <div class="flex items-center justify-between mb-6">
          <h2
            class="text-xl font-extrabold"
            style="color: #7e00d8;"
          >
            Pembayaran Parkir
          </h2>

          <span
            class="
              text-xs
              font-bold
              px-4
              py-2
              rounded-full
            "
            style="
              background: #f3e8ff;
              color: #9333ea;
            "
          >
            Non Member
          </span>
        </div>

        <!-- DETAIL TIKET -->
        <div
          class="
            rounded-2xl
            p-5
            space-y-3
          "
          style="
            background: #fff7fc;
            border: 1px solid #fce7f3;
          "
        >
          <p class="flex justify-between">
            <span style="color: #9ca3af;">
              Nomor Tiket
            </span>

            <span
              class="font-semibold"
              style="color: #7e00d8;"
            >
              {{ tiket.kode_tiket }}
            </span>
          </p>

          <p class="flex justify-between">
            <span style="color: #9ca3af;">
              Waktu Masuk
            </span>

            <span
              class="font-semibold"
              style="color: #374151;"
            >
              {{ formatTanggal(tiket.waktu_masuk) }}
            </span>
          </p>

          <p class="flex justify-between">
            <span style="color: #9ca3af;">
              Durasi Parkir
            </span>

            <span
              class="font-semibold"
              style="color: #374151;"
            >
              {{ formatDurasi(tiket.durasi_menit) }}
            </span>
          </p>

          <p class="flex justify-between">
            <span style="color: #9ca3af;">
              Tarif Per Jam
            </span>

            <span
              class="font-semibold"
              style="color: #374151;"
            >
              {{ formatRupiah(tiket.tarif_per_jam) }} / jam
            </span>
          </p>

          <div
            class="
              pt-3
              mt-2
            "
            style="
              border-top: 1px solid #f3d8ef;
            "
          >
            <p class="flex justify-between">
              <span
                class="font-semibold"
                style="color: #6b7280;"
              >
                Total Bayar
              </span>

              <span
                class="font-extrabold text-lg"
                style="color: #7e00d8;"
              >
                {{ formatRupiah(tiket.total_bayar) }}
              </span>
            </p>
          </div>
        </div>

        <!-- JENIS KENDARAAN -->
        <div class="mt-6">
          <label
            class="
              block
              text-sm
              font-semibold
              mb-2
            "
            style="color: #6b7280;"
          >
            Jenis Kendaraan
          </label>

          <div class="grid grid-cols-2 gap-3">
            <button
              type="button"
              @click="jenisKendaraan = 'motor'"
              class="
                py-3
                rounded-xl
                font-bold
                border-2
                transition
              "
              :style="
                jenisKendaraan === 'motor'
                  ? `
                    background: linear-gradient(90deg, #ec4899 0%, #c084fc 100%);
                    color: #fff;
                    border-color: transparent;
                  `
                  : `
                    background: #fffaff;
                    color: #a855f7;
                    border-color: #e9d5ff;
                  `
              "
            >
              🏍️ Motor
            </button>

            <button
              type="button"
              @click="jenisKendaraan = 'mobil'"
              class="
                py-3
                rounded-xl
                font-bold
                border-2
                transition
              "
              :style="
                jenisKendaraan === 'mobil'
                  ? `
                    background: linear-gradient(90deg, #ec4899 0%, #c084fc 100%);
                    color: #fff;
                    border-color: transparent;
                  `
                  : `
                    background: #fffaff;
                    color: #a855f7;
                    border-color: #e9d5ff;
                  `
              "
            >
              🚗 Mobil
            </button>
          </div>
        </div>

        <!-- FORM PLAT NOMOR -->
        <div class="mt-6">
          <label
            class="
              block
              text-sm
              font-semibold
              mb-2
            "
            style="color: #6b7280;"
          >
            Nomor Polisi
          </label>

          <input
            v-model="platNomor"
            type="text"
            maxlength="20"
            placeholder="Contoh: B 1234 CD"
            class="
              w-full
              px-4
              py-3
              rounded-xl
              outline-none
              uppercase
            "
            style="
              border: 1px solid #d8b4fe;
              color: #374151;
            "
            @keyup.enter="bayarTiket"
          />
        </div>

        <!-- UANG DIBAYAR -->
        <div class="mt-6">
          <label
            class="
              block
              text-sm
              font-semibold
              mb-2
            "
            style="color: #6b7280;"
          >
            Uang Dibayar
          </label>

          <input
            v-model.number="uangDibayar"
            type="number"
            min="0"
            placeholder="Masukkan nominal uang"
            class="
              w-full
              px-4
              py-3
              rounded-xl
              outline-none
            "
            style="
              border: 1px solid #d8b4fe;
              color: #374151;
            "
            @keyup.enter="bayarTiket"
          />

          <div class="mt-3 flex flex-wrap gap-2">
            <button
              type="button"
              @click="uangDibayar = tiket.total_bayar"
              class="
                px-3
                py-1.5
                rounded-full
                text-xs
                font-bold
                transition
                hover:scale-105
              "
              style="
                background: #ede9fe;
                color: #7c3aed;
              "
            >
              Uang Pas
            </button>

            <button
              v-for="nominal in pilihanUang"
              :key="nominal"
              type="button"
              @click="uangDibayar = nominal"
              class="
                px-3
                py-1.5
                rounded-full
                text-xs
                font-bold
                transition
                hover:scale-105
              "
              style="
                background: #fce7f3;
                color: #be185d;
              "
            >
              {{ formatRupiah(nominal) }}
            </button>
          </div>

          <!-- KEMBALIAN / KURANG -->
          <div
            v-if="uangDibayarNum > 0"
            class="
              mt-4
              rounded-xl
              p-4
              flex
              justify-between
              items-center
            "
            :style="
              uangCukup
                ? 'background: #dcfce7; color: #15803d;'
                : 'background: #fce7f3; color: #be185d;'
            "
          >
            <span class="font-semibold">
              {{ uangCukup ? 'Kembalian' : 'Uang Kurang' }}
            </span>

            <span class="font-extrabold text-lg">
              {{ formatRupiah(uangCukup ? kembalian : uangKurang) }}
            </span>
          </div>
        </div>

        <!-- ERROR -->
        <div
          v-if="paymentError"
          class="
            mt-5
            p-3
            rounded-xl
            text-center
            font-semibold
          "
          style="
            background: #fce7f3;
            color: #be185d;
          "
        >
          {{ paymentError }}
        </div>

        <!-- BUTTON -->
        <div class="mt-6 grid grid-cols-2 gap-4">

          <button
            @click="batal"
            class="
              w-full
              py-3.5
              rounded-2xl
              text-white
              font-bold
              shadow-md
              transition
              hover:scale-[1.01]
            "
            style="
              background: #a855f7;
            "
          >
            BATAL
          </button>

          <button
            :disabled="!platNomor.trim() || !jenisKendaraan || !uangCukup"
            @click="bayarTiket"
            class="
              w-full
              py-3.5
              rounded-2xl
              text-white
              font-bold
              shadow-md
              transition
              hover:scale-[1.01]
              disabled:opacity-50
              disabled:cursor-not-allowed
              disabled:hover:scale-100
            "
            style="
              background: linear-gradient(
                90deg,
                #ec4899 0%,
                #c084fc 100%
              );
            "
          >
            BAYAR
          </button>

        </div>
      </div>

      <!-- ========================= -->
      <!-- SUKSES -->
      <!-- ========================= -->
      <div
        v-if="sukses"
        class="
          mt-5
          bg-white
          rounded-[30px]
          p-8
          shadow-lg
        "
        style="
          border: 1px solid #f0d9ff;
          box-shadow: 0 10px 30px rgba(168, 85, 247, 0.10);
        "
      >
        <div class="flex flex-col items-center text-center">

          <!-- CHECK -->
          <div
            class="
              flex
              h-20
              w-20
              items-center
              justify-center
              rounded-full
            "
            style="
              background: #dcfce7;
            "
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-12 w-12"
              style="color: #16a34a;"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="3"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M5 13l4 4L19 7"
              />
            </svg>
          </div>

          <h2
            class="
              mt-5
              text-3xl
              font-extrabold
            "
            style="color: #16a34a;"
          >
            Pintu Terbuka
          </h2>

          <p
            class="mt-2"
            style="color: #9ca3af;"
          >
            Kendaraan berhasil diproses ✨
          </p>

          <!-- DETAIL -->
          <div
            class="
              mt-6
              w-full
              rounded-2xl
              p-5
              space-y-3
            "
            style="
              background: #fff7fc;
              border: 1px solid #fce7f3;
            "
          >
            <p
              v-if="sukses.kodeTiket"
              class="flex justify-between"
            >
              <span style="color: #9ca3af;">
                Nomor Tiket
              </span>

              <span
                class="font-semibold"
                style="color: #7e00d8;"
              >
                {{ sukses.kodeTiket }}
              </span>
            </p>

            <p
              v-if="sukses.member"
              class="flex justify-between"
            >
              <span style="color: #9ca3af;">
                Kode Member
              </span>

              <span
                class="font-semibold"
                style="color: #7e00d8;"
              >
                {{ sukses.kodeMember }}
              </span>
            </p>

            <p class="flex justify-between">
              <span style="color: #9ca3af;">
                Jenis Kendaraan
              </span>

              <span
                class="font-semibold"
                style="color: #374151;"
              >
                {{ sukses.jenisKendaraan === 'motor' ? '🏍️ Motor' : '🚗 Mobil' }}
              </span>
            </p>

            <p class="flex justify-between">
              <span style="color: #9ca3af;">
                Nomor Polisi
              </span>

              <span
                class="font-semibold"
                style="color: #374151;"
              >
                {{ sukses.nomorPolisi || "-" }}
              </span>
            </p>

            <div
              v-if="!sukses.member"
              class="pt-3 mt-2 space-y-3"
              style="
                border-top: 1px solid #f3d8ef;
              "
            >
              <p class="flex justify-between">
                <span
                  class="font-semibold"
                  style="color: #6b7280;"
                >
                  Total Bayar
                </span>

                <span
                  class="font-extrabold"
                  style="color: #7e00d8;"
                >
                  {{ formatRupiah(sukses.totalBayar) }}
                </span>
              </p>

              <p class="flex justify-between">
                <span style="color: #9ca3af;">
                  Uang Dibayar
                </span>

                <span
                  class="font-semibold"
                  style="color: #374151;"
                >
                  {{ formatRupiah(sukses.uangDibayar) }}
                </span>
              </p>

              <p class="flex justify-between">
                <span
                  class="font-semibold"
                  style="color: #6b7280;"
                >
                  Kembalian
                </span>

                <span
                  class="font-extrabold"
                  style="color: #16a34a;"
                >
                  {{ formatRupiah(sukses.kembalian) }}
                </span>
              </p>
            </div>

            <p
              v-if="sukses.member"
              class="text-center text-xs mt-2"
              style="color: #16a34a;"
            >
              Gratis biaya parkir (Member)
            </p>
          </div>

          <!-- CETAK ULANG INVOICE -->
          <button
            @click="cetakUlangInvoice"
            class="
              w-full
              mt-4
              py-3
              rounded-2xl
              font-bold
              transition
              hover:scale-[1.01]
            "
            style="
              background: #ede9fe;
              color: #7c3aed;
            "
          >
            🖨️ Cetak Ulang Invoice
          </button>

          <!-- OK -->
          <button
            @click="batal"
            class="
              w-full
              mt-3
              py-3.5
              rounded-2xl
              text-white
              font-bold
              shadow-md
              transition
              hover:scale-[1.01]
            "
            style="
              background: linear-gradient(
                90deg,
                #ec4899 0%,
                #c084fc 100%
              );
            "
          >
            OK
          </button>

        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
interface MemberData {
  member_id: number
  kode: string
  nama: string
  perusahaan?: string
  status_parkir: string
  tiket_id: number
  waktu_masuk: string
}

interface TicketData {
  id: number
  kode_tiket: string
  qr_code: string
  status: string
  waktu_masuk: string
  durasi_menit: number
  durasi_jam: number
  tarif_per_jam: number
  total_bayar: number
}

interface ScanResponseBody {
  success: boolean
  type: 'member' | 'ticket'
  message: string
  data: MemberData | TicketData
}

interface SuksesData {
  kodeTiket: string
  kodeMember: string
  nomorPolisi: string
  jenisKendaraan: string
  member: boolean
  totalBayar: number
  uangDibayar: number
  kembalian: number
}

interface InvoicePayload {
  isMember: boolean;
  kode: string;
  nama?: string;
  perusahaan?: string;
  jenisKendaraan: string;
  platNomor: string;
  waktuMasuk: string;
  waktuKeluar: Date;
  totalBayar: number;
  tarifPerJamOverride?: number;
  uangDibayar?: number;
  kembalian?: number;
}

const { $api } = useNuxtApp();

/* =========================
   TARIF KENDARAAN
   (dipakai untuk invoice)
========================= */

const TARIF_KENDARAAN: Record<string, number> = {
  motor: 2000,
  mobil: 5000,
};

/* =========================
   STATE
========================= */

const message = ref("");
const success = ref(false);

const memproses = ref(false);

const qrCode = ref("");
const qrCodeInput = ref("");

const inputRef = ref<HTMLInputElement | null>(null);

const member = ref<MemberData | null>(null);
const tiket = ref<TicketData | null>(null);

const platNomor = ref("");
const jenisKendaraan = ref("");

const sukses = ref<SuksesData | null>(null);

const paymentError = ref("");

const lastInvoiceData = ref<InvoicePayload | null>(null);

/* =========================
   UANG DIBAYAR & KEMBALIAN
========================= */

const uangDibayar = ref<number | null>(null);

const uangDibayarNum = computed(() => Number(uangDibayar.value) || 0);

const uangCukup = computed(
  () => !!tiket.value && uangDibayarNum.value >= tiket.value.total_bayar
);

const uangKurang = computed(() =>
  tiket.value
    ? Math.max(0, tiket.value.total_bayar - uangDibayarNum.value)
    : 0
);

const kembalian = computed(() =>
  tiket.value
    ? Math.max(0, uangDibayarNum.value - tiket.value.total_bayar)
    : 0
);

const pilihanUang = [5000, 10000, 20000, 50000, 100000];

/* =========================
   PARSING TANGGAL
   ==========================
   Backend mengirim waktu dalam format ISO UTC
   (contoh: "2026-09-23T04:42:00.000000Z").
   new Date() otomatis mengonversinya ke waktu
   lokal browser dengan benar — TIDAK perlu
   dimanipulasi manual.
========================= */

const parseWaktuLokal = (raw: string): Date => {
  if (!raw) return new Date(NaN);

  // Kalau string sudah punya 'Z' atau offset (+07:00 dll), biarkan apa adanya.
  const sudahAdaZona = /Z$|[+-]\d{2}:\d{2}$/.test(raw);

  // Ganti spasi jadi 'T' biar format ISO valid, lalu kalau belum ada
  // penanda zona waktu, tambahkan 'Z' — karena backend menyimpan &
  // mengirim waktu dalam UTC tanpa penanda eksplisit.
  const isoString = raw.replace(" ", "T");

  return new Date(sudahAdaZona ? isoString : `${isoString}Z`);
};

/* =========================
   SUBMIT SCAN
========================= */

const handleSubmit = async () => {
  const kode = qrCodeInput.value.trim();

  if (!kode) {
    message.value = "Kode tiket / member wajib diisi.";
    success.value = false;
    return;
  }

  await prosesScan(kode);
};

/* =========================
   SCAN (MEMBER / TIKET)
========================= */

const prosesScan = async (decodedText: string) => {
  resetState();

  qrCode.value = decodedText.trim();

  if (!qrCode.value) {
    message.value = "Kode tidak boleh kosong.";
    return;
  }

  memproses.value = true;

  try {
    const response = await $api.post("/keluar/scan", {
      qr_code: qrCode.value,
    });

    const body: ScanResponseBody | undefined = response?.data;

    if (!body || !body.success) {
      throw new Error(
        body?.message || "QR tidak dapat diproses."
      );
    }

    if (body.type === "member") {
      member.value = body.data as MemberData;
    }

    if (body.type === "ticket") {
      tiket.value = body.data as TicketData;
    }

  } catch (error: any) {
    console.error("Scan gagal:", error);

    message.value =
      error?.response?.data?.message ||
      error?.data?.message ||
      error?.message ||
      "QR tidak dapat diproses.";

    success.value = false;

  } finally {
    memproses.value = false;
  }
};

/* =========================
   KENDARAAN KELUAR - MEMBER
========================= */

const prosesKeluarMember = async () => {
  paymentError.value = "";

  if (!member.value) {
    paymentError.value = "Data member tidak ditemukan.";
    return;
  }

  if (!jenisKendaraan.value) {
    paymentError.value = "Jenis kendaraan wajib dipilih.";
    return;
  }

  if (!platNomor.value.trim()) {
    paymentError.value = "Nomor polisi wajib diisi.";
    return;
  }

  if (memproses.value) {
    return;
  }

  memproses.value = true;

  try {
    const waktuKeluar = new Date();
    const platFinal = platNomor.value.trim().toUpperCase();

    const response = await $api.post("/keluar/member", {
      member_id: member.value.member_id,
      tiket_id: member.value.tiket_id,
      jenis_kendaraan: jenisKendaraan.value,
      plat_nomor: platFinal,
    });

    sukses.value = {
      kodeTiket: "",
      kodeMember: member.value.kode,
      nomorPolisi: platFinal,
      jenisKendaraan: jenisKendaraan.value,
      member: true,
      totalBayar: 0,
      uangDibayar: 0,
      kembalian: 0,
    };

    cetakInvoice({
      isMember: true,
      kode: member.value.kode,
      nama: member.value.nama,
      perusahaan: member.value.perusahaan,
      jenisKendaraan: jenisKendaraan.value,
      platNomor: platFinal,
      waktuMasuk: member.value.waktu_masuk,
      waktuKeluar,
      totalBayar: 0,
    });

    member.value = null;

    message.value =
      response?.data?.message ||
      "Member berhasil keluar. Pintu terbuka.";

    success.value = true;

  } catch (error: any) {
    console.error("Keluar member gagal:", error);

    paymentError.value =
      error?.response?.data?.message ||
      error?.data?.message ||
      error?.message ||
      "Gagal memproses kendaraan keluar.";

    success.value = false;

  } finally {
    memproses.value = false;
  }
};

/* =========================
   BAYAR TIKET NON MEMBER
========================= */

const bayarTiket = async () => {
  paymentError.value = "";

  if (!tiket.value) {
    paymentError.value = "Data tiket tidak ditemukan.";
    return;
  }

  if (!jenisKendaraan.value) {
    paymentError.value = "Jenis kendaraan wajib dipilih.";
    return;
  }

  if (!platNomor.value.trim()) {
    paymentError.value = "Nomor polisi wajib diisi.";
    return;
  }

  if (!uangCukup.value) {
    paymentError.value = "Uang yang dibayarkan kurang.";
    return;
  }

  if (memproses.value) {
    return;
  }

  memproses.value = true;

  try {
    const waktuKeluar = new Date();
    const platFinal = platNomor.value.trim().toUpperCase();
    const uangFinal = uangDibayarNum.value;
    const kembalianFinal = kembalian.value;

    const response = await $api.post("/keluar/bayar", {
      tiket_id: tiket.value.id,
      jenis_kendaraan: jenisKendaraan.value,
      plat_nomor: platFinal,
      uang_dibayar: uangFinal,
    });

    sukses.value = {
      kodeTiket: tiket.value.kode_tiket,
      kodeMember: "",
      nomorPolisi: platFinal,
      jenisKendaraan: jenisKendaraan.value,
      member: false,
      totalBayar: tiket.value.total_bayar,
      uangDibayar: uangFinal,
      kembalian: kembalianFinal,
    };

    cetakInvoice({
      isMember: false,
      kode: tiket.value.kode_tiket,
      jenisKendaraan: jenisKendaraan.value,
      platNomor: platFinal,
      waktuMasuk: tiket.value.waktu_masuk,
      waktuKeluar,
      totalBayar: tiket.value.total_bayar,
      tarifPerJamOverride: tiket.value.tarif_per_jam,
      uangDibayar: uangFinal,
      kembalian: kembalianFinal,
    });

    tiket.value = null;

    message.value =
      response?.data?.message ||
      "Pembayaran berhasil. Pintu terbuka.";

    success.value = true;

  } catch (error: any) {
    console.error("Bayar tiket gagal:", error);

    paymentError.value =
      error?.response?.data?.message ||
      error?.data?.message ||
      error?.message ||
      "Pembayaran gagal diproses.";

    success.value = false;

  } finally {
    memproses.value = false;
  }
};

/* =========================
   GENERATE INVOICE (PRINT/PDF)
========================= */

const cetakInvoice = (data: InvoicePayload) => {
  lastInvoiceData.value = data;

  const tarifPerJam =
    data.tarifPerJamOverride ??
    TARIF_KENDARAAN[data.jenisKendaraan] ??
    0;

  const masuk = parseWaktuLokal(data.waktuMasuk);
  const keluar = data.waktuKeluar;

  const durasiMenit = Math.max(
    0,
    Math.floor((keluar.getTime() - masuk.getTime()) / 60000)
  );

  const durasiJam = Math.max(1, Math.ceil(durasiMenit / 60));

  const totalNormal = durasiJam * tarifPerJam;

  const jenisLabel =
    data.jenisKendaraan === "mobil" ? "🚗 Mobil" : "🏍️ Motor";

  const fmtRupiah = (v: number) =>
    "Rp " + Number(v || 0).toLocaleString("id-ID");

  const fmtWaktu = (d: Date) =>
    d.toLocaleString("id-ID", { dateStyle: "short", timeStyle: "short" });

  const invoiceHtml = `
    <html>
      <head>
        <title>Invoice - ${data.kode}</title>
        <meta charset="UTF-8" />
        <style>
          * { box-sizing: border-box; }
          body {
            font-family: 'Courier New', monospace;
            padding: 24px;
            max-width: 380px;
            margin: 0 auto;
            color: #111827;
          }
          h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 4px;
          }
          .sub {
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 16px;
          }
          .divider {
            border-top: 1px dashed #9ca3af;
            margin: 12px 0;
          }
          .row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 6px;
          }
          .row span:first-child {
            color: #6b7280;
          }
          .total {
            font-size: 16px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
          }
          .badge {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #16a34a;
            margin-top: 6px;
          }
          .footer {
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            margin-top: 20px;
          }
          @media print {
            body { padding: 8px; }
          }
        </style>
      </head>
      <body>
        <h1>🅿️ INVOICE PARKIR</h1>
        <p class="sub">${new Date().toLocaleString("id-ID")}</p>

        <div class="divider"></div>

        <div class="row">
          <span>${data.isMember ? "Kode Member" : "Nomor Tiket"}</span>
          <span>${data.kode}</span>
        </div>

        ${
          data.nama
            ? `<div class="row"><span>Nama</span><span>${data.nama}</span></div>`
            : ""
        }

        ${
          data.perusahaan
            ? `<div class="row"><span>Perusahaan</span><span>${data.perusahaan}</span></div>`
            : ""
        }

        <div class="row">
          <span>Jenis Kendaraan</span>
          <span>${jenisLabel}</span>
        </div>

        <div class="row">
          <span>Nomor Polisi</span>
          <span>${data.platNomor}</span>
        </div>

        <div class="divider"></div>

        <div class="row">
          <span>Jam Masuk</span>
          <span>${fmtWaktu(masuk)}</span>
        </div>

        <div class="row">
          <span>Jam Keluar</span>
          <span>${fmtWaktu(keluar)}</span>
        </div>

        <div class="row">
          <span>Durasi</span>
          <span>${durasiJam} jam</span>
        </div>

        <div class="row">
          <span>Tarif</span>
          <span>${fmtRupiah(tarifPerJam)} / jam</span>
        </div>

        <div class="divider"></div>

        ${
          data.isMember
            ? `
              <div class="row">
                <span>Tarif Normal</span>
                <span>${fmtRupiah(totalNormal)}</span>
              </div>
              <div class="total">
                <span>Total Bayar</span>
                <span>GRATIS</span>
              </div>
              <p class="badge">✓ Hemat ${fmtRupiah(totalNormal)} (Member)</p>
            `
            : `
              <div class="total">
                <span>Total Bayar</span>
                <span>${fmtRupiah(data.totalBayar)}</span>
              </div>
              <div class="row" style="margin-top:8px;">
                <span>Uang Dibayar</span>
                <span>${fmtRupiah(data.uangDibayar ?? 0)}</span>
              </div>
              <div class="row">
                <span>Kembalian</span>
                <span>${fmtRupiah(data.kembalian ?? 0)}</span>
              </div>
            `
        }

        <div class="footer">
          Terima kasih telah menggunakan layanan parkir kami 🚗
        </div>
      </body>
    </html>
  `;

  const invoiceWindow = window.open("", "_blank", "width=420,height=650");

  if (!invoiceWindow) {
    alert("Popup diblokir browser. Izinkan popup untuk mencetak/mengunduh invoice.");
    return;
  }

  invoiceWindow.document.open();
  invoiceWindow.document.write(invoiceHtml);
  invoiceWindow.document.close();

  invoiceWindow.onload = () => {
    invoiceWindow.focus();
    invoiceWindow.print();
  };
};

/* =========================
   CETAK ULANG INVOICE
========================= */

const cetakUlangInvoice = () => {
  if (lastInvoiceData.value) {
    cetakInvoice(lastInvoiceData.value);
  }
};

/* =========================
   BATAL / OK
========================= */

const batal = async () => {
  resetState();

  await nextTick();

  inputRef.value?.focus();
};

/* =========================
   RESET
========================= */

const resetState = () => {
  member.value = null;
  tiket.value = null;
  sukses.value = null;

  memproses.value = false;

  qrCode.value = "";
  qrCodeInput.value = "";

  platNomor.value = "";
  jenisKendaraan.value = "";
  uangDibayar.value = null;

  paymentError.value = "";

  message.value = "";
  success.value = false;
};

/* =========================
   FORMAT DURASI
========================= */

const formatDurasi = (menit: number) => {
  const m = Math.floor(Number(menit) || 0);

  const jam = Math.floor(m / 60);
  const sisa = m % 60;

  if (jam > 0) {
    return sisa > 0
      ? `${jam} jam ${sisa} menit`
      : `${jam} jam`;
  }

  return `${sisa} menit`;
};

/* =========================
   FORMAT TANGGAL
========================= */

const formatTanggal = (tanggal: string) => {
  if (!tanggal) return "-";

  const d = parseWaktuLokal(tanggal);

  if (isNaN(d.getTime())) return "-";

  return d.toLocaleString("id-ID", {
    dateStyle: "short",
    timeStyle: "short",
  });
};

/* =========================
   FORMAT RUPIAH
========================= */

const formatRupiah = (val: number) => {
  return (
    "Rp " +
    Number(val || 0).toLocaleString("id-ID")
  );
};

/* =========================
   AUTO FOCUS
========================= */

onMounted(() => {
  nextTick(() => {
    inputRef.value?.focus();
  });
});
</script>