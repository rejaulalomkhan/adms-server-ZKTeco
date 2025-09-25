@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">{{ $lable }}</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('devices.Guide') }}" class="btn btn-outline-secondary">Open Guide</a>
                <a href="{{ route('devices.create') }}" class="btn btn-primary">Add Device</a>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                @php($base = request()->getSchemeAndHttpHost())
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-lg-8">
                        <h6 class="mb-2">Connect your ZKTeco (ADMS/Push)</h6>
                        <ul class="mb-2">
                            <li>Server/IP: <code>{{ parse_url($base, PHP_URL_HOST) }}</code></li>
                            <li>Port: <code>{{ parse_url($base, PHP_URL_PORT) ?? (request()->getScheme() === 'https' ? 443 : 80) }}</code></li>
                            <li>Path/URI: <code>/iclock/cdata</code></li>
                            <li>Realtime/Push: <strong>Enabled</strong></li>
                        </ul>
                        <div class="small text-muted">Tip: run with <code>php artisan serve --host=0.0.0.0 --port=YOUR_PORT</code> so devices can reach your PC.</div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="form-label">Test SN</label>
                        <div class="input-group mb-2">
                            <input type="text" id="testSN" class="form-control" placeholder="TESTSN">
                            <a id="openHandshake" class="btn btn-outline-secondary" target="_blank" href="#">Open Handshake</a>
                        </div>
                        <label class="form-label">cURL test (copy & run)</label>
                        <div class="input-group">
                            <input type="text" readonly class="form-control" id="curlCmd">
                            <button class="btn btn-outline-secondary" type="button" id="copyCurl">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered data-table" id="devices">
            <thead>
                <tr>
                    {{-- <th>No</th> --}}
                    <th>Serial Number</th>
                    <th>Online</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($log as $d)
                    <tr>
                        {{-- <td>{{ $d->id }}</td> --}}
                        <td>{{ $d->no_sn }}</td>
                        <td>{{ $d->online }}</td>
                        <td>
                            <a href="{{ route('devices.edit', $d->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
<script>
    (function(){
        const base = '{{ $base ?? '' }}' || window.location.origin;
        const snInput = document.getElementById('testSN');
        const openBtn = document.getElementById('openHandshake');
        const curlInput = document.getElementById('curlCmd');
        const copyBtn = document.getElementById('copyCurl');

        function today(){ const d=new Date(); return d.toISOString().slice(0,10); }
        function updateSamples(){
            const sn = snInput && snInput.value ? snInput.value : 'TESTSN';
            const handshakeUrl = `${base}/iclock/cdata?SN=${encodeURIComponent(sn)}&option=all`;
            if (openBtn) openBtn.href = handshakeUrl;
            const curl = `curl -X POST \"${base}/iclock/cdata?SN=${sn}&table=ATTLOG&Stamp=1\" -H \"Content-Type: text/plain\" --data-binary $'1001\\t${today()} 09:05:00\\t1'`;
            if (curlInput) curlInput.value = curl;
        }
        snInput && snInput.addEventListener('input', updateSamples);
        copyBtn && copyBtn.addEventListener('click', function(){
            curlInput.select();
            document.execCommand('copy');
        });
        updateSamples();
    })();
    </script>
@endsection
