<!-- upload.blade.php -->
<form action="{{ route('image.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image">
    <button type="submit">Upload Image</button>
</form>
