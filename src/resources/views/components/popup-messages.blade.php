@php
    $popupMessages = collect();

    foreach (['success', 'error', 'info'] as $type) {
        if (session($type)) {
            $popupMessages->push([
                'type' => $type,
                'message' => session($type),
            ]);
        }
    }

    foreach ($errors->all() as $error) {
        $popupMessages->push([
            'type' => 'error',
            'message' => $error,
        ]);
    }
@endphp

@if($popupMessages->isNotEmpty())
    <div class="popup-wrapper" id="popup-wrapper" role="status" aria-live="polite">
        @foreach($popupMessages as $popup)
            <div class="popup-message popup-{{ $popup['type'] }}">
                {{ $popup['message'] }}
            </div>
        @endforeach
    </div>

    <script>
        setTimeout(() => {
            const popupWrapper = document.getElementById('popup-wrapper');

            if (popupWrapper) {
                popupWrapper.style.display = 'none';
            }
        }, 8000);
    </script>
@endif