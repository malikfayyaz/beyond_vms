
@section('formbuilder')
<div x-data="{
    formData: '',
    submitForm() {
        fetch('{{-- route('form.save') --}}', {
            method: 'POST',
            body: JSON.stringify({ form: this.formData })
        })
        .then(response => response.json())
        .then(data => {
            alert('Form saved successfully!');
        })
        .catch(error => console.error('Error:', error));
    }
}">
    <div id="form-builder"></div>
    <button @click="submitForm">Save Form</button>
</div>

<script>
    $(document).ready(function () {
        const formBuilder = $('#form-builder').formBuilder();
        document.querySelector('[x-data]').__x.$data.formData = formBuilder.actions.getData('json');
    });
</script>
@endsection
