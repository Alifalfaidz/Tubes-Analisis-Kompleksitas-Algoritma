<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kasir — Iteratif vs Rekursif</title>

  <!-- Tailwind CDN (tanpa Node) -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    .grain:before{
      content:"";
      position:absolute; inset:0;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)' opacity='.13'/%3E%3C/svg%3E");
      pointer-events:none;
      mix-blend-mode:overlay;
      border-radius:24px;
    }
  </style>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100">
  <!-- neon bg -->
  <div class="fixed inset-0 -z-10">
    <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-fuchsia-600/30 blur-3xl"></div>
    <div class="absolute top-20 -right-24 h-96 w-96 rounded-full bg-cyan-400/25 blur-3xl"></div>
    <div class="absolute bottom-0 left-1/3 h-96 w-96 rounded-full bg-amber-400/15 blur-3xl"></div>
  </div>

  <header class="mx-auto max-w-6xl px-4 pt-8">
    <div class="flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="h-11 w-11 rounded-2xl bg-white/10 ring-1 ring-white/15 grid place-items-center">🧾</div>
        <div>
          <p class="text-sm text-slate-300">Tubes AKA — Simulasi Kasir</p>
          <h1 class="text-xl font-semibold tracking-tight">Total Belanja: Iteratif vs Rekursif</h1>
        </div>
      </div>

      <div class="hidden sm:flex items-center gap-2">
        <span class="text-xs text-slate-300">UI/UX: glass • neon • clean</span>
        <span class="text-xs px-2 py-1 rounded-full bg-white/10 ring-1 ring-white/15">Gen-Z mode ✨</span>
      </div>
    </div>

    <!-- CARD HEADER (Anggota + Tentang Aplikasi) -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-4">
      <!-- Studi kasus / deskripsi singkat (besar) -->
      <div class="relative grain rounded-3xl bg-white/5 ring-1 ring-white/10 p-5 lg:col-span-2 overflow-hidden">
        <p class="text-sm text-slate-300">Studi Kasus</p>
        <h2 class="mt-1 text-2xl font-semibold leading-snug">
          Sistem Kasir: Hitung Total Belanja 🛒
          <span class="text-slate-300 font-normal">dengan 2 pendekatan</span>
        </h2>
        <p class="mt-3 text-slate-300 leading-relaxed">
          Aplikasi ini mensimulasikan proses kasir dalam menjumlahkan harga barang (per unit) menggunakan
          <b>algoritma iteratif</b> dan <b>algoritma rekursif</b>. Kamu bisa memilih algoritmanya, lalu melihat
          <b>total belanja</b> dan <b>waktu eksekusi</b> untuk membandingkan efisiensi.
        </p>

        <div class="mt-5 flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/20">Iteratif O(n)</span>
          <span class="text-xs px-3 py-1 rounded-full bg-fuchsia-500/10 ring-1 ring-fuchsia-400/20">Rekursif O(n)</span>
          <span class="text-xs px-3 py-1 rounded-full bg-cyan-500/10 ring-1 ring-cyan-400/20">Median time</span>
          <span class="text-xs px-3 py-1 rounded-full bg-amber-500/10 ring-1 ring-amber-400/20">Kasir Mode</span>
        </div>
      </div>

      <!-- Anggota -->
      <div class="rounded-3xl bg-white/5 ring-1 ring-white/10 p-5">
        <p class="text-sm text-slate-300">Anggota</p>
        <ul class="mt-3 space-y-2 text-slate-200">
          <li class="flex items-center justify-between">
            <span>Fazrul Ridha Alliandre</span><span class="text-slate-400 text-sm">103012580022</span>
          </li>
          <li class="flex items-center justify-between">
            <span>Alif Mahtum Alfaidz</span><span class="text-slate-400 text-sm">103012580030</span>
          </li>
          <li class="flex items-center justify-between">
            <span>Muhammad</span><span class="text-slate-400 text-sm">103012580058</span>
          </li>
        </ul>

        <div class="mt-5 rounded-2xl bg-black/30 ring-1 ring-white/10 p-4 text-sm text-slate-300">
          <p class="font-medium text-slate-200">Catatan cepat</p>
          <p class="mt-1">Rekursif punya overhead call stack & bisa error kalau data terlalu besar.</p>
        </div>
      </div>

      <!-- Tentang Aplikasi -->
      <div class="rounded-3xl bg-white/5 ring-1 ring-white/10 p-5">
        <p class="text-sm text-slate-300">Tentang Aplikasi</p>
        <h3 class="mt-2 text-lg font-semibold">Cara Pakai</h3>

        <ol class="mt-3 list-decimal list-inside space-y-2 text-sm text-slate-300">
          <li>Tambah barang (nama, harga, qty) ke keranjang.</li>
          <li>Pilih algoritma: <b>Iteratif</b> atau <b>Rekursif</b>.</li>
          <li>Klik <b>HITUNG TOTAL</b> atau <b>Compare</b> untuk bandingkan performa.</li>
        </ol>

        <div class="mt-4 rounded-2xl bg-black/30 ring-1 ring-white/10 p-4 text-sm text-slate-300">
          <p class="font-medium text-slate-200">Tujuan</p>
          <p class="mt-1">Membandingkan efisiensi algoritma berdasarkan waktu eksekusi saat jumlah data (n) meningkat.</p>
        </div>
      </div>
    </div>

    @if($errors->any())
      <div class="mt-5 rounded-2xl bg-red-500/10 ring-1 ring-red-400/25 p-4 text-sm text-red-100">
        <p class="font-semibold">Ada yang perlu dibenerin dulu:</p>
        <ul class="list-disc list-inside mt-2 space-y-1">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  </header>

  <main class="mx-auto max-w-6xl px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      <!-- LEFT: Input item -->
      <section class="relative grain rounded-3xl bg-white/5 ring-1 ring-white/10 p-5 overflow-hidden lg:col-span-1">
        <h2 class="text-lg font-semibold">🛒 Input Barang</h2>
        <p class="text-sm text-slate-300 mt-1">Tambah item belanja (nama, harga, qty).</p>

        <form method="POST" action="{{ route('items.add') }}" class="mt-5 space-y-3">
          @csrf
          <div>
            <label class="text-sm text-slate-200">Nama Barang</label>
            <input name="name" value="{{ old('name') }}"
              class="mt-2 w-full rounded-2xl bg-black/30 ring-1 ring-white/10 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-fuchsia-400"
              placeholder="contoh: Es kopi susu" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-sm text-slate-200">Harga (Rp)</label>
              <input type="number" name="price" value="{{ old('price', 10000) }}"
                class="mt-2 w-full rounded-2xl bg-black/30 ring-1 ring-white/10 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-300" />
            </div>
            <div>
              <label class="text-sm text-slate-200">Qty</label>
              <input type="number" name="qty" value="{{ old('qty', 1) }}"
                class="mt-2 w-full rounded-2xl bg-black/30 ring-1 ring-white/10 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-300" />
            </div>
          </div>

          <button class="w-full rounded-2xl bg-gradient-to-r from-fuchsia-500 to-cyan-400 px-4 py-3 font-semibold text-slate-950 hover:opacity-95 active:scale-[0.99] transition">
            Tambah ke Keranjang ✨
          </button>
        </form>

        <form method="POST" action="{{ route('items.clear') }}" class="mt-3">
          @csrf
          <button class="w-full rounded-2xl bg-white/10 ring-1 ring-white/15 px-4 py-3 text-sm text-slate-100 hover:bg-white/15 transition">
            Hapus Semua Item
          </button>
        </form>

        <div class="mt-4 rounded-2xl bg-black/30 ring-1 ring-white/10 p-4 text-sm text-slate-300">
          <p class="font-medium text-slate-200">Tips Tubes</p>
          <p class="mt-1">Untuk lihat perbedaan performa, coba isi item banyak (qty besar) lalu klik “Compare”.</p>
        </div>
      </section>

      <!-- RIGHT: Cart + checkout -->
      <section class="rounded-3xl bg-white/5 ring-1 ring-white/10 p-5 lg:col-span-2">
        <div class="flex items-start justify-between gap-3">
          <div>
            <h2 class="text-lg font-semibold">🧺 Keranjang</h2>
            <p class="text-sm text-slate-300 mt-1">Daftar item belanja kamu.</p>
          </div>

          <div class="text-xs text-slate-300 rounded-2xl bg-black/30 ring-1 ring-white/10 px-3 py-2">
            Total item: <b>{{ count($items) }}</b>
          </div>
        </div>

        <div class="mt-4 overflow-x-auto rounded-2xl bg-black/30 ring-1 ring-white/10">
          <table class="min-w-full text-sm">
            <thead class="text-slate-300">
              <tr class="border-b border-white/10">
                <th class="text-left p-3">Nama</th>
                <th class="text-left p-3">Harga</th>
                <th class="text-left p-3">Qty</th>
                <th class="text-left p-3">Subtotal</th>
                <th class="p-3"></th>
              </tr>
            </thead>
            <tbody class="text-slate-100">
              @forelse($items as $it)
                <tr class="border-b border-white/5 hover:bg-white/5 transition">
                  <td class="p-3 font-medium">{{ $it['name'] }}</td>
                  <td class="p-3">Rp {{ number_format($it['price'], 0, ',', '.') }}</td>
                  <td class="p-3">{{ $it['qty'] }}</td>
                  <td class="p-3">Rp {{ number_format($it['price'] * $it['qty'], 0, ',', '.') }}</td>
                  <td class="p-3 text-right">
                    <form method="POST" action="{{ route('items.remove') }}">
                      @csrf
                      <input type="hidden" name="id" value="{{ $it['id'] }}">
                      <button class="rounded-xl bg-red-500/15 ring-1 ring-red-400/25 px-3 py-2 text-xs hover:bg-red-500/20 transition">
                        Hapus
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="p-5 text-center text-slate-400">
                    Keranjang masih kosong. Tambah barang dulu ya 👀
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Checkout controls -->
        <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="rounded-2xl bg-black/30 ring-1 ring-white/10 p-4 md:col-span-2">
            <h3 class="font-semibold">⚙️ Pilih Algoritma</h3>

            <form method="POST" action="{{ route('checkout') }}" class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
              @csrf

              <div class="sm:col-span-2">
                <label class="text-sm text-slate-200">Mode</label>
                <div class="mt-2 flex flex-wrap gap-2">
                  <label class="cursor-pointer">
                    <input type="radio" name="algorithm" value="iterative" class="hidden" checked>
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 ring-1 ring-emerald-400/20 px-4 py-2 text-sm hover:bg-emerald-500/15 transition">
                      ⚡ Iteratif <span class="text-xs text-slate-300">(recommended)</span>
                    </span>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="algorithm" value="recursive" class="hidden">
                    <span class="inline-flex items-center gap-2 rounded-full bg-fuchsia-500/10 ring-1 ring-fuchsia-400/20 px-4 py-2 text-sm hover:bg-fuchsia-500/15 transition">
                      🌀 Rekursif <span class="text-xs text-slate-300">(experimental)</span>
                    </span>
                  </label>
                </div>
              </div>

              <div>
                <label class="text-sm text-slate-200">Repeat (median)</label>
                <input type="number" name="repeat" value="10"
                  class="mt-2 w-full rounded-2xl bg-black/30 ring-1 ring-white/10 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-300" />
              </div>

              <button class="sm:col-span-3 rounded-2xl bg-gradient-to-r from-emerald-400 to-cyan-300 px-4 py-3 font-semibold text-slate-950 hover:opacity-95 active:scale-[0.99] transition">
                HITUNG TOTAL 💸
              </button>
            </form>

            <form method="POST" action="{{ route('compare') }}" class="mt-3">
              @csrf
              <input type="hidden" name="repeat" value="10">
              <button class="w-full rounded-2xl bg-white/10 ring-1 ring-white/15 px-4 py-3 text-sm hover:bg-white/15 transition">
                Compare Iteratif vs Rekursif 📊
              </button>
            </form>
          </div>

          <!-- Result card -->
          <div class="rounded-2xl bg-black/30 ring-1 ring-white/10 p-4">
            <h3 class="font-semibold">📌 Hasil</h3>

            @php $result = $result ?? []; @endphp

            @if(empty($result))
              <p class="text-sm text-slate-300 mt-2">Belum ada hasil. Jalankan “HITUNG TOTAL” dulu.</p>
            @else
              @if(($result['mode'] ?? '') === 'single')
                <div class="mt-3 space-y-2 text-sm">
                  <p class="text-slate-300">Algoritma: <b class="text-slate-100">{{ $result['algorithm'] }}</b></p>
                  <p class="text-slate-300">n (jumlah data): <b class="text-slate-100">{{ $result['n'] }}</b></p>
                  <p class="text-slate-300">Repeat: <b class="text-slate-100">{{ $result['repeat'] }}</b></p>
                  <p class="text-slate-300">Waktu (median): <b class="text-slate-100">{{ $result['time_ms'] }} ms</b></p>
                  <p class="text-slate-300">Total: <b class="text-slate-100">Rp {{ number_format($result['total'], 0, ',', '.') }}</b></p>
                </div>
              @else
                <div class="mt-3 space-y-3 text-sm">
                  <p class="text-slate-300">n (jumlah data): <b class="text-slate-100">{{ $result['n'] }}</b></p>
                  <p class="text-slate-300">Repeat: <b class="text-slate-100">{{ $result['repeat'] }}</b></p>

                  <div class="rounded-xl bg-emerald-500/10 ring-1 ring-emerald-400/20 p-3">
                    <p class="font-semibold">⚡ Iteratif</p>
                    <p class="text-slate-200">Time: <b>{{ $result['iter']['time_ms'] }} ms</b></p>
                    <p class="text-slate-200">Total: <b>Rp {{ number_format($result['iter']['total'], 0, ',', '.') }}</b></p>
                  </div>

                  <div class="rounded-xl bg-fuchsia-500/10 ring-1 ring-fuchsia-400/20 p-3">
                    <p class="font-semibold">🌀 Rekursif</p>
                    @if(is_null($result['rec']['time_ms']))
                      <p class="text-slate-200">Time: <b>-</b></p>
                      <p class="text-slate-200">Total: <b>-</b></p>
                    @else
                      <p class="text-slate-200">Time: <b>{{ $result['rec']['time_ms'] }} ms</b></p>
                      <p class="text-slate-200">Total: <b>Rp {{ number_format($result['rec']['total'], 0, ',', '.') }}</b></p>
                    @endif
                  </div>

                  @if(!empty($result['note']))
                    <p class="text-xs text-amber-200">{{ $result['note'] }}</p>
                  @endif

                  <div class="text-xs text-slate-300 rounded-xl bg-white/5 ring-1 ring-white/10 p-3">
                    <p class="font-semibold text-slate-200">Complexity (buat laporan)</p>
                    <p>Iteratif: {{ $result['complexity']['iterative_time'] }} time, {{ $result['complexity']['iterative_space'] }} space</p>
                    <p>Rekursif: {{ $result['complexity']['recursive_time'] }} time, {{ $result['complexity']['recursive_space'] }} space</p>
                  </div>
                </div>
              @endif
            @endif
          </div>
        </div>

      </section>
    </div>
  </main>

  <!-- Footer credit -->
  <footer class="mx-auto max-w-6xl px-4 pb-10">
    <div class="mt-6 rounded-2xl bg-white/5 ring-1 ring-white/10 p-4 text-xs text-slate-300 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
      <span>© 2025 — Tubes Analisis Kompleksitas Algoritma</span>
      <span class="text-slate-400">Fazrul • Alfaidz • Muhammad</span>
    </div>
  </footer>
</body>
</html>
