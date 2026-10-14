<script>
    function imagePreviewForTest(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#myImg").change(function() {
        imagePreviewForTest(this);
    });
</script>
