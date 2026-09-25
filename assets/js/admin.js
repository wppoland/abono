( function () {
    var labelInput = document.getElementById( 'subscribe_label' );
    var defaultBox = document.getElementById( 'subscribe_default' );
    var preview    = document.getElementById( 'subscribe-preview' );
    var previewText = document.getElementById( 'subscribe-preview-text' );

    if ( ! preview || ! previewText ) {
        return;
    }

    var fallback = preview.getAttribute( 'data-fallback' ) || '';

    function sync() {
        if ( labelInput ) {
            var value = labelInput.value.trim();
            previewText.textContent = value !== '' ? value : fallback;
        }
        if ( defaultBox ) {
            preview.classList.toggle( 'is-checked', defaultBox.checked );
        }
    }

    if ( labelInput ) {
        labelInput.addEventListener( 'input', sync );
    }
    if ( defaultBox ) {
        defaultBox.addEventListener( 'change', sync );
    }
    sync();
} )();
