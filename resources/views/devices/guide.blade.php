@extends('layouts.app')

@section('content')
<div class="container">
  <h4 class="mb-3">Device Setup Guide (ZKTeco ADMS / Push)</h4>

  <div class="card mb-3">
    <div class="card-body">
      <h6>1) Server info to enter on the device</h6>
      @php($base = request()->getSchemeAndHttpHost())
      <ul>
        <li>Server/IP: <code>{{ parse_url($base, PHP_URL_HOST) }}</code></li>
        <li>Port: <code>{{ parse_url($base, PHP_URL_PORT) ?? (request()->getScheme() === 'https' ? 443 : 80) }}</code></li>
        <li>Path/URI: <code>/iclock/cdata</code></li>
        <li>Realtime/Push: <strong>Enabled</strong></li>
      </ul>
      <div class="small text-muted">Run the app with: <code>php artisan serve --host=0.0.0.0 --port=YOUR_PORT</code></div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <h6>2) Quick verification</h6>
      <div class="mb-2">Handshake link (replace SN if needed):</div>
      <div class="input-group mb-3">
        <input type="text" id="testSN" class="form-control" placeholder="TESTSN">
        <a id="openHandshake" class="btn btn-outline-secondary" target="_blank" href="#">Open Handshake</a>
      </div>

      <div class="mb-2">cURL test (copy & run from a PC on the same LAN):</div>
      <div class="input-group">
        <input type="text" readonly class="form-control" id="curlCmd">
        <button class="btn btn-outline-secondary" type="button" id="copyCurl">Copy</button>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h6>3) Troubleshooting</h6>
      <ul>
        <li>Allow inbound port in firewall; use HTTP if TLS fails.</li>
        <li>Ensure device and server are on the same network/subnet.</li>
        <li>Device date/time/timezone must be correct.</li>
      </ul>
    </div>
  </div>

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
  copyBtn && copyBtn.addEventListener('click', function(){ curlInput.select(); document.execCommand('copy'); });
  updateSamples();
})();
</script>
@endsection


