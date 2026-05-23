<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print e-cards</title>
  <style>
    body{font-family: Arial, sans-serif; background:#f5f7fb; margin:0; padding:20px;}
    .print-actions{margin-bottom:20px; text-align:center;}
    .ecard{position:relative; width:860px; height:520px; margin:0 auto 24px; border-radius:18px; overflow:hidden; color:#fff; box-shadow:0 10px 30px rgba(0,0,0,0.15);}
    .ecard::before{content:''; position:absolute; inset:0; background: radial-gradient(1200px 600px at 20% 20%, #6f29e1 0%, #4b0aac 40%, #1b2b70 70%, #06234b 100%), url('{{ asset('backend-assets/img/ecard-bg.jpg') }}'); background-size: cover; background-blend-mode: screen; opacity:0.95;}
    .content{position:relative; z-index:2; padding:26px 34px;}
    .top{display:flex; justify-content:space-between; align-items:center;}
    .brand{font-weight:700; font-size:28px; letter-spacing:.5px;}
    .world{opacity:.85;}
    .holder{display:flex; align-items:center; margin-top:22px;}
    .photo{width:120px; height:120px; background:#fff; border-radius:8px; overflow:hidden; margin-right:18px;}
    .photo img{width:100%;height:100%;object-fit:cover;}
    .details{line-height:1.4;}
    .name{font-size:26px; font-weight:700;}
    .dob{font-size:16px; opacity:.9;}
    .numbers{display:flex; gap:32px; margin-top:26px; font-size:26px; letter-spacing:2px;}
    .footer{position:absolute; bottom:22px; left:34px; right:34px; display:flex; justify-content:space-between; align-items:center;}
    .id{font-weight:700;}
    .valid{opacity:.9;}
    .badge{background:#2bc48a; padding:6px 12px; border-radius:16px; font-weight:700;}
    .pagebreak{page-break-after: always;}
    @media print {
      .print-actions{ display:none; }
      body{ background:#fff; padding:0; }
      .ecard{ box-shadow:none; margin:0 auto 0; }
      .pagebreak{page-break-after: always;}
    }
  </style>
</head>
<body>
  <div class="print-actions">
    <button onclick="window.print()" style="padding:10px 14px; font-size:14px;">Print / Save PDF</button>
  </div>

  @foreach($users as $user)
    @php
      $fullName = trim(implode(' ', array_filter([$user->first_name ?? null, $user->middle_name ?? null, $user->last_name ?? null])));
      $createdTs = $user->created_at ? strtotime($user->created_at) : time();
      $validThru = date('m/y', strtotime('+3 years', $createdTs));
      $seg1 = substr($user->mobile_no ?? '0000', 0, 4);
      $seg2 = substr((string) ($user->user_id ?? $user->id), -4);
      $seg3 = date('y', $createdTs).date('m', $createdTs);
      $seg4 = str_pad((string) ((int) ($user->id ?? 0) % 10000), 4, '0', STR_PAD_LEFT);
      $photoUrl = null;
      if (! empty($user->profile_image)) {
        $photoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_image);
      }
    @endphp
    <div class="ecard">
      <div class="content">
        <div class="top">
          <div class="brand">e-card <span style="font-weight:400; font-size:18px;">International Card</span></div>
          <div class="world">{{ $user->state ?? '' }} {{ !empty($user->district) ? '• '.$user->district : '' }}</div>
        </div>
        <div class="holder">
          <div class="photo">
            @if($photoUrl)
              <img src="{{ $photoUrl }}" alt="Photo">
            @else
              <img src="{{ asset('frontend-assets/img/avatar-placeholder.png') }}" alt="Photo">
            @endif
          </div>
          <div class="details">
            <div class="name">{{ strtoupper($fullName) }}</div>
            <div class="dob">D.O.B. {{ $user->date_of_birth ?? 'NA' }}</div>
          </div>
        </div>
        <div class="numbers">
          <div>{{ $seg1 }}</div>
          <div>{{ $seg2 }}</div>
          <div>{{ $seg3 }}</div>
          <div>{{ $seg4 }}</div>
        </div>
        <div class="footer">
          <div>
            <div class="id">ID: {{ $user->user_id ?? ($user->id ?? 'NA') }}</div>
            <div class="valid">VALID THRU {{ $validThru }}</div>
          </div>
          <div class="badge">LIFE TIME ACCESS</div>
        </div>
      </div>
    </div>
    @if(! $loop->last)
      <div class="pagebreak"></div>
    @endif
  @endforeach

  @if(!empty($autoprint))
    <script>
      window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 350);
      });
    </script>
  @endif
</body>
</html>
