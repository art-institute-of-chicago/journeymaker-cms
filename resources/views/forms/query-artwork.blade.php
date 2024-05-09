@pushOnce('extra_css')
    <link href="/assets/twill/css/custom.css" rel="stylesheet" />
@endPushOnce

<div class="custom">
    <a17-query-artwork
        label="{{ $label }}"
        {!! $formFieldName() !!}
        @if ($required) :required="true" @endif
        @if ($note) note="{{ $note }}" @endif
        @if ($disabled) disabled @endif
        @if ($readOnly) readonly @endif
        @if ($placeholder) placeholder="{{ $placeholder }}" @endif
        @if ($updateFormFields) :update-form-fields="{{ json_encode($updateFormFields) }}" @endif
    ></a17-query-artwork>
</div>
