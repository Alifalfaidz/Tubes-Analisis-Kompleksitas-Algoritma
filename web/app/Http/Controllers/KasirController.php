<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->session()->get('items', []);
        $result = $request->session()->get('result', []);
        $request->session()->forget('result'); // biar result cuma tampil sekali (opsional)

        return view('kasir', [
            'items' => $items,
            'result' => $result,
        ]);
    }

    public function addItem(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'price' => ['required', 'integer', 'min:0'],
            'qty' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $items = $request->session()->get('items', []);
        $items[] = [
            'id' => bin2hex(random_bytes(6)),
            'name' => $data['name'],
            'price' => (int)$data['price'],
            'qty' => (int)$data['qty'],
        ];
        $request->session()->put('items', $items);

        return redirect()->route('kasir');
    }

    public function removeItem(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'string'],
        ]);

        $items = $request->session()->get('items', []);
        $items = array_values(array_filter($items, fn($it) => $it['id'] !== $data['id']));
        $request->session()->put('items', $items);

        return redirect()->route('kasir');
    }

    public function clearItems(Request $request)
    {
        $request->session()->forget('items');
        return redirect()->route('kasir');
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'algorithm' => ['required', 'in:iterative,recursive'],
            'repeat' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $items = $request->session()->get('items', []);
        $prices = $this->expandToPriceArray($items);
        $n = count($prices);

        if ($n === 0) {
            return redirect()->route('kasir')->withErrors(['items' => 'Keranjang masih kosong. Tambahin barang dulu ya.']);
        }

        // batas aman rekursif (anti stack overflow)
        $maxSafeRecursiveN = 2000;
        if ($data['algorithm'] === 'recursive' && $n > $maxSafeRecursiveN) {
            return redirect()->route('kasir')->withErrors([
                'algorithm' => "n={$n} terlalu besar untuk rekursif (batas aman {$maxSafeRecursiveN}). Coba iteratif / kecilkan data."
            ]);
        }

        [$ms, $total] = $this->timeIt(function() use ($data, $prices) {
            return $data['algorithm'] === 'iterative'
                ? $this->sumIterative($prices)
                : $this->sumRecursive($prices);
        }, (int)$data['repeat']);

        $request->session()->put('result', [
            'mode' => 'single',
            'algorithm' => $data['algorithm'],
            'n' => $n,
            'repeat' => (int)$data['repeat'],
            'total' => $total,
            'time_ms' => $ms,
        ]);

        return redirect()->route('kasir');
    }

    public function compare(Request $request)
    {
        $data = $request->validate([
            'repeat' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $items = $request->session()->get('items', []);
        $prices = $this->expandToPriceArray($items);
        $n = count($prices);

        if ($n === 0) {
            return redirect()->route('kasir')->withErrors(['items' => 'Keranjang masih kosong. Tambahin barang dulu ya.']);
        }

        $maxSafeRecursiveN = 2000;

        // Iteratif
        [$iterMs, $iterTotal] = $this->timeIt(fn() => $this->sumIterative($prices), (int)$data['repeat']);

        // Rekursif (skip kalau n kebesaran)
        $recMs = null;
        $recTotal = null;
        $note = null;

        if ($n <= $maxSafeRecursiveN) {
            [$recMs, $recTotal] = $this->timeIt(fn() => $this->sumRecursive($prices), (int)$data['repeat']);
        } else {
            $note = "Rekursif di-skip karena n={$n} melewati batas aman {$maxSafeRecursiveN}.";
        }

        $request->session()->put('result', [
            'mode' => 'compare',
            'n' => $n,
            'repeat' => (int)$data['repeat'],
            'iter' => ['total' => $iterTotal, 'time_ms' => $iterMs],
            'rec'  => ['total' => $recTotal,  'time_ms' => $recMs],
            'note' => $note,
            'complexity' => [
                'iterative_time' => 'O(n)',
                'iterative_space' => 'O(1)',
                'recursive_time' => 'O(n)',
                'recursive_space' => 'O(n) (call stack)',
            ],
        ]);

        return redirect()->route('kasir');
    }

    // ===== Helpers & Algorithms =====

    private function expandToPriceArray(array $items): array
    {
        // Ubah item (price, qty) jadi array harga per unit supaya n bener-bener "jumlah data"
        $prices = [];
        foreach ($items as $it) {
            $qty = (int)$it['qty'];
            $price = (int)$it['price'];
            for ($i = 0; $i < $qty; $i++) {
                $prices[] = $price;
            }
        }
        return $prices;
    }

    private function sumIterative(array $prices): int
    {
        $sum = 0;
        foreach ($prices as $p) $sum += $p;
        return $sum;
    }

    private function sumRecursive(array $prices): int
    {
        return $this->sumRecursiveHelper($prices, 0);
    }

    private function sumRecursiveHelper(array $prices, int $i): int
    {
        if ($i >= count($prices)) return 0;
        return $prices[$i] + $this->sumRecursiveHelper($prices, $i + 1);
    }

    /**
     * Run callable N times, return median ms + last result.
     */
    private function timeIt(callable $fn, int $repeat): array
    {
        // warm-up
        $fn();

        $times = [];
        $last = null;
        for ($i = 0; $i < $repeat; $i++) {
            $start = hrtime(true);
            $last = $fn();
            $end = hrtime(true);
            $times[] = ($end - $start) / 1_000_000; // ns -> ms
        }
        sort($times);
        $median = $times[(int) floor(count($times) / 2)];
        return [round($median, 4), $last];
    }
}
