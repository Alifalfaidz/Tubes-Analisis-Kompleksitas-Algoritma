<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $items  = $request->session()->get('items', []);
        $result = $request->session()->get('result', []);
        $request->session()->forget('result');

        return view('kasir', [
            'items'  => $items,
            'result' => $result,
        ]);
    }

    public function addItem(Request $request)
    {
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:60'],
            'price' => ['required', 'integer', 'min:0'],
            'qty'   => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $items   = $request->session()->get('items', []);
        $items[] = [
            'id'    => bin2hex(random_bytes(6)),
            'name'  => $data['name'],
            'price' => (int) $data['price'],
            'qty'   => (int) $data['qty'],
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
            'repeat'    => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $items  = $request->session()->get('items', []);
        $prices = $this->expandToPriceArray($items);
        $n      = count($prices);

        if ($n === 0) {
            return redirect()->route('kasir')->withErrors(['items' => 'Keranjang masih kosong. Tambahin barang dulu ya.']);
        }

        $maxSafeRecursiveN = 2000;
        if ($data['algorithm'] === 'recursive' && $n > $maxSafeRecursiveN) {
            return redirect()->route('kasir')->withErrors([
                'algorithm' => "n={$n} terlalu besar untuk rekursif (batas aman {$maxSafeRecursiveN}). Coba iteratif / kecilkan data."
            ]);
        }

        [$ms, $total] = $this->timeIt(function () use ($data, $prices) {
            return $data['algorithm'] === 'iterative'
                ? $this->sumIterative($prices)
                : $this->sumRecursive($prices);
        }, (int)$data['repeat']);

        $algoLabel = $data['algorithm'] === 'iterative' ? 'Iteratif' : 'Rekursif';

        $request->session()->put('result', [
            'mode'      => 'single',
            'algorithm' => $algoLabel,
            'n'         => $n,
            'repeat'    => (int)$data['repeat'],
            'total'     => $total,
            'time_ms'   => $ms,
        ]);

        return redirect()->route('kasir');
    }

    public function compare(Request $request)
    {
        $data = $request->validate([
            'repeat' => ['required', 'integer', 'min:1', 'max:50'],
        ]);

        $items  = $request->session()->get('items', []);
        $prices = $this->expandToPriceArray($items);
        $n      = count($prices);

        if ($n === 0) {
            return redirect()->route('kasir')->withErrors(['items' => 'Keranjang masih kosong. Tambahin barang dulu ya.']);
        }

        $maxSafeRecursiveN = 2000;

        // iteratif
        [$iterMs, $iterTotal] = $this->timeIt(fn() => $this->sumIterative($prices), (int)$data['repeat']);

        // rekursif (skip kalau kebesaran)
        $recMs = null;
        $recTotal = null;
        $note = null;

        if ($n <= $maxSafeRecursiveN) {
            [$recMs, $recTotal] = $this->timeIt(fn() => $this->sumRecursive($prices), (int)$data['repeat']);
        } else {
            $note = "Rekursif di-skip karena n={$n} melewati batas aman {$maxSafeRecursiveN}.";
        }

        // ===== kurva time vs n (sample points) =====
        $curveRepeat = max(1, min(10, (int) floor(((int)$data['repeat']) / 2)));

        $points = $this->makeSamplePoints($n, 10); // 10 titik biar halus
        $curveLabels = [];
        $curveIterMs = [];
        $curveRecMs  = [];

        foreach ($points as $k) {
            $curveLabels[] = $k;

            [$tIter] = $this->timeIt(fn() => $this->sumIterativePrefix($prices, $k), $curveRepeat);
            $curveIterMs[] = $tIter;

            if ($k <= $maxSafeRecursiveN) {
                [$tRec] = $this->timeIt(fn() => $this->sumRecursivePrefix($prices, $k), $curveRepeat);
                $curveRecMs[] = $tRec;
            } else {
                $curveRecMs[] = null;
            }
        }

        $request->session()->put('result', [
            'mode'   => 'compare',
            'n'      => $n,
            'repeat' => (int)$data['repeat'],
            'iter'   => ['total' => $iterTotal, 'time_ms' => $iterMs],
            'rec'    => ['total' => $recTotal,  'time_ms' => $recMs],
            'note'   => $note,
            'complexity' => [
                'iterative_time'   => 'O(n)',
                'iterative_space'  => 'O(1)',
                'recursive_time'   => 'O(n)',
                'recursive_space'  => 'O(n) (call stack)',
            ],
            'curve' => [
                'labels'  => $curveLabels,
                'iter_ms' => $curveIterMs,
                'rec_ms'  => $curveRecMs,
                'repeat'  => $curveRepeat,
            ],
        ]);

        return redirect()->route('kasir');
    }

    // ===== Helpers =====

    private function expandToPriceArray(array $items): array
    {
        $prices = [];
        foreach ($items as $it) {
            $qty = (int)$it['qty'];
            $price = (int)$it['price'];
            for ($i = 0; $i < $qty; $i++) $prices[] = $price;
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
        $n = count($prices);
        return $this->sumRecursiveHelper($prices, 0, $n);
    }

    private function sumRecursiveHelper(array $prices, int $i, int $n): int
    {
        if ($i >= $n) return 0;
        return $prices[$i] + $this->sumRecursiveHelper($prices, $i + 1, $n);
    }

    private function sumIterativePrefix(array $prices, int $len): int
    {
        $sum = 0;
        $n = min($len, count($prices));
        for ($i = 0; $i < $n; $i++) $sum += $prices[$i];
        return $sum;
    }

    private function sumRecursivePrefix(array $prices, int $len): int
    {
        $n = min($len, count($prices));
        return $this->sumRecursiveHelper($prices, 0, $n);
    }

    private function timeIt(callable $fn, int $repeat): array
    {
        $fn(); // warm-up

        $times = [];
        $last = null;

        for ($i = 0; $i < $repeat; $i++) {
            $start = hrtime(true);
            $last = $fn();
            $end = hrtime(true);
            $times[] = ($end - $start) / 1_000_000; // ms
        }

        sort($times);
        $median = $times[(int) floor(count($times) / 2)];
        return [round($median, 6), $last];
    }

    private function makeSamplePoints(int $n, int $points = 10): array
    {
        $n = max(1, $n);
        $points = max(2, $points);

        $out = [];
        for ($i = 1; $i <= $points; $i++) {
            $k = (int) round(($n * $i) / $points);
            $k = max(1, min($n, $k));
            $out[] = $k;
        }

        $out = array_values(array_unique($out));
        sort($out);
        return $out;
    }
}
